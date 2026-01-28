<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// Library Cloudinary wajib ada untuk Vercel
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    /**
     * SIMPAN PROYEK BARU (FIXED FOR VERCEL)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'lokasi'      => 'required|string',
            'total_unit'  => 'required|integer|min:1',
            'deskripsi'   => 'required|string',
            'gambar'      => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        // Upload ke Cloudinary menggunakan preset 'kedamark'
        $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
            'upload_preset' => 'kedamark',
            'folder' => 'projects'
        ])->getSecurePath();

        Project::create([
            'nama_proyek' => $request->nama_proyek,
            'lokasi'      => $request->lokasi,
            'deskripsi'   => $request->deskripsi,
            'total_unit'  => $request->total_unit, 
            'gambar'      => $uploadedFileUrl, // Simpan URL HTTPS permanen
            'status'      => 'Sedang Berjalan',
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

    /**
     * UPDATE PROYEK (FIXED FOR VERCEL)
     */
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

        // Sinkronisasi Stok
        if ($request->total_unit != $project->total_unit) {
            $data['tersedia'] = $request->total_unit - ($project->booked + $project->terjual);
        }

        if ($request->hasFile('gambar')) {
            // Upload baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'upload_preset' => 'kedamark',
                'folder' => 'projects'
            ])->getSecurePath();
            
            $data['gambar'] = $uploadedFileUrl;
        }

        $project->update($data);

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        // Proteksi: Mencegah penghapusan jika sudah ada unit yang terjual secara riil
        $sudahTerjual = $project->units()->where('status', 'Terjual')->count();

        if ($sudahTerjual > 0) {
            return redirect()->back()->with('error', 'Proyek tidak bisa dihapus karena sudah ada unit yang terjual!');
        }

        // Untuk Cloudinary, file tidak perlu dihapus manual dari storage lokal
        $project->delete();

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil dihapus!');
    }
}