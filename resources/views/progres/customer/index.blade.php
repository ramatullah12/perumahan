@extends('layout.customer')

@section('content')
<div style="padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- HEADER --}}
    <div style="margin-bottom: 35px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; font-weight: 900; color: #1e293b; letter-spacing: -1px; margin: 0;">Progres Unit Saya</h2>
            <p style="color: #64748b; font-weight: 500; margin-top: 5px;">Pantau real-time pembangunan rumah impian Anda secara akurat</p>
        </div>
        <div style="background: white; padding: 10px 22px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-sync-alt" style="color: #3b82f6; font-size: 12px;"></i>
            <span style="font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Update: {{ date('d M Y') }}</span>
        </div>
    </div>

    @forelse($bookings as $b)
        @php
            /** * PERBAIKAN: Mengambil nilai progres langsung dari kolom unit (integer)
             * Menghapus ->first() karena 'progres' adalah properti angka, bukan relasi.
             */
            $persentase = $b->unit->progres ?? 0;
            $persentase = max(0, min(100, $persentase));

            /** * Karena Anda menggunakan kolom 'progres' sebagai angka, 
             * jika ingin menampilkan catatan lapangan, kita perlu mengecek relasi histori yang benar.
             * Di sini kita asumsikan menggunakan data default jika relasi belum diset.
             */
            $latestHistory = $b->unit->latestProgres ?? null; 
        @endphp

        <div style="background: white; border-radius: 32px; padding: 40px; border: 1px solid #e2e8f0; margin-bottom: 35px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.04); position: relative; overflow: hidden; transition: transform 0.3s ease;">
            
            {{-- AKSEN STATUS SAMPING --}}
            <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background: linear-gradient(to bottom, #1e5eff, #3b82f6);"></div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 35px;">
                <div>
                    <h4 style="font-weight: 900; color: #1e5eff; font-size: 24px; margin: 0; letter-spacing: -0.5px;">{{ $b->unit->project->nama_proyek ?? 'Proyek Kedaton' }}</h4>
                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                        <span style="background: #eff6ff; color: #1e5eff; font-weight: 800; font-size: 14px; padding: 4px 12px; border-radius: 8px;">Blok {{ $b->unit->block }} No. {{ $b->unit->no_unit }}</span>
                        <span style="width: 5px; height: 5px; background: #cbd5e1; border-radius: 50%;"></span>
                        <span style="color: #64748b; font-weight: 600; font-size: 15px;">Tipe {{ $b->unit->tipe->nama_tipe }}</span>
                    </div>
                </div>
                <div>
                    <span style="background: #f0fdf4; color: #166534; padding: 12px 24px; border-radius: 16px; font-size: 13px; font-weight: 900; text-transform: uppercase; border: 1px solid #dcfce7; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.1);">
                        <span style="width: 10px; height: 10px; background: #22c55e; border-radius: 50%; display: inline-block;"></span>
                        {{ $b->status }}
                    </span>
                </div>
            </div>

            {{-- PROGRESS BAR AREA --}}
            <div style="background: #f8fafc; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9; margin-bottom: 35px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span style="color: #475569; font-weight: 800; font-size: 16px;">
                        Status: <span style="color: #1e5eff;">{{ $persentase == 100 ? 'Selesai' : 'Tahap Pembangunan' }}</span>
                    </span>
                    <div style="text-align: right;">
                        <span style="color: #1e5eff; background: white; border: 1px solid #d1e9ff; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 900; box-shadow: 0 2px 4px rgba(30, 94, 255, 0.05);">
                            {{ $persentase }}% Selesai
                        </span>
                    </div>
                </div>
                
                {{-- TRACK BAR --}}
                <div style="width: 100%; background: #e2e8f0; height: 22px; border-radius: 14px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="width: {{ $persentase }}%; 
                                background: linear-gradient(90deg, #1e5eff 0%, #60a5fa 100%); 
                                height: 100%; 
                                border-radius: 14px;
                                transition: width 2s cubic-bezier(0.34, 1.56, 0.64, 1);
                                box-shadow: 0 4px 12px rgba(30, 94, 255, 0.3);
                                position: relative;">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(rgba(255,255,255,0.2), rgba(255,255,255,0));"></div>
                    </div>
                </div>
            </div>

            {{-- INFO FOOTER --}}
            <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 35px;">
                
                {{-- CATATAN LAPANGAN --}}
                <div>
                    <h5 style="font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-clipboard-list" style="color: #1e5eff;"></i> Catatan Pembangunan:
                    </h5>
                    <div style="background: #ffffff; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; min-height: 100px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                        <p style="margin: 0; font-size: 15px; color: #64748b; line-height: 1.7; font-style: italic;">
                            "{{ $latestHistory->keterangan ?? 'Tim kami sedang memproses pembangunan di lokasi. Pembaruan data foto akan dilakukan segera setelah progres fisik terlihat di lapangan.' }}"
                        </p>
                    </div>
                </div>

                {{-- FOTO DOKUMENTASI --}}
                <div>
                    <h5 style="font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-camera" style="color: #1e5eff;"></i> Dokumentasi Terbaru:
                    </h5>
                    <div style="position: relative;">
                        @if($latestHistory && $latestHistory->foto)
                            <a href="{{ asset('storage/' . $latestHistory->foto) }}" target="_blank" style="text-decoration: none; display: block; border-radius: 20px; overflow: hidden; border: 4px solid white; box-shadow: 0 15px 30px -5px rgba(0,0,0,0.12);">
                                <img src="{{ asset('storage/' . $latestHistory->foto) }}" 
                                     style="width: 100%; height: 180px; object-fit: cover;"
                                     alt="Dokumentasi Unit">
                            </a>
                        @else
                            <div style="width: 100%; height: 180px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #cbd5e1;">
                                <i class="fas fa-hard-hat" style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;"></i>
                                <span style="font-size: 13px; font-weight: 800; color: #94a3b8;">Tahap Persiapan</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 100px 40px; background: white; border-radius: 48px; border: 3px dashed #cbd5e1; margin-top: 20px;">
            <h3 style="color: #1e293b; font-weight: 900; font-size: 26px; margin-bottom: 15px;">Belum Ada Data Progres</h3>
            <p style="color: #64748b; font-size: 16px; max-width: 450px; margin: 0 auto;">Pemesanan Anda sedang diverifikasi. Data pembangunan akan muncul di sini segera.</p>
        </div>
    @endforelse
</div>
@endsection