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
     * MAIN INDEX
     * Menangani rute standar 'booking.index' dan mengarahkan berdasarkan role.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return $this->indexAdmin();
        }
        return $this->indexCustomer();
    }

    /**
     * CUSTOMER - DAFTAR BOOKING
     */
    public function indexCustomer()
    {
        $bookings = Booking::with(['unit.project', 'unit.tipe'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('booking.customer.index', compact('bookings'));
    }

    /**
     * ADMIN - DAFTAR SEMUA BOOKING
     */
    public function indexAdmin()
    {
        $bookings = Booking::with(['user', 'unit.project', 'unit.tipe'])
            ->latest()
            ->get();

        return view('booking.admin.index', compact('bookings'));
    }

    /**
     * DETAIL BOOKING (ADMIN & CUSTOMER)
     */
    public function show(Booking $booking)
    {
        // Pastikan customer hanya bisa melihat booking miliknya sendiri
        if (Auth::user()->role === 'customer' && $booking->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $booking->load(['user', 'unit.project', 'unit.tipe']);
        
        $view = Auth::user()->role === 'admin' ? 'booking.admin.show' : 'booking.customer.show';
        return view($view, compact('booking'));
    }

    /**
     * CUSTOMER - FORM BOOKING BARU
     */
    public function create()
    {
        $projects = Project::whereHas('units', function($q) {
            $q->where('status', 'Tersedia');
        })->get();

        return view('booking.customer.create', compact('projects'));
    }

    /**
     * AJAX - AMBIL UNIT BERDASARKAN PROYEK
     */
    public function getUnitsByProject($projectId)
    {
        $units = Unit::with('tipe')
            ->where('project_id', $projectId)
            ->where('status', 'Tersedia')
            ->get();

        return response()->json($units);
    }

    /**
     * CUSTOMER - SIMPAN DATA BOOKING
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
                    throw new Exception('Maaf, unit ini baru saja dibooking atau tidak tersedia.');
                }

                $pathKtp = null;
                if ($request->hasFile('dokumen_ktp')) {
                    $file = $request->file('dokumen_ktp');
                    
                    // Upload ke Cloudinary
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
                        throw new Exception('Gagal mengupload dokumen KTP.');
                    }
                }

                $booking = Booking::create([
                    'user_id'         => Auth::id(),
                    'nama'            => Auth::user()->name, 
                    'project_id'      => $unit->project_id,
                    'unit_id'         => $unit->id,
                    'tanggal_booking' => $request->tanggal_booking,
                    'dokumen'         => $pathKtp,
                    'keterangan'      => $request->keterangan,
                    'status'          => 'pending',
                ]);

                // Update status unit menjadi 'Dibooking'
                $unit->update(['status' => 'Dibooking']);

                return redirect()->route('customer.booking.index')
                    ->with('success', 'Booking berhasil! Silakan tunggu verifikasi admin.');
            });
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * ADMIN - UPDATE STATUS (SETUJU/TOLAK)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,pending',
        ]);

        try {
            return DB::transaction(function () use ($request, $booking) {
                $booking->update(['status' => $request->status]);
                $unit = $booking->unit;

                if ($request->status == 'disetujui') {
                    $unit->update(['status' => 'Terjual']);

                    Notification::create([
                        'user_id' => $booking->user_id,
                        'type'    => 'booking',
                        'title'   => 'Booking Disetujui!',
                        'message' => 'Booking Anda untuk Unit ' . $unit->no_unit . ' telah disetujui.',
                        'is_read' => false,
                    ]);
                } elseif ($request->status == 'ditolak') {
                    // Jika ditolak, unit tersedia kembali
                    $unit->update(['status' => 'Tersedia']);

                    Notification::create([
                        'user_id' => $booking->user_id,
                        'type'    => 'booking',
                        'title'   => 'Booking Ditolak',
                        'message' => 'Mohon maaf, booking Anda untuk Unit ' . $unit->no_unit . ' ditolak.',
                        'is_read' => false,
                    ]);
                } else {
                    $unit->update(['status' => 'Dibooking']);
                }

                return redirect()->route('admin.booking.index')
                    ->with('success', 'Status booking berhasil diperbarui.');
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}