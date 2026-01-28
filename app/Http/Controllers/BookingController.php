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
        // Memuat relasi berlapis: Unit -> Project & Unit -> Tipe
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
     * Ini memastikan Tipe Unit muncul secara dinamis di form.
     */
    public function getUnitsByProject($projectId)
    {
        // Eager load 'tipe' agar harga dan nama tipe bisa diakses
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
                    'harga' => $unit->tipe ? $unit->tipe->harga : 0,
                    'harga_format' => $unit->tipe ? 'Rp ' . number_format($unit->tipe->harga, 0, ',', '.') : 'Rp 0'
                ];
            });

        return response()->json($units);
    }

    /**
     * Tampilan form booking baru untuk Customer.
     */
    public function create()
    {
        // Ambil proyek yang memiliki setidaknya satu unit tersedia
        $projects = Project::whereHas('units', function($q) {
            $q->where('status', 'Tersedia');
        })->get();

        return view('booking.customer.create', compact('projects'));
    }

    /**
     * Proses penyimpanan booking baru dengan proteksi transaksi.
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
            'dokumen_ktp.max'  => 'Ukuran file KTP tidak boleh lebih dari 2MB.'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Lock unit untuk mencegah double booking di milidetik yang sama
                $unit = Unit::lockForUpdate()->findOrFail($request->unit_id);

                if ($unit->status !== 'Tersedia') {
                    throw new Exception('Maaf, unit ini baru saja dipesan oleh orang lain.');
                }

                // Logika Upload ke Cloudinary
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
                    if (isset($result['secure_url'])) {
                        $pathKtp = $result['secure_url'];
                    } else {
                        Log::error('Cloudinary Error: ', $result);
                        throw new Exception('Gagal mengunggah dokumen ke server.');
                    }
                }

                // Simpan data ke tabel Bookings
                Booking::create([
                    'user_id'         => Auth::id(),
                    'nama'            => Auth::user()->name, // Mengisi kolom nama yang sebelumnya error
                    'project_id'      => $unit->project_id,
                    'unit_id'         => $unit->id,
                    'tanggal_booking' => $request->tanggal_booking,
                    'dokumen'         => $pathKtp,
                    'keterangan'      => $request->keterangan,
                    'status'          => 'pending',
                ]);

                // Update status unit menjadi 'Dibooking' (menghilangkan dari daftar 'Tersedia')
                $unit->update(['status' => 'Dibooking']);

                return redirect()->route('customer.booking.index')
                    ->with('success', 'Booking berhasil diajukan! Silakan tunggu verifikasi admin.');
            });
        } catch (Exception $e) {
            Log::error('Booking Error: ' . $e->getMessage());
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
                $booking->update(['status' => $request->status]);
                $unit = $booking->unit;

                if ($request->status == 'disetujui') {
                    $unit->update(['status' => 'Terjual']);
                    $this->createNotification($booking->user_id, 'Booking Disetujui!', "Booking unit {$unit->block}-{$unit->no_unit} telah disetujui.");
                } elseif ($request->status == 'ditolak') {
                    // Unit dikembalikan ke status 'Tersedia' agar bisa dipesan lagi
                    $unit->update(['status' => 'Tersedia']);
                    $this->createNotification($booking->user_id, 'Booking Ditolak', "Mohon maaf, booking unit {$unit->block}-{$unit->no_unit} ditolak.");
                }

                return redirect()->route('admin.booking.index')->with('success', 'Status booking berhasil diperbarui.');
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Notifikasi Internal
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