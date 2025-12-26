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
        // 1. Statistik Ringkasan (Akurat berdasarkan Unit yang benar-benar terjual)
        // Konsistensi: Mengambil langsung dari tabel units untuk total riil
        $totalRevenue = Unit::where('status', 'Terjual')->sum('harga');

        // Revenue Proyeksi (Booking disetujui tapi status unit mungkin belum 'Terjual')
        $projectedRevenue = Booking::where('bookings.status', 'disetujui')
            ->join('units', 'bookings.unit_id', '=', 'units.id')
            ->sum('units.harga');

        $unitTerjual = Unit::where('status', 'Terjual')->count();
        $customerAktif = User::where('role', 'customer')->count();

        // 2. Data Tabel & Grafik (Sinkron dengan total di atas)
        $projects = Project::with(['units' => function($q) {
                $q->where('status', 'Terjual');
            }])
            ->withCount([
                'units as booked_count' => function($q) { $q->where('status', 'Dibooking'); },
                'units as sold_count' => function($q) { $q->where('status', 'Terjual'); },
                'units as total_count' 
            ])->get();

        // Menambahkan total revenue per proyek secara dinamis agar tabel sinkron
        $projects->each(function($project) {
            $project->revenue_proyek = $project->units->sum('harga');
        });

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
     * SOLUSI: Menambahkan kembali method yang hilang agar tidak error 500
     */
    public function analisisOwner()
    {
        // Total Omzet Riil (Unit Terjual)
        $totalPendapatan = Unit::where('status', 'Terjual')->sum('harga');

        $stats = [
            'total_units' => Unit::count(),
            'terjual'     => Unit::where('status', 'Terjual')->count(),
            'tersedia'    => Unit::where('status', 'Tersedia')->count(),
            'booked'      => Unit::where('status', 'Dibooking')->count(),
        ];

        // Performa Penjualan per Proyek
        $proyekPerforma = Project::withCount(['units as total_unit', 'units as terjual' => function($q) {
            $q->where('status', 'Terjual');
        }])->get();

        return view('laporan.owner.analisis', compact('totalPendapatan', 'stats', 'proyekPerforma'));
    }

    /**
     * EXPORT PDF - Harus Sinkron dengan Dashboard
     */
    public function exportPDF()
    {
        // Gunakan sum dari tabel units agar data PDF identik dengan dashboard
        $totalRevenue = Unit::where('status', 'Terjual')->sum('harga');
        $unitTerjual = Unit::where('status', 'Terjual')->count();
        
        $projects = Project::with(['units' => function($q) {
                $q->where('status', 'Terjual');
            }])
            ->withCount([
                'units as sold_count' => function($q) { $q->where('status', 'Terjual'); }
            ])->get();

        $projects->each(function($project) {
            $project->revenue_proyek = $project->units->sum('harga');
        });

        $pdf = Pdf::loadView('laporan.admin.pdf', compact('totalRevenue', 'unitTerjual', 'projects'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Penjualan-' . date('Y-m-d') . '.pdf');
    }
}