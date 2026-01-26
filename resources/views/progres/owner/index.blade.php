@extends('layout.owner')

@section('content')
<div class="p-4" style="background: #f8fafc; min-height: 100vh;">
    {{-- HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Monitoring Progres Konstruksi</h3>
            <p class="text-muted small">Pantau perkembangan fisik seluruh unit pembangunan secara real-time.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-white text-primary border shadow-sm px-3 py-2 rounded-pill">
                <i class="fas fa-calendar-alt me-2"></i>{{ date('d F Y') }}
            </span>
        </div>
    </div>

    {{-- STATS SUMMARY --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted extra-small fw-bold text-uppercase mb-1">Unit Aktif</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $units->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning p-3 rounded-3 me-3">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted extra-small fw-bold text-uppercase mb-1">Rata-rata Fisik</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ round($units->avg('progres')) }}%</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <h6 class="text-white text-opacity-75 extra-small fw-bold text-uppercase mb-2">Status Terakhir Lapangan</h6>
                    <p class="mb-0 fw-medium">
                        <i class="fas fa-info-circle me-2"></i>
                        Terdapat {{ $units->where('progres', 100)->count() }} unit telah mencapai progres 100% dan siap serah terima.
                    </p>
                </div>
                <i class="fas fa-hard-hat position-absolute end-0 bottom-0 mb-n3 me-n3 opacity-25" style="font-size: 100px;"></i>
            </div>
        </div>
    </div>

    {{-- MONITORING TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Daftar Pembangunan Unit</h5>
            <div class="input-group style="width: 250px;">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control bg-light border-0 small" placeholder="Cari unit atau proyek...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted extra-small fw-bold text-uppercase">
                        <th class="ps-4 py-3">Proyek & Identitas</th>
                        <th>Tipe Unit</th>
                        <th width="300">Visualisasi Progress</th>
                        <th>Status Teknis</th>
                        <th class="pe-4">Sinkronisasi Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td class="ps-4 py-4">
                                <span class="fw-bold text-dark d-block mb-1">{{ $unit->project->nama_proyek }}</span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary extra-small">
                                    Blok {{ $unit->block }} — No. {{ $unit->no_unit }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-outline-primary border border-primary text-primary px-3 py-2 rounded-pill extra-small">
                                    <i class="fas fa-tag me-1"></i> {{ $unit->tipe->nama_tipe }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 shadow-sm" style="height: 10px; border-radius: 20px; background: #e9ecef;">
                                        <div class="progress-bar bg-primary gradient-progress" role="progressbar" 
                                             style="width: {{ $unit->progres }}%; border-radius: 20px;" 
                                             aria-valuenow="{{ $unit->progres }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="ms-3 fw-bold text-primary">{{ $unit->progres }}%</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small mb-1">
                                    {{ $unit->latestProgres->tahap ?? 'Tahap Persiapan' }}
                                </div>
                                <div class="text-muted extra-small italic">
                                    {{ Str::limit($unit->latestProgres->keterangan ?? 'Menunggu update laporan lapangan', 45) }}
                                </div>
                            </td>
                            <td class="pe-4">
                                <div class="text-muted extra-small d-flex align-items-center">
                                    <div class="update-indicator {{ $unit->latestProgres ? 'bg-success' : 'bg-warning' }} me-2"></div>
                                    {{ $unit->latestProgres ? $unit->latestProgres->created_at->diffForHumans() : 'Belum Terkoneksi' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/box.svg" alt="empty" style="width: 120px;" class="mb-3">
                                <p class="text-muted italic">Tidak ditemukan unit dalam antrian pembangunan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.75rem; }
    .fw-black { font-weight: 900; }
    .update-indicator { width: 8px; height: 8px; border-radius: 50%; }
    .gradient-progress {
        background: linear-gradient(90deg, #0d6efd 0%, #00d4ff 100%) !important;
    }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
    .table thead th { border: none; }
    .table tbody td { border-bottom: 1px solid #f1f5f9; }
</style>
@endsection