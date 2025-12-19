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
        // Menampilkan unit yang sudah Dibooking atau Terjual agar admin bisa update progresnya
        $units = Unit::with(['project', 'latestProgres', 'booking.user'])
            ->whereIn('status', ['Dibooking', 'Terjual'])
            ->get();

        return view('progres.admin.index', compact('units'));
    }

    /**
     * CUSTOMER - LIHAT PROGRES UNIT MILIK SENDIRI
     */
    public function indexCustomer()
    {
        // Mengambil data booking milik user login yang statusnya sudah disetujui
        // Pastikan path view diarahkan ke 'progres.customer.index' setelah folder Anda di-rename
        $bookings = Booking::with(['unit.project', 'unit.progres' => function($query) {
                $query->latest(); 
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

        // Simpan catatan baru ke tabel progres
        Progres::create($data);

        // Update persentase di tabel units agar data sinkron saat dipanggil di dashboard
        Unit::where('id', $request->unit_id)->update([
            'progres_pembangunan' => $request->persentase
        ]);

        return back()->with('success', 'Progres pembangunan berhasil diperbarui!');
    }

    /**
     * ADMIN - HAPUS CATATAN PROGRES
     */
    public function destroy($id)
    {
        $progres = Progres::findOrFail($id);

        if ($progres->foto) {
            Storage::disk('public')->delete($progres->foto);
        }
        
        $progres->delete();
        return back()->with('success', 'Catatan progres berhasil dihapus!');
    }
}