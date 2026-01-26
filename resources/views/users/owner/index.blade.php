@extends('layout.owner')

@section('content')
<div class="container-fluid py-4" style="background: #f8fafc; min-height: 100vh;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen Otoritas Pengguna</h2>
            <p class="text-muted small mb-0">Atur hak akses administratif staf dan manajemen lapangan secara terpusat.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white px-4 py-2 rounded-4 border shadow-sm small fw-bold text-primary">
                <i class="fas fa-shield-halved me-2"></i> Owner-Only Access
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center animate__animated animate__fadeIn">
            <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="fas fa-check small"></i>
            </div>
            <span class="fw-semibold small text-dark">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Daftar Pengguna Sistem</h5>
                    <span class="badge bg-light text-muted ms-3 border rounded-pill px-3">{{ $users->count() }} Total</span>
                </div>
                
                <div class="input-group input-group-sm" style="max-width: 300px;">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0 rounded-end-pill small" placeholder="Cari nama atau email...">
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light text-muted extra-small fw-bold text-uppercase">
                        <th class="ps-4 py-3" style="letter-spacing: 0.5px;">Identitas Pengguna</th>
                        <th style="letter-spacing: 0.5px;">Kontak & Email</th>
                        <th style="letter-spacing: 0.5px;">Level Otoritas</th>
                        <th class="text-end pe-4" style="letter-spacing: 0.5px;">Tindakan Keamanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4 py-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-box me-3 {{ $user->role === 'admin' ? 'admin-gradient' : 'user-gradient' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0 lh-1">{{ $user->name }}</div>
                                    <small class="text-muted extra-small">Internal ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-dark small fw-medium">
                                <i class="far fa-envelope me-2 text-muted"></i>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge-custom bg-primary-soft text-primary d-inline-block">
                                    <i class="fas fa-user-shield me-1"></i> ADMINISTRATOR
                                </span>
                            @else
                                <span class="badge-custom bg-light text-muted border d-inline-block">
                                    <i class="fas fa-user me-1"></i> CUSTOMER / USER
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('owner.users.toggleAdmin', $user->id) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('{{ $user->role === 'admin' ? 
                                  'PERINGATAN: Mencabut hak akses ini akan menghentikan kemampuan staf untuk mengelola data operasional segera. Lanjutkan?' : 
                                  'KONFIRMASI: Memberikan hak admin akan membuka akses ke seluruh data proyek dan laporan keuangan. Lanjutkan?' }}')">
                                @csrf
                                @method('PATCH')
                                
                                @if($user->role === 'admin')
                                    <button type="submit" class="btn btn-action-danger btn-sm">
                                        <i class="fas fa-shield-slash me-2"></i>Cabut Otoritas
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-action-primary btn-sm">
                                        <i class="fas fa-shield-check me-2"></i>Otorisasi Admin
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-5">
                                <i class="fas fa-users-slash text-muted opacity-25 mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted fw-bold">Tidak ditemukan data pengguna lain.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* TYPOGRAPHY */
    .extra-small { font-size: 0.7rem; }
    
    /* BADGES */
    .badge-custom {
        padding: 6px 14px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.65rem;
        letter-spacing: 0.3px;
    }
    .bg-primary-soft { background-color: #eef2ff; }

    /* AVATAR BOX */
    .avatar-box {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: white;
        font-weight: 800;
        font-size: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    .admin-gradient { background: linear-gradient(135deg, #4f46e5, #3b82f6); }
    .user-gradient { background: linear-gradient(135deg, #94a3b8, #64748b); }

    /* BUTTONS */
    .btn-action-primary {
        background: white;
        color: #3b82f6;
        border: 1px solid #3b82f6;
        padding: 6px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-action-primary:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-action-danger {
        background: white;
        color: #ef4444;
        border: 1px solid #ef4444;
        padding: 6px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-action-danger:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* CARD IMPROVEMENTS */
    .table thead th { border-top: none; }
    .table tbody td { border-bottom: 1px solid #f1f5f9; }
</style>
@endsection