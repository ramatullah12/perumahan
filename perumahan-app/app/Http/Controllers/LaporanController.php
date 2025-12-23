<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// TAMBAHKAN IMPORT PDF DI BAWAH INI
use Barryvdh\DomPDF\Facade\Pdf; 

class LaporanController extends Controller
{
    public function index()
    {
        // 1. Statistik Ringkasan (Cards)
        $totalRevenue = Booking::where('status', 'disetujui')->sum(DB::raw('100000000')); 
        $projectedRevenue = Booking::where('status', 'pending')->count() * 100000000;
        $unitTerjual = Unit::where('status', 'Terjual')->count();
        $customerAktif = User::where('role', 'customer')->count();

        // 2. Data Grafik Penjualan per Proyek
        $projects = Project::withCount([
            'units as booked_count' => function($q) { $q->where('status', 'Dibooking'); },
            'units as sold_count' => function($q) { $q->where('status', 'Terjual'); },
            'units as total_count' 
        ])->get();

        // 3. Data Chart Status Unit
        $statusStats = [
            'tersedia' => Unit::where('status', 'Tersedia')->count(),
            'booked'   => Unit::where('status', 'Dibooking')->count(),
            'terjual'  => Unit::where('status', 'Terjual')->count(),
        ];

        return view('laporan.admin.index', compact(
            'totalRevenue', 
            'projectedRevenue', 
            'unitTerjual', 
            'customerAktif', 
            'projects', 
            'statusStats'
        ));
    }

    /**
     * FUNGSI EXPORT PDF (Tambahkan ini agar error hilang)
     */
    public function exportPDF()
    {
        // Ambil data untuk laporan PDF
        $totalRevenue = Booking::where('status', 'disetujui')->sum(DB::raw('100000000'));
        $unitTerjual = Unit::where('status', 'Terjual')->count();
        $projects = Project::withCount([
            'units as sold_count' => function($q) { $q->where('status', 'Terjual'); }
        ])->get();

        // Render view ke PDF
        // Pastikan Anda sudah membuat file resources/views/laporan/admin/pdf.blade.php
        $pdf = Pdf::loadView('laporan.admin.pdf', compact('totalRevenue', 'unitTerjual', 'projects'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Penjualan-' . date('Y-m-d') . '.pdf');
    }
}