<?php

namespace App\Http\Controllers;

use App\Models\Tipe;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TipeController extends Controller
{
    /**
     * Menampilkan daftar tipe adsa
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
            $validated['gambar'] = $request->file('gambar')->store('tipes', 'public');
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
            // Hapus gambar lama jika ada
            if ($tipe->gambar) {
                Storage::disk('public')->delete($tipe->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('tipes', 'public');
        }

        $tipe->update($validated);

        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Diperbarui!');
    }

    /**
     * Menghapus data tipe
     */
    public function destroy(Tipe $tipe) {
        if ($tipe->gambar) {
            Storage::disk('public')->delete($tipe->gambar);
        }
        
        $tipe->delete();

        return redirect()->route('admin.tipe.index')->with('success', 'Tipe Berhasil Dihapus!');
    }
}