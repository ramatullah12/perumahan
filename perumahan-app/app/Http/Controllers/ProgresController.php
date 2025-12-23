<?php

namespace App\Http\Controllers;

use App\Models\Progres;
use App\Models\Unit;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgresController extends Controller
{
    /**
     * ADMIN - DAFTAR UNIT YANG DIPANTAU
     */
    public function index()
    {
        $units = Unit::with(['project', 'latestProgres', 'booking.user'])
            ->whereIn('status', ['Dibooking', 'Terjual'])
            ->latest()
            ->get();

        return view('progres.admin.index', compact('units'));
    }

    /**
     * OWNER - LIHAT SELURUH PEMBANGUNAN PROYEK
     * PERBAIKAN: Menambahkan method ini agar rute owner tidak error.
     */
    public function indexOwner()
    {
        // Owner melihat semua unit yang sudah mulai dibangun (ada progres)
        $units = Unit::with(['project', 'latestProgres'])
            ->where('progres', '>', 0)
            ->latest()
            ->get();

        return view('progres.owner.index', compact('units'));
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

        return view('progres.customer.index', compact('bookings'));
    }

    /**
     * ADMIN - HALAMAN TAMBAH PROGRES (Create)
     */
    public function create(Request $request)
    {
        $unit_id = $request->query('unit_id');
        $unit = Unit::with('project')->findOrFail($unit_id);
        
        return view('progres.admin.create', compact('unit'));
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

        // Gunakan Transaction agar sinkronisasi data terjamin aman
        DB::transaction(function () use ($request) {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('progres_pembangunan', 'public');
            }

            // 1. Simpan riwayat progres
            Progres::create([
                'unit_id'    => $request->unit_id,
                'persentase' => $request->persentase,
                'tahap'      => $request->tahap,
                'keterangan' => $request->keterangan,
                'foto'       => $fotoPath,
            ]);

            // 2. SINKRONISASI: Update kolom utama di tabel units
            Unit::where('id', $request->unit_id)->update([
                'progres' => $request->persentase
            ]);
        });

        return redirect()->route('admin.progres.index')->with('success', 'Progres pembangunan berhasil diperbarui!');
    }

    /**
     * ADMIN - HALAMAN EDIT PROGRES TERAKHIR
     */
    public function edit($id)
    {
        $unit = Unit::with(['project', 'latestProgres'])->findOrFail($id);
        return view('progres.admin.edit', compact('unit'));
    }

    /**
     * ADMIN - PROSES UPDATE DATA
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'tahap'      => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request, $id) {
            $unit = Unit::findOrFail($id);
            $fotoPath = $unit->latestProgres->foto ?? null;

            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($fotoPath) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $request->file('foto')->store('progres_pembangunan', 'public');
            }

            Progres::create([
                'unit_id'    => $id,
                'persentase' => $request->persentase,
                'tahap'      => $request->tahap,
                'keterangan' => $request->keterangan,
                'foto'       => $fotoPath,
            ]);

            $unit->update([
                'progres' => $request->persentase
            ]);
        });

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