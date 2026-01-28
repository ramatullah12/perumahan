<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Untuk mencatat error jika terjadi kegagalan API

class ProjectController extends Controller
{
    /**
     * Menampilkan daftar proyek.
     */
    public function index()
    {
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
     * SIMPAN PROYEK BARU
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'lokasi'      => 'required|string',
            'total_unit'  => 'required|integer|min:1',
            'deskripsi'   => 'required|string',
            'gambar'      => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');
                
                // Kirim ke API Cloudinary
                $response = Http::asMultipart()->post(
                    'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                    [
                        ['name' => 'file', 'contents' => fopen($file->getRealPath(), 'r'), 'filename' => $file->getClientOriginalName()],
                        ['name' => 'upload_preset', 'contents' => env('CLOUDINARY_UPLOAD_PRESET', 'kedamark')],
                        ['name' => 'folder', 'contents' => 'projects'],
                    ]
                );

                $result = $response->json();

                if (isset($result['secure_url'])) {
                    $input['gambar'] = $result['secure_url']; // URL lengkap (https://...)
                } else {
                    return back()->withInput()->withErrors(['gambar' => 'Cloudinary Upload Gagal: ' . ($result['error']['message'] ?? 'Check Cloud Name/Preset')]);
                }
            } catch (\Exception $e) {
                Log::error('Upload Error: ' . $e->getMessage());
                return back()->withInput()->withErrors(['gambar' => 'Koneksi ke Cloudinary bermasalah.']);
            }
        }

        // Set Default Values
        $input['status']   = 'Sedang Berjalan';
        $input['tersedia'] = $request->total_unit;
        $input['booked']   = 0;
        $input['terjual']  = 0;

        Project::create($input);

        return redirect()->route('admin.project.index')->with('success', 'Proyek Berhasil Dipublikasikan!');
    }

    public function edit(Project $project)
    {
        return view('project.admin.edit', compact('project'));
    }

    /**
     * UPDATE PROYEK
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

        // Logika Stok: Jika total unit diubah, pastikan tidak kurang dari yang sudah laku
        if ($request->total_unit != $project->total_unit) {
            $tersediaBaru = $request->total_unit - ($project->booked + $project->terjual);
            
            if ($tersediaBaru < 0) {
                return back()->withErrors(['total_unit' => 'Total unit baru tidak mencukupi unit yang sudah dibooking/terjual!']);
            }
            $data['tersedia'] = $tersediaBaru;
        }

        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');
                $response = Http::asMultipart()->post(
                    'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                    [
                        ['name' => 'file', 'contents' => fopen($file->getRealPath(), 'r'), 'filename' => $file->getClientOriginalName()],
                        ['name' => 'upload_preset', 'contents' => env('CLOUDINARY_UPLOAD_PRESET', 'kedamark')],
                        ['name' => 'folder', 'contents' => 'projects'],
                    ]
                );

                $result = $response->json();
                if (isset($result['secure_url'])) {
                    $data['gambar'] = $result['secure_url'];
                }
            } catch (\Exception $e) {
                return back()->withErrors(['gambar' => 'Gagal mengupdate gambar.']);
            }
        }

        $project->update($data);

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        // Cek apakah ada unit yang terjual
        if ($project->units()->where('status', 'Terjual')->exists()) {
            return redirect()->back()->with('error', 'Proyek ini memiliki unit yang sudah terjual dan tidak bisa dihapus!');
        }

        $project->delete();
        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil dihapus!');
    }
}