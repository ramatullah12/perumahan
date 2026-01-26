<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\tipe; // Menggunakan 'tipe' sesuai nama file tipe.php Anda
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Menampilkan Halaman Utama (Welcome)
     */
    public function index()
    {
        // Statistik untuk stat cards di halaman Welcome
        $totalProyek = Project::count();
        $unitTersedia = Unit::where('status', 'Tersedia')->count();
        $lokasiUtama = Project::select('lokasi')->first()->lokasi ?? 'Palembang';

        // Daftar proyek terbaru
        $projects = Project::latest()->get();

        return view('welcome', compact('totalProyek', 'unitTersedia', 'projects', 'lokasiUtama'));
    }

    /**
     * Menampilkan Halaman Detail Proyek Perumahan
     */
    public function show($id)
    {
        // Mengambil data proyek beserta relasi tipe dan unitnya
        $project = Project::with(['tipes', 'units.tipe'])->findOrFail($id);

        // Menghitung statistik unit untuk dashboard ketersediaan
        $stats = [
            'total'     => $project->units->count(),
            'tersedia'  => $project->units->where('status', 'Tersedia')->count(),
            'dibooking' => $project->units->where('status', 'Dibooking')->count(),
            'terjual'   => $project->units->where('status', 'Terjual')->count(),
        ];

        /**
         * PERBAIKAN PENTING:
         * Karena file Anda berada di resources/views/detail.blade.php,
         * maka pemanggilan view cukup 'detail', bukan 'projects.detail'.
         */
        return view('detail', compact('project', 'stats'));
    }
}