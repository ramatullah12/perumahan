<?php

namespace App\Http\Controllers;

use App\Models\Tipe;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Gunakan ini sebagai pengganti SDK Cloudinary

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
     * Menyimpan data tipe baru (Gaya HTTP Multipart untuk Vercel)
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
                            'contents' => 'kedamark', // Preset Anda
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
                    return back()->withInput()->withErrors(['gambar' => 'Cloudinary Error: ' . ($result['error']['message'] ?? 'Unknown error')]);
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['gambar' => 'Koneksi Cloudinary Gagal: ' . $e->getMessage()]);
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
     * Memperbarui data tipe (Gaya HTTP Multipart untuk Vercel)
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
                            'contents' => 'kedamark',
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
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['gambar' => 'Cloudinary Error: ' . $e->getMessage()]);
            }
        }

        $tipe->update($validated);

        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Diperbarui!');
    }

    /**
     * Menghapus data tipe
     */
    public function destroy(Tipe $tipe) {
        $tipe->delete();
        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Dihapus!');
    }
}