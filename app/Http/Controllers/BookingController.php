<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking berdasarkan Role.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return $this->indexAdmin();
        }
        return $this->indexCustomer();
    }

    public function indexCustomer()
    {
        $bookings = Booking::with(['unit.project', 'unit.tipe'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('booking.customer.index', compact('bookings'));
    }

    public function indexAdmin()
    {
        $bookings = Booking::with(['user', 'unit.project', 'unit.tipe'])
            ->latest()
            ->get();

        return view('booking.admin.index', compact('bookings'));
    }

    /**
     * AJAX - AMBIL UNIT BERDASARKAN PROYEK
     * Dipanggil ketika dropdown proyek berubah di halaman Customer.
     */
    public function getUnitsByProject($projectId)
    {
        try {
            $units = Unit::with(['tipe'])
                ->where('project_id', $projectId)
                ->where('status', 'Tersedia') 
                ->get()
                ->map(function ($unit) {
                    return [
                        'id' => $unit->id,
                        'block' => $unit->block,
                        'no_unit' => $unit->no_unit,
                        'nama_tipe' => $unit->tipe ? $unit->tipe->nama_tipe : 'N/A',
                        'harga_format' => $unit->tipe ? 'Rp ' . number_format($unit->tipe->harga, 0, ',', '.') : 'Rp 0'
                    ];
                });

            return response()->json($units);
        } catch (Exception $e) {
            Log::error("AJAX Booking Error: " . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data unit'], 500);
        }
    }

    /**
     * Tampilan form booking baru untuk Customer.
     */
    public function create()
    {
        // Hanya tampilkan proyek yang memiliki unit dengan status 'Tersedia'
        $projects = Project::whereHas('units', function($q) {
            $q->where('status', 'Tersedia');
        })->orderBy('nama_proyek')->get();

        return view('booking.customer.create', compact('projects'));
    }

    /**
     * Proses penyimpanan booking baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id'         => 'required|exists:units,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'dokumen_ktp'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan'      => 'nullable|string|max:255',
        ], [
            'unit_id.required' => 'Silakan pilih unit terlebih dahulu.',
            'dokumen_ktp.required' => 'Wajib mengunggah scan/foto KTP.',
            'dokumen_ktp.max'  => 'Ukuran file KTP tidak boleh lebih dari 2MB.'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Lock unit untuk menghindari race condition
                $unit = Unit::lockForUpdate()->findOrFail($request->unit_id);

                if ($unit->status !== 'Tersedia') {
                    throw new Exception('Maaf, unit ini baru saja dipesan oleh orang lain atau tidak tersedia.');
                }

                // --- Upload ke Cloudinary ---
                $pathKtp = null;
                if ($request->hasFile('dokumen_ktp')) {
                    $file = $request->file('dokumen_ktp');
                    
                    $response = Http::asMultipart()->post(
                        'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                        [
                            ['name' => 'file', 'contents' => fopen($file->getRealPath(), 'r'), 'filename' => $file->getClientOriginalName()],
                            ['name' => 'upload_preset', 'contents' => env('CLOUDINARY_UPLOAD_PRESET', 'kedamark')],
                            ['name' => 'folder', 'contents' => 'dokumen_booking'],
                        ]
                    );

                    $result = $response->json();
                    if (!isset($result['secure_url'])) {
                        Log::error("Cloudinary Error: " . json_encode($result));
                        throw new Exception('Gagal mengunggah dokumen ke server gambar.');
                    }
                    $pathKtp = $result['secure_url'];
                }

                // --- Simpan Data Booking ---
                Booking::create([
                    'user_id'         => Auth::id(),
                    'nama'            => Auth::user()->name,
                    'project_id'      => $unit->project_id,
                    'unit_id'         => $unit->id,
                    'tanggal_booking' => $request->tanggal_booking,
                    'dokumen'         => $pathKtp,
                    'keterangan'      => $request->keterangan,
                    'status'          => 'pending',
                ]);

                // Update status unit menjadi 'Dibooking' agar tidak muncul di pilihan orang lain
                $unit->update(['status' => 'Dibooking']);

                // Sinkronisasi angka stok di tabel projects
                $this->syncStock($unit->project_id);

                return redirect()->route('customer.booking.index')
                    ->with('success', 'Booking berhasil diajukan! Admin akan memverifikasi pesanan Anda.');
            });
        } catch (Exception $e) {
            Log::error('Booking Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Update status oleh Admin (Setujui/Tolak).
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak,pending']);

        try {
            return DB::transaction(function () use ($request, $booking) {
                $oldStatus = $booking->status;
                $booking->update(['status' => $request->status]);
                $unit = $booking->unit;

                if ($request->status == 'disetujui') {
                    // Jika disetujui, unit dianggap terjual
                    $unit->update(['status' => 'Terjual']);
                    $this->createNotification($booking->user_id, 'Booking Disetujui!', "Unit {$unit->block}-{$unit->no_unit} telah disetujui. Silakan hubungi kantor pemasaran.");
                } elseif ($request->status == 'ditolak') {
                    // Jika ditolak, kembalikan status unit menjadi Tersedia agar bisa dibeli orang lain
                    $unit->update(['status' => 'Tersedia']);
                    $this->createNotification($booking->user_id, 'Booking Ditolak', "Maaf, pengajuan unit {$unit->block}-{$unit->no_unit} belum bisa disetujui.");
                }

                $this->syncStock($unit->project_id);

                return redirect()->route('admin.booking.index')->with('success', 'Status booking berhasil diperbarui.');
            });
        } catch (Exception $e) {
            Log::error("Update Status Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status.');
        }
    }

    /**
     * Helper: Sinkronisasi Angka Stok di Tabel Projects
     */
    private function syncStock($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $stats = Unit::where('project_id', $projectId)
                ->selectRaw("
                    count(case when status = 'Tersedia' then 1 end) as tersedia,
                    count(case when status = 'Dibooking' then 1 end) as booked,
                    count(case when status = 'Terjual' then 1 end) as terjual
                ")
                ->first();

            $project->update([
                'tersedia' => $stats->tersedia ?? 0,
                'booked'   => $stats->booked ?? 0,
                'terjual'  => $stats->terjual ?? 0,
            ]);
        }
    }

    /**
     * Helper: Buat Notifikasi DB
     */
    private function createNotification($userId, $title, $message) 
    {
        Notification::create([
            'user_id' => $userId,
            'type'    => 'booking',
            'title'   => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }
}