<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // WAJIB ditambahkan untuk hapus file

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        return view('project.admin.index', compact('projects'));
    }

    public function create()
    {
        return view('project.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'lokasi'      => 'required|string',
            'total_unit'  => 'required|integer|min:1',
            'deskripsi'   => 'required|string',
            'gambar'      => 'required|image|mimes:jpg,png,jpeg,webp,svg,bmp|max:5120',
        ]);

        $path = $request->file('gambar')->store('projects', 'public');

        Project::create([
            'nama_proyek' => $request->nama_proyek,
            'lokasi'      => $request->lokasi,
            'deskripsi'   => $request->deskripsi,
            'total_unit'  => $request->total_unit,
            'tersedia'    => $request->total_unit,
            'booked'      => 0,
            'terjual'     => 0,
            'gambar'      => $path,
            'status'      => 'Sedang Berjalan',
        ]);

        return redirect()->route('admin.project.index')->with('success', 'Proyek Berhasil Dipublikasikan!');
    }

    /**
     * Menampilkan halaman edit proyek
     */
    public function edit(Project $project)
    {
        return view('project.admin.edit', compact('project'));
    }

    /**
     * Memperbarui data proyek di database
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'lokasi'      => 'required|string',
            'total_unit'  => 'required|integer',
            'deskripsi'   => 'required|string',
            'gambar'      => 'required|image|mimes:jpg,png,jpeg,webp,svg,bmp|max:5120',
        ]);

        $data = $request->only(['nama_proyek', 'lokasi', 'total_unit', 'deskripsi']);

        // Cek jika ada unggahan gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari storage agar tidak penuh
            if ($project->gambar) {
                Storage::disk('public')->delete($project->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Menghapus proyek dan file gambarnya (Fungsi yang tadi Error)
     */
    public function destroy(Project $project)
    {
        // 1. Hapus file gambar secara fisik dari folder storage
        if ($project->gambar) {
            Storage::disk('public')->delete($project->gambar);
        }

        // 2. Hapus data dari database
        $project->delete();

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil dihapus secara permanen!');
    }
}