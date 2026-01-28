<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Wajib untuk upload ke API Cloudinary
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * SIMPAN PROYEK BARU (Menggunakan API HTTP Multipart)
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
                
                // Request manual ke Cloudinary API (Tanpa SDK Library)
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
                            'contents' => 'projects',
                        ],
                    ]
                );

                $result = $response->json();

                if (isset($result['secure_url'])) {
                    $input['gambar'] = $result['secure_url'];
                } else {
                    return back()->withErrors(['gambar' => 'Cloudinary Upload Error: ' . ($result['error']['message'] ?? 'Unknown error')]);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['gambar' => 'Cloudinary Connection Error: ' . $e->getMessage()]);
            }
        }

        // Mapping data tambahan
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
     * UPDATE PROYEK (Menggunakan API HTTP Multipart)
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

        // Sinkronisasi Stok jika total unit berubah
        if ($request->total_unit != $project->total_unit) {
            $data['tersedia'] = $request->total_unit - ($project->booked + $project->terjual);
        }

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
                            'contents' => 'projects',
                        ],
                    ]
                );

                $result = $response->json();
                if (isset($result['secure_url'])) {
                    $data['gambar'] = $result['secure_url'];
                }
            } catch (\Exception $e) {
                return back()->withErrors(['gambar' => 'Cloudinary Error: ' . $e->getMessage()]);
            }
        }

        $project->update($data);

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $sudahTerjual = $project->units()->where('status', 'Terjual')->count();

        if ($sudahTerjual > 0) {
            return redirect()->back()->with('error', 'Proyek tidak bisa dihapus karena sudah ada unit yang terjual!');
        }

        $project->delete();

        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil dihapus!');
    }
}