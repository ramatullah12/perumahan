<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 

class LaporanController extends Controller
{
    /**
     * ADMIN - Halaman Laporan Utama
     */
    public function index()
    {
        // 1. Statistik Ringkasan (Cards)
        // Menggunakan harga asli dari unit yang disetujui (bukan hardcode angka)
        $totalRevenue = Booking::where('status', 'disetujui')
            ->join('units', 'bookings.unit_id', '=', 'units.id')
            ->sum('units.harga');

        $projectedRevenue = Booking::where('status', 'pending')
            ->join('units', 'bookings.unit_id', '=', 'units.id')
            ->sum('units.harga');

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
     * OWNER - Fitur Analisis Penjualan Khusus Owner
     * PERBAIKAN: Method ini wajib ada agar rute owner.analisis.index tidak error
     */
    public function analisisOwner()
    {
        // Total Omzet Riil (Unit Terjual)
        $totalPendapatan = Unit::where('status', 'Terjual')->sum('harga');

        // Statistik Stok untuk Chart
        $stats = [
            'total_units' => Unit::count(),
            'terjual'     => Unit::where('status', 'Terjual')->count(),
            'tersedia'    => Unit::where('status', 'Tersedia')->count(),
            'booked'      => Unit::where('status', 'Dibooking')->count(),
        ];

        // Performa Penjualan per Proyek (Volume)
        $proyekPerforma = Project::withCount(['units as total_unit', 'units as terjual' => function($q) {
            $q->where('status', 'Terjual');
        }])->get();

        return view('laporan.owner.analisis', compact('totalPendapatan', 'stats', 'proyekPerforma'));
    }

    /**
     * EXPORT PDF - Mendukung Laporan Admin & Owner
     */
    public function exportPDF()
    {
        $totalRevenue = Booking::where('status', 'disetujui')
            ->join('units', 'bookings.unit_id', '=', 'units.id')
            ->sum('units.harga');
            
        $unitTerjual = Unit::where('status', 'Terjual')->count();
        
        $projects = Project::withCount([
            'units as sold_count' => function($q) { $q->where('status', 'Terjual'); }
        ])->get();

        $pdf = Pdf::loadView('laporan.admin.pdf', compact('totalRevenue', 'unitTerjual', 'projects'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Penjualan-' . date('Y-m-d') . '.pdf');
    }
}