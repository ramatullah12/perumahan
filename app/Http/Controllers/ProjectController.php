<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Menampilkan daftar proyek dengan statistik unit otomatis.
     */
    public function index()
    {
        // Menggunakan withCount agar data statistik dihitung langsung dari relasi Unit
        $projects = Project::withCount([
            'units as tersedia_count' => function ($query) {
                $query->where('status', 'Tersedia');
            },
            'units as booked_count' => function ($query) {
                $query->where('status', 'Dibooking');
            },
            'units as terjual_count' => function ($query) {
                $query->where('status', 'Terjual');
            }
        ])->latest()->get();

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
            'gambar'      => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        $request->file('foto')->storePublicly('projects', 'public');

        /**
         * SOLUSI ERROR 1364: Mengirimkan nilai awal ke kolom yang tidak memiliki default value
         */
        Project::create([
            'nama_proyek' => $request->nama_proyek,
            'lokasi'      => $request->lokasi,
            'deskripsi'   => $request->deskripsi,
            'total_unit'  => $request->total_unit, 
            'gambar'      => $path,
            'status'      => 'Sedang Berjalan',
            // Inisialisasi stok awal
            'tersedia'    => $request->total_unit, 
            'booked'      => 0,
            'terjual'     => 0,
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
            'total_unit'  => 'required|integer|min:1',
            'deskripsi'   => 'required|string',
            'gambar'      => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        $data = $request->only(['nama_proyek', 'lokasi', 'total_unit', 'deskripsi']);

        /**
         * Sinkronisasi Stok: Jika total_unit (kapasitas) diubah, 
         * maka jumlah 'tersedia' harus dihitung ulang berdasarkan unit yang sudah laku
         */
        if ($request->total_unit != $project->total_unit) {
            $data['tersedia'] = $request->total_unit - ($project->booked + $project->terjual);
        }

        if ($request->hasFile('gambar')) {
            if ($project->gambar) {
                Storage::disk('public')->delete($project->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        /** * Proteksi: Mencegah penghapusan jika sudah ada unit yang terjual secara riil
         */
        $sudahTerjual = $project->units()->where('status', 'Terjual')->count();

        if ($sudahTerjual > 0) {
            return redirect()->back()->with('error', 'Proyek tidak bisa dihapus karena sudah ada unit yang terjual!');
        }

        if ($project->gambar) {
            Storage::disk('public')->delete($project->gambar);
        }

        $project->delete();

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil dihapus!');
    }
}