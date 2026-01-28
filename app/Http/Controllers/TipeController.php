<?php

namespace App\Http\Controllers;

use App\Models\Tipe;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TipeController extends Controller
{
    /**
     * Menampilkan daftar tipe
     */
    public function index() {
        $tipes = Tipe::with('project')->latest()->get();
        return view('tipe.admin.index', compact('tipes'));
    }

    /**
     * Form tambah tipe
     */
    public function create() {
        $projects = Project::all();
        return view('tipe.admin.create', compact('projects'));
    }

    /**
     * Menyimpan data tipe baru
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'project_id'    => 'required|exists:projects,id',
            'nama_tipe'     => 'required|string|max:255',
            'harga'         => 'required|numeric',
            'luas_tanah'    => 'required|integer',
            'luas_bangunan' => 'required|integer',
            'kamar_tidur'   => 'required|integer',
            'kamar_mandi'   => 'required|integer',
            'gambar'        => 'required|image|mimes:jpg,png,jpeg,webp|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');
                
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
                            'contents' => env('CLOUDINARY_UPLOAD_PRESET', 'kedamark'),
                        ],
                        [
                            'name'     => 'folder',
                            'contents' => 'tipes',
                        ],
                    ]
                );

                $result = $response->json();

                if (isset($result['secure_url'])) {
                    $validated['gambar'] = $result['secure_url'];
                } else {
                    Log::error('Cloudinary Store Error: ', $result);
                    return back()->withInput()->withErrors(['gambar' => 'Cloudinary Error: ' . ($result['error']['message'] ?? 'Konfigurasi Cloud Name/Preset Salah')]);
                }
            } catch (\Exception $e) {
                Log::error('Cloudinary Exception: ' . $e->getMessage());
                return back()->withInput()->withErrors(['gambar' => 'Koneksi Cloudinary Gagal.']);
            }
        }

        Tipe::create($validated);

        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Ditambahkan!');
    }

    /**
     * Form edit tipe
     */
    public function edit(Tipe $tipe) {
        $projects = Project::all();
        return view('tipe.admin.edit', compact('tipe', 'projects'));
    }

    /**
     * Memperbarui data tipe
     */
    public function update(Request $request, Tipe $tipe) {
        $validated = $request->validate([
            'project_id'    => 'required|exists:projects,id',
            'nama_tipe'     => 'required|string|max:255',
            'harga'         => 'required|numeric',
            'luas_tanah'    => 'required|integer',
            'luas_bangunan' => 'required|integer',
            'kamar_tidur'   => 'required|integer',
            'kamar_mandi'   => 'required|integer',
            'gambar'        => 'nullable|image|mimes:jpg,png,jpeg,webp|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');
                
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
                            'contents' => env('CLOUDINARY_UPLOAD_PRESET', 'kedamark'),
                        ],
                        [
                            'name'     => 'folder',
                            'contents' => 'tipes',
                        ],
                    ]
                );

                $result = $response->json();
                
                if (isset($result['secure_url'])) {
                    $validated['gambar'] = $result['secure_url'];
                } else {
                    return back()->withInput()->withErrors(['gambar' => 'Gagal Update Gambar.']);
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['gambar' => 'Masalah Koneksi Cloudinary.']);
            }
        }

        $tipe->update($validated);

        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Diperbarui!');
    }

    /**
     * Menghapus data tipe
     */
    public function destroy(Tipe $tipe) {
        // Opsional: Cek jika tipe masih digunakan di tabel Unit sebelum hapus
        $tipe->delete();
        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Dihapus!');
    }
}