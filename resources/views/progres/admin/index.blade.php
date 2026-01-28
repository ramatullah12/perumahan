@extends('layout.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Monitoring Proyek</h2>
            <p class="text-gray-500 italic text-sm mt-1">Daftar unit terjual yang sedang dalam tahap pembangunan</p>
        </div>
        <div class="bg-blue-600 text-white px-6 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-blue-200">
            {{ $units->count() }} Unit Aktif
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
    <div id="alert-success" class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl flex justify-between items-center shadow-sm">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        <button onclick="document.getElementById('alert-success').remove()" class="text-green-500 hover:text-green-700 transition-colors bg-transparent border-none p-0">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Grid Progres Unit --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($units as $unit)
        @php
            // Logika Sinkronisasi: Ambil dari riwayat terbaru (latestProgres)
            // Jika tidak ada riwayat, pakai nilai default di tabel unit
            $persentaseTerbaru = $unit->latestProgres->persentase ?? ($unit->progres_pembangunan ?? 0);
            $tahapTerbaru = $unit->latestProgres->tahap ?? 'Persiapan';
        @endphp

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 hover:shadow-xl transition-all group relative overflow-hidden">
            
            {{-- Badge Status --}}
            <div class="absolute top-0 right-0 bg-blue-600 text-white px-6 py-2 rounded-bl-[1.5rem] text-[10px] font-black uppercase tracking-widest">
                {{ $unit->status ?? 'Sold' }}
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-black text-gray-800 group-hover:text-blue-600 transition-colors pr-12 line-clamp-1">
                    {{ $unit->project->nama_proyek ?? 'Proyek Tidak Diketahui' }}
                </h3>
                <p class="text-gray-500 font-bold">Blok {{ $unit->block }} No. {{ $unit->no_unit }}</p>
                
                <div class="flex items-center mt-3 text-gray-400 bg-gray-50 p-2 rounded-lg">
                    <i class="fas fa-user-circle mr-2 text-sm"></i>
                    <p class="text-[9px] font-black uppercase tracking-widest italic truncate">
                        Pembeli: {{ $unit->booking->user->name ?? ($unit->booking->nama ?? 'User Sistem') }}
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-end">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Progres Capaian</span>
                    <span class="text-blue-600 font-black text-2xl leading-none">
                        {{ $persentaseTerbaru }}%
                    </span>
                </div>
                
                {{-- Progress Bar Dinamis --}}
                <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden shadow-inner border border-gray-50">
                    <div class="bg-blue-600 h-full transition-all duration-700 ease-in-out" 
                         style="width: {{ $persentaseTerbaru }}%">
                    </div>
                </div>

                <div class="flex items-center text-[11px] font-bold text-gray-500 bg-blue-50/50 p-2 rounded-xl">
                    <i class="fas fa-hammer mr-2 text-blue-500"></i>
                    <span class="mr-1 text-blue-900">TAHAP:</span>
                    <span class="text-blue-600 uppercase">{{ $tahapTerbaru }}</span>
                </div>

                <a href="{{ route('admin.progres.edit', $unit->id) }}" 
                   style="text-decoration: none;"
                   class="w-full mt-6 bg-gray-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center">
                    <i class="fas fa-edit mr-2 text-xs"></i> Kelola Progres
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-gray-200">
            <div class="text-6xl mb-6">🏗️</div>
            <p class="text-gray-400 font-black uppercase tracking-widest text-xs">Belum ada unit terjual untuk dipantau</p>
            <p class="text-gray-300 text-sm italic mt-2">Hanya unit dengan status 'Terjual' yang muncul di sini</p>
        </div>
        @endforelse
    </div>
</div>
@endsection