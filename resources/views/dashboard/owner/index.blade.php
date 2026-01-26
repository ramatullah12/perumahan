@extends('layout.owner') 

@section('content')
<style>
    :root {
        --primary: #4361ee;
        --success: #2ec4b6;
        --warning: #ff9f1c;
        --danger: #e71d36;
        --dark: #1e293b;
    }

    /* Professional Card Styling */
    .stat-card-premium {
        border: none;
        border-radius: 20px;
        padding: 24px;
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        height: 100%;
    }

    .stat-card-premium:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .icon-box {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .card-gradient-blue   { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
    .card-gradient-green  { background: linear-gradient(135deg, #2ec4b6, #0891b2); }
    .card-gradient-orange { background: linear-gradient(135deg, #f79256, #f97316); }
    .card-gradient-purple { background: linear-gradient(135deg, #7209b7, #4cc9f0); }

    /* Table & Content Styling */
    .glass-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        color: #64748b;
        padding: 15px;
        border: none;
    }

    .table tbody td {
        padding: 18px 15px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .progress-custom {
        height: 6px;
        background-color: #f1f5f9;
        border-radius: 10px;
        margin-bottom: 0;
    }

    .badge-soft-success {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #d1fae5;
        padding: 6px 12px;
    }

    .date-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="h3 fw-bold text-dark mb-1">Executive Summary</h2>
            <p class="text-muted small mb-0">Laporan analitik performa Kedaton Sejahtera Abadi</p>
        </div>
        <div>
            <span class="date-badge fw-medium">
                <i class="far fa-calendar-alt me-2 text-primary"></i> {{ date('d M Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-premium card-gradient-blue">
                <div class="icon-box">
                    <i class="fas fa-project-diagram fs-4"></i>
                </div>
                <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Total Proyek</p>
                <h2 class="fw-bold mb-0">{{ number_format($totalProyek) }}</h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-premium card-gradient-green">
                <div class="icon-box">
                    <i class="fas fa-home fs-4"></i>
                </div>
                <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Unit Terjual</p>
                <h2 class="fw-bold mb-0">{{ number_format($totalTerjual) }}</h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-premium card-gradient-orange">
                <div class="icon-box">
                    <i class="fas fa-warehouse fs-4"></i>
                </div>
                <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Stok Tersedia</p>
                <h2 class="fw-bold mb-0">{{ number_format($totalUnit - $totalTerjual) }}</h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-premium card-gradient-purple">
                <div class="icon-box">
                    <i class="fas fa-user-friends fs-4"></i>
                </div>
                <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Basis Pelanggan</p>
                <h2 class="fw-bold mb-0">{{ number_format(\App\Models\User::where('role', 'customer')->count()) }}</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="glass-card p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Analisis Penjualan Proyek
                    </h5>
                    <button class="btn btn-light btn-sm rounded-pill px-4 border shadow-sm fw-semibold">
                        <i class="fas fa-file-export me-2 text-primary"></i>Export Data
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-0">Nama Proyek</th>
                                <th class="text-center" style="width: 300px;">Absorpsi Pasar</th>
                                <th class="text-center">Volume</th>
                                <th class="text-end pe-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                            <tr>
                                <td class="ps-0">
                                    <div class="fw-bold text-dark mb-1">{{ $project->nama_proyek }}</div>
                                    <span class="text-muted small">Kedaton Group Property</span>
                                </td>
                                <td>
                                    @php 
                                        $percent = $totalUnit > 0 ? ($project->sold_count / $totalUnit) * 100 : 0; 
                                        $colorClass = $percent > 70 ? 'bg-success' : ($percent > 40 ? 'bg-primary' : 'bg-warning');
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-custom flex-grow-1 me-3">
                                            <div class="progress-bar {{ $colorClass }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $percent }}%" 
                                                 aria-valuenow="{{ $percent }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="small fw-bold text-dark">{{ number_format($percent, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark">{{ number_format($project->sold_count) }}</span> 
                                    <span class="text-muted small ms-1">Unit</span>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="badge rounded-pill badge-soft-success fw-bold text-uppercase" style="font-size: 10px;">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">
                                    <i class="fas fa-folder-open d-block fs-2 mb-3"></i>
                                    Belum ada data proyek tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection