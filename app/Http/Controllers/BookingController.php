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
     * MAIN INDEX - Menghilangkan Error index()
     */
    public function index()
    {
        // Mendeteksi role dari user yang sedang login
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

        // Sesuai dengan folder path Anda: booking/customer/index.blade.php
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
     * CUSTOMER - FORM BOOKING BARU (Perbaikan Unit Tidak Muncul)
     */
    public function create()
    {
        // Mengambil data unit dan mengelompokkan per proyek untuk dikirim langsung ke view
        $units = Unit::with(['project', 'tipe'])
            ->where('status', 'Tersedia')
            ->get()
            ->groupBy(function($item) {
                return $item->project->nama_proyek ?? 'Tanpa Proyek';
            });

        // Tetap kirim $projects jika Anda ingin menggunakan fitur AJAX nantinya
        $projects = Project::whereHas('units', function($q) {
            $q->where('status', 'Tersedia');
        })->get();

        return view('booking.customer.create', compact('projects', 'units'));
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
                // Lock unit untuk mencegah double booking
                $unit = Unit::lockForUpdate()->findOrFail($request->unit_id);

                if ($unit->status !== 'Tersedia') {
                    throw new Exception('Maaf, unit ini sudah tidak tersedia.');
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
                        throw new Exception('Gagal mengupload dokumen ke Cloudinary.');
                    }
                }

                // Buat data booking
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

                // Update status unit menjadi 'Dibooking' agar tidak dibooking orang lain
                $unit->update(['status' => 'Dibooking']);

                return redirect()->route('customer.booking.index')
                    ->with('success', 'Booking berhasil diajukan! Tunggu verifikasi admin.');
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
        $request->validate(['status' => 'required|in:disetujui,ditolak,pending']);

        try {
            return DB::transaction(function () use ($request, $booking) {
                $booking->update(['status' => $request->status]);
                $unit = $booking->unit;

                if ($request->status == 'disetujui') {
                    $unit->update(['status' => 'Terjual']);
                    $this->createNotification($booking->user_id, 'Booking Disetujui!', 'Booking Unit ' . $unit->no_unit . ' telah disetujui.');
                } elseif ($request->status == 'ditolak') {
                    // Unit tersedia kembali jika ditolak
                    $unit->update(['status' => 'Tersedia']);
                    $this->createNotification($booking->user_id, 'Booking Ditolak', 'Booking Unit ' . $unit->no_unit . ' ditolak.');
                }

                return redirect()->route('admin.booking.index')->with('success', 'Status berhasil diperbarui.');
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // Helper untuk notifikasi agar kode lebih bersih
    private function createNotification($userId, $title, $message) {
        Notification::create([
            'user_id' => $userId,
            'type'    => 'booking',
            'title'   => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }
}