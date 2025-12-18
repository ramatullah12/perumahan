<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
     * EDIT: Menambahkan pengelompokan (groupBy) berdasarkan proyek
     */
    public function create()
    {
        // Mengambil unit yang tersedia dan dikelompokkan berdasarkan nama proyek
        $units = Unit::with(['project', 'tipe'])
                     ->where('status', 'Tersedia')
                     ->get()
                     ->groupBy(function($unit) {
                         // Mengelompokkan berdasarkan nama proyek dari relasi
                         return $unit->project->nama_proyek ?? 'Tanpa Proyek';
                     });
                     
        return view('booking.customer.create', compact('units'));
    }

    /**
     * CUSTOMER - SIMPAN BOOKING
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id'         => 'required|exists:units,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'dokumen_ktp'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request) {
            // Lock unit agar tidak dibooking dua orang secara bersamaan (Race Condition)
            $unit = Unit::lockForUpdate()->findOrFail($request->unit_id);

            // Double Check Status
            if ($unit->status !== 'Tersedia') {
                return redirect()->back()->with('error', 'Maaf, unit ini baru saja dibooking atau sudah terjual.');
            }

            // Upload KTP
            $pathKtp = null;
            if ($request->hasFile('dokumen_ktp')) {
                $pathKtp = $request->file('dokumen_ktp')->store('dokumen_booking', 'public');
            }

            // Simpan Data Booking
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

            // Ubah status unit menjadi 'Dibooking' agar tidak muncul lagi di pilihan unit customer lain
            $unit->update(['status' => 'Dibooking']);

            return redirect()->route('customer.booking.index')
                ->with('success', 'Booking berhasil dikirim! Silakan tunggu konfirmasi Admin.');
        });
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
            'status' => 'required|in:disetujui,ditolak',
        ]);

        return DB::transaction(function () use ($request, $booking) {
            
            $booking->update(['status' => $request->status]);
            $unit = $booking->unit;

            if ($request->status == 'disetujui') {
                // Jika disetujui, unit resmi terjual
                $unit->update(['status' => 'Terjual']);
            } elseif ($request->status == 'ditolak') {
                // Jika ditolak, unit tersedia kembali untuk customer lain
                $unit->update(['status' => 'Tersedia']);
            }

            return redirect()->route('admin.booking.index')
                ->with('success', 'Status booking berhasil diperbarui!');
        });
    }
}