@extends('layout.admin')

@section('content')
<div class="booking-admin-container" style="padding: 20px; position: relative;">
    
    <style>
        .booking-title { font-size: 24px; font-weight: 800; color: #1a202c; margin-bottom: 4px; letter-spacing: -0.5px; }
        .booking-sub { color: #718096; font-size: 14px; margin-bottom: 30px; }
        
        .table-header {
            background: #f8fafc;
            padding: 18px 25px;
            border-radius: 15px;
            border: 1px solid #e2e8f0;
            font-weight: 800;
            font-size: 11px;
            display: grid;
            grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr 150px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-row {
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            border: 1px solid #e2e8f0;
            margin-top: 12px;
            font-size: 14px;
            display: grid;
            grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr 150px;
            align-items: center;
            transition: 0.2s;
        }

        .table-row:hover { border-color: #1e5eff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }

        .badge-status {
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 800;
            display: inline-block;
            text-transform: uppercase;
        }
        .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
        .status-disetujui { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .status-ditolak { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

        .btn-action { width: 38px; height: 38px; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; transition: 0.3s; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; }
        .btn-approve { background: #10b981; }
        .btn-approve:hover { background: #059669; transform: translateY(-2px); }
        .btn-reject { background: #ef4444; }
        .btn-reject:hover { background: #dc2626; transform: translateY(-2px); }
        
        .btn-revoke { background: #64748b; width: auto; padding: 0 15px; height: 32px; font-size: 10px; gap: 6px; }
        .btn-revoke:hover { background: #334155; transform: translateY(-2px); }

        .form-hidden { display: none !important; }
    </style>

    <div class="booking-title">Manajemen Booking</div>
    <div class="booking-sub">Validasi permohonan unit dan kontrol akses notifikasi customer</div>

    <div class="table-header">
        <div>Customer</div>
        <div>Proyek & Unit</div>
        <div>Tanggal</div>
        <div>KTP</div>
        <div>Status</div>
        <div>Aksi</div>
    </div>

    @forelse($bookings as $booking)
        <div class="table-row">
            <div>
                <div style="font-weight: 800; color: #1a202c;">{{ $booking->user->name ?? $booking->nama ?? 'N/A' }}</div>
                <div style="font-size: 12px; color: #94a3b8;">{{ $booking->user->email ?? '-' }}</div>
            </div>

            <div>
                <div style="font-weight: 700; color: #1e5eff;">{{ $booking->unit->project->nama_proyek ?? 'N/A' }}</div>
                <div style="font-size: 12px; color: #64748b;">Blok {{ $booking->unit->block ?? '-' }} No. {{ $booking->unit->no_unit ?? '-' }}</div>
            </div>

            <div style="font-weight: 600; color: #475569;">
                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d M Y') }}
            </div>

            <div>
                @if($booking->dokumen)
                    {{-- FIXED: Langsung menggunakan URL dari database (Cloudinary) --}}
                    <a href="{{ $booking->dokumen }}" target="_blank" style="color: #1e5eff; font-weight: 800; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-id-card"></i> LIHAT KTP
                    </a>
                @else
                    <span style="color: #cbd5e1; font-size: 12px;">-</span>
                @endif
            </div>

            <div>
                <span class="badge-status status-{{ strtolower($booking->status) }}">
                    ● {{ $booking->status }}
                </span>
            </div>

            <div>
                @if($booking->status == 'pending')
                    <div style="display: flex; gap: 10px;">
                        <button type="button" onclick="confirmAction('{{ $booking->id }}', 'approve', '{{ $booking->user->name ?? $booking->nama }}')" class="btn-action btn-approve" title="Setujui & Beri Akses">
                            <i class="fas fa-check"></i>
                        </button>
                        <form id="form-approve-{{ $booking->id }}" action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST" class="form-hidden">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="disetujui">
                        </form>

                        <button type="button" onclick="confirmAction('{{ $booking->id }}', 'reject', '{{ $booking->user->name ?? $booking->nama }}')" class="btn-action btn-reject" title="Tolak Booking">
                            <i class="fas fa-times"></i>
                        </button>
                        <form id="form-reject-{{ $booking->id }}" action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST" class="form-hidden">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="ditolak">
                        </form>
                    </div>
                @elseif($booking->status == 'disetujui')
                    <button type="button" onclick="confirmAction('{{ $booking->id }}', 'revoke', '{{ $booking->user->name ?? $booking->nama }}')" class="btn-action btn-revoke">
                        <i class="fas fa-undo-alt"></i> CABUT AKSES
                    </button>
                    <form id="form-revoke-{{ $booking->id }}" action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST" class="form-hidden">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="pending">
                    </form>
                @else
                    <span style="font-size: 11px; font-weight: 800; color: #94a3b8;">VERIFIED</span>
                @endif
            </div>
        </div>
    @empty
        <div class="table-row" style="display: block; text-align: center; padding: 40px;">
            <div style="color: #94a3b8;">Belum ada data booking.</div>
        </div>
    @endforelse
</div>

{{-- Script SweetAlert dan Konfirmasi tetap sama --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(id, action, name) {
        let title, text, icon, confirmColor;
        
        if(action === 'approve') {
            title = 'Setujui?';
            text = `Beri akses notifikasi & progres untuk ${name}`;
            icon = 'success';
            confirmColor = '#10b981';
        } else if(action === 'reject') {
            title = 'Tolak?';
            text = `Batalkan permohonan unit untuk ${name}`;
            icon = 'error';
            confirmColor = '#ef4444';
        } else {
            title = 'Cabut Akses?';
            text = `Kembalikan status ke Pending & hapus akses milik ${name}`;
            icon = 'warning';
            confirmColor = '#64748b';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Lanjutkan!',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-${action}-${id}`).submit();
            }
        });
    }
</script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif
@endsection