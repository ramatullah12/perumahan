@extends('layout.owner')

@section('content')
<div class="p-4" style="background: #f8fafc; min-height: 100vh;">
    {{-- HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Analisis Penjualan & Keuangan</h3>
            <p class="text-muted small">Ringkasan performa bisnis dan distribusi stok unit secara real-time.</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-white border shadow-sm px-3">
                <i class="fas fa-print me-2"></i>Cetak Laporan
            </button>
        </div>
    </div>

    {{-- KARTU STATISTIK UTAMA --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 bg-primary text-white position-relative">
                    <div class="position-relative z-1">
                        <h6 class="text-uppercase fw-bold small opacity-75 mb-2">Total Omzet Penjualan</h6>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                        <div class="mt-3 small">
                            <span class="badge bg-white bg-opacity-25 py-2 px-3 rounded-pill">
                                <i class="fas fa-chart-line me-1"></i> Akumulasi Unit Terjual
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-wallet position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-25" style="font-size: 150px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon bg-success bg-opacity-10 text-success p-3 rounded-3">
                            <i class="fas fa-home fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Unit Terjual</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['terjual'] }} <span class="fs-6 fw-normal text-muted">Unit</span></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                            <i class="fas fa-key fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Unit Booked</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['booked'] }} <span class="fs-6 fw-normal text-muted">Unit</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- TABEL PERFORMA PROYEK --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0 text-dark">Performa Penjualan Per Proyek</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small fw-bold text-uppercase">
                                <th class="ps-4 py-3">Nama Proyek</th>
                                <th>Efektivitas Penjualan</th>
                                <th class="text-center">Unit Terjual / Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyekPerforma as $p)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="fw-bold text-dark d-block">{{ $p->nama_proyek }}</span>
                                    <span class="text-muted extra-small">Lokasi Strategis</span>
                                </td>
                                <td>
                                    @php $persen = $p->total_unit > 0 ? ($p->terjual / $p->total_unit) * 100 : 0; @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px; border-radius: 10px; background: #e2e8f0;">
                                            <div class="progress-bar bg-primary" style="width: {{ $persen }}%; border-radius: 10px;"></div>
                                        </div>
                                        <span class="ms-3 fw-bold text-primary small">{{ round($persen) }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">
                                        {{ $p->terjual }} <span class="text-muted fw-normal">/ {{ $p->total_unit }}</span>
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- GRAFIK DISTRIBUSI --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0 text-dark text-center">Distribusi Unit</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center pt-0">
                    <div style="position: relative; width: 100%; max-width: 280px;">
                        <canvas id="stockChart"></canvas>
                    </div>
                    <div class="mt-4 w-100">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><i class="fas fa-circle text-success me-2"></i>Tersedia</span>
                            <span class="fw-bold">{{ $stats['tersedia'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><i class="fas fa-circle text-warning me-2"></i>Booked</span>
                            <span class="fw-bold">{{ $stats['booked'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-circle text-primary me-2"></i>Terjual</span>
                            <span class="fw-bold">{{ $stats['terjual'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.75rem; }
    .bg-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stockChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Booked', 'Terjual'],
            datasets: [{
                data: [{{ $stats['tersedia'] }}, {{ $stats['booked'] }}, {{ $stats['terjual'] }}],
                backgroundColor: ['#198754', '#ffc107', '#0d6efd'],
                hoverOffset: 15,
                borderWidth: 0
            }]
        },
        options: {
            cutout: '80%',
            plugins: {
                legend: { display: false }
            },
            maintainAspectRatio: true,
        }
    });
</script>
@endsection