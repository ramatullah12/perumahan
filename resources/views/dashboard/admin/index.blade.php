@extends('layout.admin')

@section('content')
<style>
    /* Dashboard Styling Profesional */
    .admin-container { padding: 2rem; background-color: #f8fafc; min-height: 100vh; }
    
    .stat-card {
        background: white; padding: 24px; border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        position: relative; overflow: hidden; height: 100%;
    }
    .stat-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    
    .stat-icon {
        font-size: 20px; width: 54px; height: 54px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 16px; margin-bottom: 20px;
    }
    
    /* Warna Branding Dashboard */
    .blue-theme   { background: #eff6ff; color: #3b82f6; }
    .green-theme  { background: #ecfdf5; color: #10b981; }
    .orange-theme { background: #fff7ed; color: #f97316; }
    .purple-theme { background: #faf5ff; color: #a855f7; }

    .view-link { 
        font-size: 13px; text-decoration: none; font-weight: 700; 
        display: inline-flex; align-items: center; gap: 6px; margin-top: 15px; 
    }
    
    .section-container {
        background: white; border-radius: 28px; padding: 32px;
        border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
</style>

<div class="admin-container">
    {{-- Header Dashboard --}}
    <div class="mb-5 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Pusat Kendali Properti</h2>
            <p class="text-muted mb-0 small">Monitor unit, proyek, dan persetujuan secara real-time.</p>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="badge bg-white text-dark border px-4 py-2.5 rounded-pill shadow-sm fw-bold">
                <i class="far fa-calendar-alt me-2 text-primary"></i> {{ date('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- 1. Statistik Utama (Horizontal Grid) --}}
    {{-- Penggunaan class 'row' sangat penting agar kartu sejajar horizontal --}}
    <div class="row g-4 mb-5">
        {{-- Total Proyek --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon blue-theme"><i class="fas fa-city"></i></div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider">Total Proyek</div>
                <div class="h3 fw-bold text-dark mb-0 mt-2">{{ $totalProyek }}</div>
                <a href="{{ route('admin.project.index') }}" class="view-link text-primary">
                    Kelola Proyek <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Unit Tersedia --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card border-bottom border-4 border-success">
                <div class="stat-icon green-theme"><i class="fas fa-home"></i></div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider">Unit Tersedia</div>
                <div class="h3 fw-bold text-success mb-0 mt-2">
                    {{ $unitTersedia }}<span class="text-muted fs-6">/{{ $totalUnit }}</span>
                </div>
                <a href="{{ route('admin.unit.index') }}" class="view-link text-success">Cek Stok Unit</a>
            </div>
        </div>

        {{-- Booking Pending --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card border-bottom border-4 border-warning">
                <div class="stat-icon orange-theme"><i class="fas fa-clock"></i></div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider">Booking Pending</div>
                <div class="h3 fw-bold text-warning mb-0 mt-2">{{ $bookingPending }}</div>
                <a href="{{ route('admin.booking.index') }}" class="view-link text-warning">Verifikasi</a>
            </div>
        </div>

        {{-- Total Customer --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card border-bottom border-4 border-purple">
                <div class="stat-icon purple-theme"><i class="fas fa-users"></i></div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider">Total Customer</div>
                <div class="h2 fw-bold mb-0 mt-2" style="color: #a855f7;">{{ $totalCustomer }}</div>
                <span class="view-link" style="color: #a855f7;">Data Terpusat</span>
            </div>
        </div>
    </div>

    {{-- 2. Grafik dan Persetujuan --}}
    <div class="row g-5">
        {{-- Grafik Penjualan --}}
        <div class="col-lg-8">
            <div class="section-container h-100 shadow-sm">
                <h6 class="fw-bold text-dark text-uppercase tracking-widest mb-4" style="font-size: 12px;">Analisis Performa Penjualan</h6>
                <div style="height: 350px;">
                    <canvas id="projectSalesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Daftar Persetujuan Tertunda --}}
        <div class="col-lg-4">
            <div class="section-container h-100 shadow-sm">
                <h6 class="fw-bold text-dark text-uppercase tracking-widest mb-4" style="font-size: 12px;">Antrean Verifikasi</h6>
                <div class="approval-list">
                    @forelse($pendingApprovals as $booking)
                        <div class="p-3 bg-light border border-warning-subtle rounded-4 mb-3 shadow-sm">
                            <div class="fw-bold text-dark small">{{ $booking->user->name }}</div>
                            <div class="text-muted extra-small mb-3">
                                {{ $booking->unit->project->nama_proyek }} — Unit {{ $booking->unit->no_unit }}
                            </div>
                            <form action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="disetujui">
                                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold py-2">
                                    Setujui Sekarang
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-muted mb-3 d-block fs-1 opacity-25"></i>
                            <p class="text-muted small mb-0">Semua booking telah diproses secara tuntas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. Script untuk Chart Analitik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('projectSalesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($projects->pluck('nama_proyek')) !!},
            datasets: [
                { 
                    label: 'Unit Terjual', 
                    data: {!! json_encode($projects->pluck('sold_count')) !!}, 
                    backgroundColor: '#3b82f6', borderRadius: 10, barThickness: 24 
                },
                { 
                    label: 'Unit Booking', 
                    data: {!! json_encode($projects->pluck('booked_count')) !!}, 
                    backgroundColor: '#f97316', borderRadius: 10, barThickness: 24 
                }
            ]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 25 } } }
        }
    });
</script>
@endsection