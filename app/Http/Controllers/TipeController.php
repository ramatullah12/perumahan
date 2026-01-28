<?php

namespace App\Http\Controllers;

use App\Models\Tipe;
use App\Models\Project;
use Illuminate\Http\Request;
// Library Cloudinary wajib ditambahkan
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
     * Menyimpan data tipe baru (FIXED FOR VERCEL)
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
            // Upload ke Cloudinary menggunakan preset 'kedamark'
            $uploadedFile = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'upload_preset' => 'kedamark',
                'folder' => 'tipes'
            ]);
            
            // Simpan URL HTTPS dari Cloudinary ke database
            $validated['gambar'] = $uploadedFile->getSecurePath();
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
     * Memperbarui data tipe (FIXED FOR VERCEL)
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
            // Upload gambar baru ke Cloudinary
            $uploadedFile = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'upload_preset' => 'kedamark',
                'folder' => 'tipes'
            ]);
            
            $validated['gambar'] = $uploadedFile->getSecurePath();
            
            // Catatan: Untuk Cloudinary, kita tidak perlu menghapus file lokal 
            // karena file disimpan di cloud.
        }

        $tipe->update($validated);

        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Diperbarui!');
    }

    /**
     * Menghapus data tipe
     */
    public function destroy(Tipe $tipe) {
        // Hapus record dari database
        $tipe->delete();

        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Dihapus!');
    }
}