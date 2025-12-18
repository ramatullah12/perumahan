<?php

namespace App\Http\Controllers;

use App\Models\Progres;
use App\Models\Unit;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProgresController extends Controller
{
    /**
     * ADMIN - DAFTAR UNIT YANG DIPANTAU
     */
    public function index()
    {
        // Menampilkan unit yang sudah laku (Dibooking/Terjual) untuk diupdate progresnya
        $units = Unit::with(['project', 'latestProgres', 'booking.user'])
            ->whereIn('status', ['Dibooking', 'Terjual'])
            ->get();

        return view('progres.admin.index', compact('units'));
    }

    /**
     * CUSTOMER - LIHAT PROGRES UNIT MILIK SENDIRI
     * Fungsi ini untuk memperbaiki error di sisi Customer
     */
    public function indexCustomer()
    {
        // Mencari booking milik user login yang sudah disetujui
        $bookings = Booking::with(['unit.project', 'unit.progres' => function($query) {
                $query->latest(); // Urutkan progres dari yang terbaru
            }])
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->get();

        return view('progres.customer.index', compact('bookings'));
    }

    /**
     * ADMIN - SIMPAN UPDATE PROGRES
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id'    => 'required|exists:units,id',
            'persentase' => 'required|numeric|min:0|max:100',
            'tahap'      => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['unit_id', 'persentase', 'tahap', 'keterangan']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('progres_pembangunan', 'public');
        }

        // Simpan ke tabel progres
        Progres::create($data);

        // OTOMATIS: Update kolom 'progres_pembangunan' di tabel units agar sinkron
        Unit::where('id', $request->unit_id)->update([
            'progres_pembangunan' => $request->persentase
        ]);

        return back()->with('success', 'Progres pembangunan berhasil diperbarui!');
    }

    /**
     * ADMIN - HAPUS PROGRES
     */
    public function destroy(Progres $progres)
    {
        if ($progres->foto) {
            Storage::disk('public')->delete($progres->foto);
        }
        
        $progres->delete();
        return back()->with('success', 'Catatan progres berhasil dihapus!');
    }
}