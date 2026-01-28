@extends('layout.owner')

@section('content')
<div class="p-4" style="background: #f8fafc; min-height: 100vh;">
    {{-- HEADER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Monitoring Progres Konstruksi</h3>
            <p class="text-muted small">Laporan real-time perkembangan fisik unit proyek di seluruh lokasi.</p>
        </div>
        <div class="text-md-end">
            <span class="badge bg-white text-primary border shadow-sm px-4 py-2 rounded-pill fw-bold">
                <i class="fas fa-calendar-check me-2"></i>{{ date('d F Y') }}
            </span>
        </div>
    </div>

    {{-- STATS SUMMARY --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted extra-small fw-bold text-uppercase mb-1 tracking-wider">Unit Terjual</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $units->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success p-3 rounded-4 me-3">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted extra-small fw-bold text-uppercase mb-1 tracking-wider">Rata-rata Fisik</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ round($units->avg('progres_pembangunan')) }}%</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden h-100">
                <div class="card-body p-4 position-relative z-1">
                    <h6 class="text-white text-opacity-75 extra-small fw-bold text-uppercase mb-2">Insight Serah Terima</h6>
                    <p class="mb-0 fw-medium">
                        <i class="fas fa-check-double text-success me-2"></i>
                        {{ $units->where('progres_pembangunan', 100)->count() }} unit telah mencapai progres 100% dan masuk tahap pembersihan (Final Cleaning).
                    </p>
                </div>
                <i class="fas fa-building position-absolute end-0 bottom-0 mb-n3 me-n3 opacity-25" style="font-size: 100px; color: #3b82f6;"></i>
            </div>
        </div>
    </div>

    {{-- MONITORING TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="fw-bold mb-0 text-dark">Daftar Pembangunan Unit</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-light border-0"><i class="fas fa-filter text-muted small"></i></span>
                <input type="text" id="tableSearch" class="form-control bg-light border-0 small" placeholder="Cari unit atau proyek...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="progressTable">
                <thead class="bg-light">
                    <tr class="text-muted extra-small fw-bold text-uppercase tracking-wider">
                        <th class="ps-4 py-3 border-0">Proyek & Unit</th>
                        <th class="border-0">Tipe</th>
                        <th class="border-0" width="280">Visualisasi Progress</th>
                        <th class="border-0">Tahap Terakhir</th>
                        <th class="pe-4 border-0">Audit Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        @php
                            $prog = $unit->progres_pembangunan ?? 0;
                        @endphp
                        <tr class="searchable-row">
                            <td class="ps-4 py-4">
                                <span class="fw-bold text-dark d-block mb-1">{{ $unit->project->nama_proyek ?? 'N/A' }}</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary extra-small px-2 py-1">
                                    Blok {{ $unit->block }} — No. {{ $unit->no_unit }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted fw-semibold small">
                                    <i class="fas fa-cube me-1 text-primary opacity-50"></i> {{ $unit->tipe->nama_tipe ?? 'Tipe' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 shadow-sm" style="height: 8px; border-radius: 20px; background: #e9ecef;">
                                        <div class="progress-bar gradient-progress" role="progressbar" 
                                             style="width: {{ $prog }}%; border-radius: 20px;" 
                                             aria-valuenow="{{ $prog }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="ms-3 fw-bold {{ $prog == 100 ? 'text-success' : 'text-primary' }} small">{{ $prog }}%</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small mb-1">
                                    {{ $unit->latestProgres->tahap ?? 'Persiapan' }}
                                </div>
                                <div class="text-muted extra-small italic text-truncate" style="max-width: 200px;">
                                    {{ $unit->latestProgres->keterangan ?? 'Menunggu update lapangan' }}
                                </div>
                            </td>
                            <td class="pe-4 text-nowrap">
                                <div class="text-muted extra-small d-flex align-items-center">
                                    <span class="update-indicator {{ $unit->latestProgres ? 'bg-success' : 'bg-warning' }} me-2"></span>
                                    {{ $unit->latestProgres ? $unit->latestProgres->created_at->diffForHumans() : 'No Data' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-folder-open text-muted opacity-25 fa-4x mb-3"></i>
                                    <p class="text-muted italic fw-medium">Tidak ditemukan unit dalam antrian pembangunan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Simple filter search
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('.searchable-row');
        
        rows.forEach(row => {
            row.style.display = (row.innerText.toLowerCase().indexOf(value) > -1) ? "" : "none";
        });
    });
</script>

<style>
    .extra-small { font-size: 0.7rem; }
    .update-indicator { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
    .gradient-progress {
        background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%) !important;
    }
    .card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .table thead th { border: none; background: #f8fafc; color: #64748b; }
    .table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.2s; }
    .table tbody tr:hover { background-color: #fbfcfe; }
    .italic { font-style: italic; }
    .tracking-wider { letter-spacing: 0.05em; }
</style>
@endsection