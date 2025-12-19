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
        $bookings = Booking::with(['unit.project', 'unit.progres' => function($query) {
                $query->latest(); 
            }])
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->get();

        // Pastikan nama folder view adalah 'customer'
        return view('progres.customer.index', compact('bookings'));
    }

    /**
     * ADMIN - HALAMAN TAMBAH PROGRES (Create)
     */
    public function create(Request $request)
    {
        // Menangkap unit_id dari parameter URL
        $unit_id = $request->query('unit_id');
        $unit = Unit::with('project')->findOrFail($unit_id);
        
        return view('progres.admin.create', compact('unit'));
    }

    /**
     * ADMIN - SIMPAN UPDATE PROGRES (Fungsi Store)
     * Digunakan dari create.blade.php
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

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            // Simpan ke folder public agar bisa di-link
            $fotoPath = $request->file('foto')->store('progres_pembangunan', 'public');
        }

        // 1. Simpan catatan ke tabel riwayat (progres)
        Progres::create([
            'unit_id'    => $request->unit_id,
            'persentase' => $request->persentase,
            'tahap'      => $request->tahap,
            'keterangan' => $request->keterangan,
            'foto'       => $fotoPath,
        ]);

        // 2. SINKRONISASI: Update kolom 'progres' di tabel 'units' agar dashboard berubah
        Unit::where('id', $request->unit_id)->update([
            'progres' => $request->persentase
        ]);

        // 3. Redirect kembali ke monitoring agar tidak muncul JSON
        return redirect()->route('admin.progres.index')->with('success', 'Progres pembangunan berhasil diperbarui!');
    }

    /**
     * ADMIN - HALAMAN EDIT PROGRES
     */
    public function edit($id)
    {
        $unit = Unit::with(['project', 'latestProgres'])->findOrFail($id);
        return view('progres.admin.edit', compact('unit'));
    }

    /**
     * ADMIN - PROSES UPDATE DATA (DARI HALAMAN EDIT)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'tahap'      => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $unit = Unit::findOrFail($id);
        
        // Ambil foto lama jika tidak ada unggahan baru
        $fotoPath = $unit->latestProgres->foto ?? null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('progres_pembangunan', 'public');
        }

        // 1. Simpan sebagai riwayat baru
        Progres::create([
            'unit_id'    => $id,
            'persentase' => $request->persentase,
            'tahap'      => $request->tahap,
            'keterangan' => $request->keterangan,
            'foto'       => $fotoPath,
        ]);

        // 2. SINKRONISASI: Update kolom 'progres' agar monitoring berubah
        $unit->update([
            'progres' => $request->persentase
        ]);

        return redirect()->route('admin.progres.index')->with('success', 'Perubahan berhasil disimpan!');
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