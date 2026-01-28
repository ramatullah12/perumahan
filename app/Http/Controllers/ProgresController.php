<?php

namespace App\Http\Controllers;

use App\Models\Progres;
use App\Models\Unit;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
// Wajib gunakan Cloudinary untuk Vercel
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
     */
    public function indexOwner()
    {
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
        $bookings = Booking::with(['unit.project', 'unit.progres_history' => function($query) {
                $query->latest(); 
            }])
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->get();

        return view('progres.customer.index', compact('bookings'));
    }

    /**
     * ADMIN - HALAMAN TAMBAH PROGRES
     */
    public function create(Request $request)
    {
        $unit_id = $request->query('unit_id');
        $unit = Unit::with('project')->findOrFail($unit_id);
        
        return view('progres.admin.create', compact('unit'));
    }

    /**
     * ADMIN - SIMPAN UPDATE PROGRES (FIXED FOR VERCEL)
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

        try {
            DB::transaction(function () use ($request) {
                $fotoUrl = null;

                if ($request->hasFile('foto')) {
                    // Upload ke Cloudinary dengan preset 'kedamark'
                    $uploadedFile = Cloudinary::upload($request->file('foto')->getRealPath(), [
                        'upload_preset' => 'kedamark',
                        'folder' => 'progres_pembangunan'
                    ]);
                    $fotoUrl = $uploadedFile->getSecurePath();
                }

                // 1. Simpan ke tabel 'progres'
                Progres::create([
                    'unit_id'    => $request->unit_id,
                    'persentase' => $request->persentase,
                    'tahap'      => $request->tahap,
                    'keterangan' => $request->keterangan,
                    'foto'       => $fotoUrl, // Menyimpan URL HTTPS Cloudinary
                ]);

                // 2. Sinkronisasi ke tabel 'units'
                Unit::where('id', $request->unit_id)->update([
                    'progres' => $request->persentase
                ]);
            });

            return redirect()->route('admin.progres.index')->with('success', 'Progres pembangunan berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal update progres: ' . $e->getMessage());
        }
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
     * ADMIN - PROSES UPDATE DATA (FIXED FOR VERCEL)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'tahap'      => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $unit = Unit::findOrFail($id);
                $fotoUrl = $unit->latestProgres->foto ?? null;

                if ($request->hasFile('foto')) {
                    // Upload baru ke Cloudinary
                    $uploadedFile = Cloudinary::upload($request->file('foto')->getRealPath(), [
                        'upload_preset' => 'kedamark',
                        'folder' => 'progres_pembangunan'
                    ]);
                    $fotoUrl = $uploadedFile->getSecurePath();
                    // Catatan: Cloudinary menangani penghapusan manual via API jika perlu, 
                    // namun untuk history progres, foto lama biasanya tetap disimpan.
                }

                Progres::create([
                    'unit_id'    => $id,
                    'persentase' => $request->persentase,
                    'tahap'      => $request->tahap,
                    'keterangan' => $request->keterangan,
                    'foto'       => $fotoUrl,
                ]);

                $unit->update([
                    'progres' => $request->persentase
                ]);
            });

            return redirect()->route('admin.progres.index')->with('success', 'Perubahan berhasil disimpan!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN - HAPUS CATATAN PROGRES
     */
    public function destroy($id)
    {
        $progres = Progres::findOrFail($id);
        // Penghapusan file di Cloudinary memerlukan Public ID, 
        // untuk sementara cukup hapus record database di Vercel.
        $progres->delete();
        return back()->with('success', 'Catatan progres berhasil dihapus!');
    }
}