@extends('dashboard.customer')

@section('content')
<div style="padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- HEADER --}}
    <div style="margin-bottom: 35px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; font-weight: 900; color: #1e293b; letter-spacing: -1px; margin: 0;">Progres Unit Saya</h2>
            <p style="color: #64748b; font-weight: 500; margin-top: 5px;">Pantau real-time pembangunan rumah impian Anda</p>
        </div>
        <div style="background: white; padding: 10px 20px; border-radius: 15px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <span style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Update: {{ date('d M Y') }}</span>
        </div>
    </div>

    @forelse($bookings as $b)
        <div style="background: white; border-radius: 28px; padding: 35px; border: 1px solid #e2e8f0; margin-bottom: 30px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05); position: relative; overflow: hidden;">
            
            {{-- AKSEN STATUS --}}
            <div style="position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: #1e5eff;"></div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                <div>
                    <h4 style="font-weight: 900; color: #1e5eff; font-size: 22px; margin: 0;">{{ $b->unit->project->nama_proyek ?? 'Proyek Kedaton' }}</h4>
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                        <span style="color: #1e293b; font-weight: 800; font-size: 16px;">Blok {{ $b->unit->block ?? '-' }} No. {{ $b->unit->no_unit ?? '-' }}</span>
                        <span style="width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%;"></span>
                        <span style="color: #64748b; font-weight: 600; font-size: 14px;">Tipe {{ $b->unit->tipe->nama_tipe ?? 'N/A' }}</span>
                    </div>
                </div>
                <div>
                    <span style="background: #f0fdf4; color: #166534; padding: 10px 20px; border-radius: 14px; font-size: 12px; font-weight: 900; text-transform: uppercase; border: 1px solid #dcfce7; display: inline-flex; align-items: center; gap: 8px;">
                        <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                        {{ $b->status }}
                    </span>
                </div>
            </div>

            {{-- PROGRESS BAR AREA --}}
            <div style="background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9;">
                <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; margin-bottom: 15px;">
                    <span style="color: #475569;">Tahap: <span style="color: #1e5eff;">{{ $b->unit->latestProgres->tahap ?? 'Persiapan Lahan' }}</span></span>
                    <span style="color: #1e5eff; background: white; border: 1px solid #d1e9ff; padding: 4px 12px; border-radius: 10px; font-size: 13px;">
                        {{ $b->unit->latestProgres->persentase ?? 0 }}% Selesai
                    </span>
                </div>
                
                {{-- TRACK BAR --}}
                <div style="width: 100%; background: #e2e8f0; height: 18px; border-radius: 12px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="width: {{ $b->unit->latestProgres->persentase ?? 0 }}%; 
                                background: linear-gradient(90deg, #1e5eff 0%, #3b82f6 100%); 
                                height: 100%; 
                                border-radius: 12px;
                                transition: 1.5s cubic-bezier(0.4, 0, 0.2, 1);
                                box-shadow: 0 2px 10px rgba(30, 94, 255, 0.3);">
                    </div>
                </div>
            </div>

            {{-- INFO FOOTER --}}
            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; margin-top: 30px;">
                
                {{-- CATATAN --}}
                <div style="padding-right: 20px;">
                    <h5 style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 12px;">Catatan Lapangan Terakhir:</h5>
                    <div style="background: #ffffff; padding: 15px; border-radius: 15px; border: 1px solid #f1f5f9; min-height: 80px;">
                        <p style="margin: 0; font-size: 14px; color: #64748b; line-height: 1.6; font-style: italic;">
                            "{{ $b->unit->latestProgres->keterangan ?? 'Tim kami sedang mempersiapkan material dan area pembangunan. Data akan diupdate segera setelah tahap awal selesai.' }}"
                        </p>
                    </div>
                    <p style="font-size: 11px; color: #94a3b8; margin-top: 10px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                        <i class="far fa-clock"></i> Terakhir diperbarui: {{ $b->unit->latestProgres ? $b->unit->latestProgres->created_at->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>

                {{-- FOTO --}}
                <div>
                    <h5 style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 12px;">Foto Dokumentasi:</h5>
                    <div style="position: relative; group">
                        @if($b->unit->latestProgres && $b->unit->latestProgres->foto)
                            <a href="{{ asset('storage/' . $b->unit->latestProgres->foto) }}" target="_blank" style="text-decoration: none;">
                                <img src="{{ asset('storage/' . $b->unit->latestProgres->foto) }}" 
                                     style="width: 100%; height: 140px; object-fit: cover; border-radius: 18px; border: 2px solid white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); cursor: zoom-in;"
                                     alt="Dokumentasi Pembangunan">
                            </a>
                        @else
                            <div style="width: 100%; height: 140px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 18px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #cbd5e1;">
                                <i class="fas fa-camera" style="font-size: 24px; margin-bottom: 8px;"></i>
                                <span style="font-size: 11px; font-weight: 700;">Belum Ada Foto</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    @empty
        <div style="text-align: center; padding: 100px 40px; background: white; border-radius: 40px; border: 3px dashed #e2e8f0; margin-top: 20px;">
            <div style="font-size: 80px; margin-bottom: 25px;">🚧</div>
            <h3 style="color: #1e293b; font-weight: 900; font-size: 22px; margin-bottom: 10px;">Belum Ada Unit yang Terdaftar</h3>
            <p style="color: #94a3b8; font-size: 15px; max-width: 350px; margin: 0 auto; line-height: 1.6;">Progres pembangunan akan otomatis muncul di sini setelah **Booking Anda disetujui** oleh Admin.</p>
            <a href="{{ route('customer.booking.index') }}" style="display: inline-block; margin-top: 25px; background: #1e5eff; color: white; padding: 12px 30px; border-radius: 15px; text-decoration: none; font-weight: 800; font-size: 14px; box-shadow: 0 10px 15px -3px rgba(30, 94, 255, 0.2);">Cek Status Booking Saya</a>
        </div>
    @endforelse
</div>
@endsection