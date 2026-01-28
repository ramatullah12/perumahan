<?php

namespace App\Http\Controllers;

use App\Models\Progres;
use App\Models\Unit;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // Gunakan ini untuk upload ke API
use Exception;

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
     * ADMIN - SIMPAN UPDATE PROGRES (Gaya HTTP Multipart untuk Vercel)
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
            return DB::transaction(function () use ($request) {
                $fotoUrl = null;

                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    
                    // Request langsung ke API Cloudinary
                    $response = Http::asMultipart()->post(
                        'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                        [
                            [
                                'name'     => 'file',
                                'contents' => fopen($file->getRealPath(), 'r'),
                                'filename' => $file->getClientOriginalName(),
                            ],
                            [
                                'name'     => 'upload_preset',
                                'contents' => 'kedamark',
                            ],
                            [
                                'name'     => 'folder',
                                'contents' => 'progres_pembangunan'
                            ],
                        ]
                    );

                    $result = $response->json();
                    
                    if (isset($result['secure_url'])) {
                        $fotoUrl = $result['secure_url'];
                    } else {
                        throw new Exception('Upload gagal: ' . ($result['error']['message'] ?? 'Unknown error'));
                    }
                }

                // 1. Simpan ke tabel 'progres'
                Progres::create([
                    'unit_id'    => $request->unit_id,
                    'persentase' => $request->persentase,
                    'tahap'      => $request->tahap,
                    'keterangan' => $request->keterangan,
                    'foto'       => $fotoUrl,
                ]);

                // 2. Sinkronisasi ke tabel 'units'
                Unit::where('id', $request->unit_id)->update([
                    'progres' => $request->persentase
                ]);

                return redirect()->route('admin.progres.index')->with('success', 'Progres pembangunan berhasil diperbarui!');
            });
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal update progres: ' . $e->getMessage());
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
     * ADMIN - PROSES UPDATE DATA (Gaya HTTP Multipart untuk Vercel)
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
            return DB::transaction(function () use ($request, $id) {
                $unit = Unit::findOrFail($id);
                // Default ambil foto lama
                $fotoUrl = $unit->latestProgres->foto ?? null;

                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    
                    $response = Http::asMultipart()->post(
                        'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                        [
                            [
                                'name'     => 'file',
                                'contents' => fopen($file->getRealPath(), 'r'),
                                'filename' => $file->getClientOriginalName(),
                            ],
                            [
                                'name'     => 'upload_preset',
                                'contents' => 'kedamark',
                            ],
                            [
                                'name'     => 'folder',
                                'contents' => 'progres_pembangunan'
                            ],
                        ]
                    );

                    $result = $response->json();
                    if (isset($result['secure_url'])) {
                        $fotoUrl = $result['secure_url'];
                    }
                }

                // Buat record progres baru (setiap update progres biasanya menambah record history)
                Progres::create([
                    'unit_id'    => $id,
                    'persentase' => $request->persentase,
                    'tahap'      => $request->tahap,
                    'keterangan' => $request->keterangan,
                    'foto'       => $fotoUrl,
                ]);

                // Update data progres di unit utama
                $unit->update([
                    'progres' => $request->persentase
                ]);

                return redirect()->route('admin.progres.index')->with('success', 'Perubahan berhasil disimpan!');
            });
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN - HAPUS CATATAN PROGRES
     */
    public function destroy($id)
    {
        $progres = Progres::findOrFail($id);
        $progres->delete();
        return back()->with('success', 'Catatan progres berhasil dihapus!');
    }
}