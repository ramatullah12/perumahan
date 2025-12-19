<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingController extends Controller
{
    /**
     * CUSTOMER - LIHAT BOOKING SENDIRI
     */
    public function indexCustomer()
    {
        // Eager loading unit.project wajib ada agar foto proyek di Admin sinkron ke Customer
        $bookings = Booking::with(['unit.project', 'unit.tipe'])
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->get();

        return view('booking.customer.index', compact('bookings'));
    }

    /**
     * CUSTOMER - FORM BOOKING
     * Menampilkan daftar unit yang dikelompokkan berdasarkan proyek
     */
    public function create()
    {
        // Mengambil unit tersedia dan mengelompokkan berdasarkan nama proyek
        // Gunakan get() agar menghasilkan Collection, bukan boolean
        $units = Unit::with(['project', 'tipe'])
                     ->where('status', 'Tersedia')
                     ->get()
                     ->groupBy(function($unit) {
                         return $unit->project->nama_proyek ?? 'Tanpa Proyek';
                     });
                     
        // Mengambil daftar proyek saja (untuk dependent dropdown jika diperlukan)
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
     * CUSTOMER - SIMPAN BOOKING
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
                // Lock unit untuk mencegah "Race Condition"
                $unit = Unit::lockForUpdate()->findOrFail($request->unit_id);

                if ($unit->status !== 'Tersedia') {
                    throw new Exception('Maaf, unit ini baru saja dibooking atau sudah tidak tersedia.');
                }

                // Simpan Dokumen KTP ke storage/public/dokumen_booking
                $pathKtp = null;
                if ($request->hasFile('dokumen_ktp')) {
                    $pathKtp = $request->file('dokumen_ktp')->store('dokumen_booking', 'public');
                }

                // Buat Data Booking
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

                // Update status unit agar tidak muncul di pilihan unit customer lain
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
            'status' => 'required|in:disetujui,ditolak',
        ]);

        try {
            return DB::transaction(function () use ($request, $booking) {
                
                $booking->update(['status' => $request->status]);
                $unit = $booking->unit;

                if ($request->status == 'disetujui') {
                    // Unit resmi terjual
                    $unit->update(['status' => 'Terjual']);
                } else if ($request->status == 'ditolak') {
                    // Unit tersedia kembali
                    $unit->update(['status' => 'Tersedia']);
                }

                return redirect()->route('admin.booking.index')
                    ->with('success', 'Status booking berhasil diperbarui.');
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}