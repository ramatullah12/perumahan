@extends('layout.customer') {{-- Pastikan ini mengarah ke file di folder layout --}}

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1">Notifikasi Baru</h6>
                        <h2 class="fw-bold mb-0">{{ $unreadNotificationsCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <a href="{{ route('customer.proyek.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small fw-bold mb-1">Jelajahi</h6>
                            <h2 class="fw-bold text-dark mb-0">Cari Proyek</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection