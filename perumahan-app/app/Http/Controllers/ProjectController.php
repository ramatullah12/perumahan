<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'gambar'      => 'required|image|mimes:jpg,png,jpeg,webp|max:2048', // Max 2MB sudah cukup ideal
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

    public function edit(Project $project)
    {
        return view('project.admin.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'lokasi'      => 'required|string',
            'total_unit'  => 'required|integer|min:1', // Tambahkan min:1
            'deskripsi'   => 'required|string',
            'gambar'      => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048', // Ubah ke nullable
        ]);

        $data = $request->only(['nama_proyek', 'lokasi', 'total_unit', 'deskripsi']);

        // LOGIKA PENTING: Jika total_unit berubah, Anda harus menyesuaikan kolom 'tersedia'
        // Namun sederhananya, di sini kita hanya mengupdate info dasar. 
        // Jika ingin otomatis, Anda bisa menambahkan logika matematika di sini.

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($project->gambar) {
                Storage::disk('public')->delete($project->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        // Gunakan proteksi: Jangan hapus proyek jika sudah ada unit yang terjual
        if ($project->terjual > 0) {
            return redirect()->back()->with('error', 'Proyek tidak bisa dihapus karena sudah ada unit yang terjual!');
        }

        if ($project->gambar) {
            Storage::disk('public')->delete($project->gambar);
        }

        $project->delete();

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil dihapus!');
    }
}