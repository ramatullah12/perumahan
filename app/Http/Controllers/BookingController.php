<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
// Library Cloudinary wajib ada
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookingController extends Controller
{
    /**
     * CUSTOMER - LIHAT BOOKING SENDIRI
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
     * CUSTOMER - FORM BOOKING
     */
    public function create()
    {
        $units = Unit::with(['project', 'tipe'])
                     ->where('status', 'Tersedia')
                     ->get()
                     ->groupBy(function($unit) {
                         return $unit->project->nama_proyek ?? 'Tanpa Proyek';
                     });
                     
        $projects = Project::whereHas('units', function($q) {
            $q->where('status', 'Tersedia');
        })->get();

        return view('booking.customer.create', compact('units', 'projects'));
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
     * CUSTOMER - SIMPAN BOOKING (FIXED FOR VERCEL)
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
                $unit = Unit::lockForUpdate()->findOrFail($request->unit_id);

                if ($unit->status !== 'Tersedia') {
                    throw new Exception('Maaf, unit ini baru saja dibooking atau sudah tidak tersedia.');
                }

                $pathKtp = null;
                if ($request->hasFile('dokumen_ktp')) {
                    // Upload ke Cloudinary menggunakan preset 'kedamark'
                    $uploadedFile = Cloudinary::upload($request->file('dokumen_ktp')->getRealPath(), [
                        'upload_preset' => 'kedamark', // Sesuai dashboard Cloudinary Anda
                        'folder' => 'dokumen_booking'
                    ]);
                    $pathKtp = $uploadedFile->getSecurePath(); // Ambil URL HTTPS permanen
                }

                Booking::create([
                    'user_id'         => Auth::id(),
                    'nama'            => Auth::user()->name, 
                    'project_id'      => $unit->project_id,
                    'unit_id'         => $unit->id,
                    'tanggal_booking' => $request->tanggal_booking,
                    'dokumen'         => $pathKtp, // Simpan URL Cloudinary
                    'keterangan'      => $request->keterangan,
                    'status'          => 'pending',
                ]);

                $unit->update(['status' => 'Dibooking']);

                return redirect()->route('customer.booking.index')
                    ->with('success', 'Booking berhasil! Silakan tunggu verifikasi admin.');
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * ADMIN - LIHAT SEMUA BOOKING
     */
    public function indexAdmin()
    {
        $bookings = Booking::with(['user', 'unit.project', 'unit.tipe'])
                            ->latest()
                            ->get();

        return view('booking.admin.index', compact('bookings'));
    }

    /**
     * ADMIN - UPDATE STATUS BOOKING
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

                    Notification::updateOrCreate(
                        ['user_id' => $booking->user_id, 'type' => 'booking'],
                        [
                            'title'   => 'Booking Disetujui!',
                            'message' => 'Selamat! Booking Anda untuk Unit ' . $unit->no_unit . ' di ' . $booking->unit->project->nama_proyek . ' telah disetujui.',
                            'is_read' => false,
                            'created_at' => now()
                        ]
                    );
                } else {
                    $unit->update(['status' => ($request->status == 'pending' ? 'Dibooking' : 'Tersedia')]);
                    Notification::where('user_id', $booking->user_id)->where('type', 'booking')->delete();
                }

                return redirect()->route('admin.booking.index')
                    ->with('success', 'Status diperbarui.');
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}