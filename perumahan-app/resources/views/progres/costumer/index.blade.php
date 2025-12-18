@extends('dashboard.customer')

@section('content')
<div style="padding: 30px; font-family: 'Inter', sans-serif;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #1a202c;">Progres Unit Saya</h2>
        <p style="color: #718096;">Pantau pembangunan unit rumah yang telah Anda booking</p>
    </div>

    @forelse($bookings as $b)
        <div style="background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; margin-bottom: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <h4 style="font-weight: 800; color: #1e5eff; font-size: 18px; margin: 0;">{{ $b->unit->project->nama_proyek }}</h4>
                    <p style="color: #4a5568; font-weight: 600; margin: 5px 0 0 0;">Blok {{ $b->unit->block }} No. {{ $b->unit->no_unit }}</p>
                </div>
                <span style="background: #f0fdf4; color: #166534; padding: 6px 12px; border-radius: 10px; font-size: 11px; font-weight: 800;">
                    STATUS: {{ strtoupper($b->status) }}
                </span>
            </div>

            {{-- PROGRESS BAR --}}
            <div style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700; margin-bottom: 8px;">
                    <span style="color: #64748b;">Tahap Pembangunan</span>
                    <span style="color: #1e5eff;">{{ $b->unit->progres_pembangunan ?? 0 }}%</span>
                </div>
                <div style="width: 100%; background: #f1f5f9; height: 12px; border-radius: 10px; overflow: hidden;">
                    <div style="width: {{ $b->unit->progres_pembangunan ?? 0 }}%; background: #1e5eff; height: 100%; transition: 0.5s ease-in-out;"></div>
                </div>
            </div>

            <p style="margin-top: 20px; font-size: 12px; color: #94a3b8; font-style: italic;">
                *Data diperbarui secara berkala oleh tim lapangan Admin.
            </p>
        </div>
    @empty
        <div style="text-align: center; padding: 60px; background: white; border-radius: 20px; border: 2px dashed #e2e8f0;">
            <p style="color: #94a3b8; font-weight: 600;">Belum ada progres tersedia. Pastikan booking Anda sudah disetujui Admin.</p>
        </div>
    @endforelse
</div>
@endsection@extends('dashboard.customer')

@section('content')
<div style="padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; min-h: 100vh;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; font-weight: 900; color: #1e293b; letter-spacing: -1px;">Progres Unit Saya</h2>
        <p style="color: #64748b; font-weight: 500;">Pantau pembangunan rumah impian Anda secara real-time</p>
    </div>

    @forelse($bookings as $b)
        <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; margin-bottom: 25px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
                <div>
                    <h4 style="font-weight: 800; color: #1e5eff; font-size: 20px; margin: 0;">{{ $b->unit->project->nama_proyek ?? 'Proyek' }}</h4>
                    <p style="color: #475569; font-weight: 700; margin: 5px 0 0 0; font-size: 15px;">Blok {{ $b->unit->block }} No. {{ $b->unit->no_unit }}</p>
                </div>
                <div style="text-align: right;">
                    <span style="background: #f0fdf4; color: #166534; padding: 8px 16px; border-radius: 12px; font-size: 11px; font-weight: 900; text-transform: uppercase; border: 1px solid #dcfce7;">
                        ● {{ $b->status }}
                    </span>
                </div>
            </div>

            {{-- PROGRESS BAR UTAMA --}}
            <div style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: 800; margin-bottom: 12px;">
                    <span style="color: #475569;">Tahap: <span style="color: #1e5eff;">{{ $b->unit->latestProgres->tahap ?? 'Persiapan Lahan' }}</span></span>
                    <span style="color: #1e5eff; background: #eff6ff; padding: 2px 10px; border-radius: 8px;">{{ $b->unit->latestProgres->persentase ?? 0 }}%</span>
                </div>
                <div style="width: 100%; background: #f1f5f9; height: 16px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                    <div style="width: {{ $b->unit->latestProgres->persentase ?? 0 }}%; background: linear-gradient(90deg, #1e5eff, #60a5fa); height: 100%; transition: 1s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                </div>
            </div>

            {{-- DETAIL CATATAN TERBARU --}}
            @if($b->unit->latestProgres)
                <div style="margin-top: 25px; padding: 15px; background: #f8fafc; border-radius: 15px; border-left: 4px solid #1e5eff;">
                    <p style="margin: 0; font-size: 13px; color: #475569; line-height: 1.6;">
                        <strong>Catatan Admin:</strong> {{ $b->unit->latestProgres->keterangan ?? 'Sedang dalam pengerjaan sesuai jadwal.' }}
                    </p>
                    <small style="color: #94a3b8; font-size: 11px; margin-top: 5px; display: block; font-weight: 600;">
                        Update terakhir: {{ $b->unit->latestProgres->created_at->translatedFormat('d F Y') }}
                    </small>
                </div>

                {{-- FOTO DOKUMENTASI TERBARU --}}
                @if($b->unit->latestProgres->foto)
                    <div style="margin-top: 20px;">
                        <p style="font-size: 13px; font-weight: 800; color: #475569; margin-bottom: 10px;">Foto Lapangan Terakhir:</p>
                        <a href="{{ asset('storage/' . $b->unit->latestProgres->foto) }}" target="_blank">
                            <img src="{{ asset('storage/' . $b->unit->latestProgres->foto) }}" 
                                 style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 15px; border: 1px solid #e2e8f0; cursor: pointer;"
                                 alt="Progres Pembangunan">
                        </a>
                    </div>
                @endif
            @endif

            <p style="margin-top: 25px; font-size: 11px; color: #94a3b8; font-style: italic; text-align: center; border-top: 1px dashed #e2e8f0; pt: 15px;">
                *Data diperbarui secara berkala oleh tim teknis lapangan.
            </p>
        </div>
    @empty
        <div style="text-align: center; padding: 80px 40px; background: white; border-radius: 30px; border: 3px dashed #e2e8f0;">
            <div style="font-size: 50px; margin-bottom: 20px;">🏗️</div>
            <p style="color: #64748b; font-weight: 800; font-size: 18px; margin-bottom: 10px;">Belum Ada Unit yang Dipantau</p>
            <p style="color: #94a3b8; font-size: 14px; max-width: 300px; margin: 0 auto;">Pastikan booking Anda sudah disetujui Admin agar progres pembangunan muncul di sini.</p>
        </div>
    @endforelse
</div>
@endsection