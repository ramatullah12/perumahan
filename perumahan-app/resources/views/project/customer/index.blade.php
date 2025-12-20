@extends('dashboard.customer')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen animate-fade-in">
    {{-- Header Section --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-2">
                Jelajahi Proyek Perumahan
            </h2>
            <p class="text-slate-500 text-lg font-medium">Temukan unit rumah impian Anda dengan fasilitas terbaik</p>
        </div>
        <div class="hidden md:flex items-center gap-4">
            <div class="bg-blue-600/10 text-blue-600 px-4 py-2 rounded-2xl text-sm font-bold border border-blue-600/20">
                <i class="fas fa-check-circle mr-2"></i>Update Real-time
            </div>
        </div>
    </div>

    {{-- Grid Proyek --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-10">
        @forelse($projects as $project)
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-100 group transition-all duration-500 hover:-translate-y-3 flex flex-col">
                
                {{-- Image Container --}}
                <div class="h-72 bg-slate-200 relative overflow-hidden">
                    @if($project->gambar)
                        <img src="{{ asset('storage/' . $project->gambar) }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                             alt="{{ $project->nama_proyek }}">
                    @else
                        <div class="flex items-center justify-center h-full bg-slate-100 text-slate-300 italic text-sm flex-col">
                            <i class="fas fa-building text-6xl mb-2"></i>
                            <span>Gambar Proyek</span>
                        </div>
                    @endif
                    
                    {{-- Status Badge Proyek --}}
                    <div class="absolute top-6 left-6">
                        <span class="bg-white/90 backdrop-blur-md text-blue-600 px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                            <i class="fas fa-circle text-[8px] mr-2 animate-pulse"></i> {{ $project->status }}
                        </span>
                    </div>
                </div>

                {{-- Content Section --}}
                <div class="p-8 flex-1 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-slate-900 mb-2 group-hover:text-blue-600 transition-colors leading-tight">
                            {{ $project->nama_proyek }}
                        </h3>
                        <div class="flex items-center text-slate-400 font-semibold text-sm">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                            {{ $project->lokasi }}
                        </div>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2 italic border-l-4 border-slate-100 pl-4">
                        "{{ $project->deskripsi ?? 'Hunian eksklusif dengan konsep modern dan lingkungan asri untuk keluarga.' }}"
                    </p>

                    {{-- PERBAIKAN: Statistik Unit Proyek (Memanggil variabel hasil withCount dari Controller) --}}
                    <div class="grid grid-cols-3 gap-3 mb-8">
                        <div class="bg-green-50/50 p-3 rounded-2xl text-center border border-green-100 transition-colors hover:bg-green-50">
                            <p class="text-[9px] uppercase font-black text-green-600 mb-1 tracking-tighter">Tersedia</p>
                            {{-- Mengambil hitungan riil unit status 'Tersedia' --}}
                            <p class="text-lg font-black text-green-700">{{ number_format($project->tersedia) }}</p>
                        </div>
                        <div class="bg-orange-50/50 p-3 rounded-2xl text-center border border-orange-100 transition-colors hover:bg-orange-50">
                            <p class="text-[9px] uppercase font-black text-orange-600 mb-1 tracking-tighter">Booked</p>
                            {{-- Mengambil hitungan riil unit status 'Dibooking' --}}
                            <p class="text-lg font-black text-orange-700">{{ number_format($project->booked) }}</p>
                        </div>
                        <div class="bg-blue-50/50 p-3 rounded-2xl text-center border border-blue-100 transition-colors hover:bg-blue-50">
                            <p class="text-[9px] uppercase font-black text-blue-600 mb-1 tracking-tighter">Terjual</p>
                            {{-- Mengambil hitungan riil unit status 'Terjual' --}}
                            <p class="text-lg font-black text-blue-700">{{ number_format($project->terjual) }}</p>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="mt-auto">
                        <a href="{{ route('customer.booking.create', ['project_id' => $project->id]) }}" 
                           class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-blue-200 hover:bg-slate-900 transition-all duration-300 flex items-center justify-center group/btn active:scale-95">
                            Booking Unit Sekarang
                            <i class="fas fa-arrow-right ml-3 transition-transform group-hover/btn:translate-x-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-[3rem] border-4 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mb-8 text-slate-200">
                    <i class="fas fa-city text-5xl"></i>
                </div>
                <h4 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Belum Ada Proyek Aktif</h4>
                <p class="text-slate-400 mt-2 max-w-sm mx-auto">Kami sedang mempersiapkan hunian eksklusif baru untuk Anda. Silakan cek secara berkala.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes fade-in { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection