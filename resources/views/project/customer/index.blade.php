@extends('layout.customer')

@section('content')
<div class="p-4 md:p-10 bg-slate-50 min-h-screen animate-fade-in">
    {{-- Header Section --}}
    <div class="mb-12 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div class="max-w-2xl">
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4 leading-tight">
                Temukan <span class="text-blue-600 underline decoration-blue-100 underline-offset-8">Hunian Impian</span> Anda
            </h2>
            <p class="text-slate-500 text-lg font-medium">Jelajahi berbagai pilihan proyek perumahan eksklusif dengan fasilitas lengkap di lokasi strategis.</p>
        </div>
        
        {{-- Search & Filter Bar --}}
        <div class="flex items-center gap-3 bg-white p-2 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Cari nama proyek..." class="pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border-none outline-none focus:ring-2 focus:ring-blue-500/20 transition-all text-sm font-bold w-full md:w-64">
            </div>
            <button class="bg-slate-900 text-white p-3.5 rounded-2xl hover:bg-blue-600 transition-colors shadow-lg">
                <i class="fas fa-sliders-h"></i>
            </button>
        </div>
    </div>

    {{-- Grid Proyek --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">
        @forelse($projects as $project)
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/40 overflow-hidden border border-slate-100 group transition-all duration-500 hover:-translate-y-4 flex flex-col relative">
                
                {{-- Image Container --}}
                <div class="h-80 relative overflow-hidden bg-slate-200">
                    @if($project->gambar)
                        {{-- PERBAIKAN: Langsung panggil variabel gambar (Tanpa asset()) --}}
                        <img src="{{ $project->gambar }}" 
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" 
                             alt="{{ $project->nama_proyek }}"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=Gambar+Tidak+Tersedia';">
                    @else
                        <div class="flex items-center justify-center h-full bg-slate-100 text-slate-300 italic flex-col">
                            <i class="fas fa-house-user text-7xl mb-4 opacity-20"></i>
                            <span class="text-xs font-black uppercase tracking-widest">No Visual Available</span>
                        </div>
                    @endif
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-6 left-6">
                        <span class="bg-white/80 backdrop-blur-md text-slate-900 px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg border border-white/50 flex items-center">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 {{ $project->tersedia > 0 ? 'animate-ping' : '' }}"></span>
                            {{ $project->status }}
                        </span>
                    </div>

                    @if($project->tersedia <= 5 && $project->tersedia > 0)
                    <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                        <div class="bg-red-600 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-red-500/30">
                            Sisa {{ $project->tersedia }} Unit!
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Content Section --}}
                <div class="p-8 flex-1 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-tight mb-2">
                            {{ $project->nama_proyek }}
                        </h3>
                        <div class="flex items-center text-slate-400 font-bold text-sm">
                            <i class="fas fa-location-arrow text-blue-500 mr-2"></i>
                            {{ $project->lokasi }}
                        </div>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-3 font-medium">
                        {{ $project->deskripsi ?? 'Nikmati kenyamanan tinggal di hunian yang dirancang khusus untuk gaya hidup modern.' }}
                    </p>

                    {{-- Info Cards --}}
                    <div class="grid grid-cols-3 gap-2 mb-8 bg-slate-50 p-2 rounded-[2rem] border border-slate-100">
                        <div class="p-3 text-center">
                            <p class="text-[9px] uppercase font-black text-slate-400 mb-1 tracking-widest">Tersedia</p>
                            <p class="text-lg font-black text-emerald-600">{{ number_format($project->tersedia) }}</p>
                        </div>
                        <div class="p-3 text-center border-x border-slate-200">
                            <p class="text-[9px] uppercase font-black text-slate-400 mb-1 tracking-widest">Booked</p>
                            <p class="text-lg font-black text-orange-500">{{ number_format($project->booked) }}</p>
                        </div>
                        <div class="p-3 text-center">
                            <p class="text-[9px] uppercase font-black text-slate-400 mb-1 tracking-widest">Sold</p>
                            <p class="text-lg font-black text-slate-400">{{ number_format($project->terjual) }}</p>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="mt-auto">
                        @if($project->tersedia > 0)
                            <a href="{{ route('customer.booking.create', ['project_id' => $project->id]) }}" 
                               class="w-full bg-slate-900 text-white py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-xs shadow-2xl shadow-slate-300 hover:bg-blue-600 transition-all duration-300 flex items-center justify-center group/btn active:scale-95">
                                Pelajari & Booking Unit
                                <i class="fas fa-arrow-right ml-3 transition-transform group-hover/btn:translate-x-2"></i>
                            </a>
                        @else
                            <button disabled 
                               class="w-full bg-slate-100 text-slate-400 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-xs cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-times-circle mr-3"></i> Unit Terjual Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 flex flex-col items-center justify-center text-center">
                <div class="w-40 h-40 bg-white shadow-2xl shadow-slate-200 rounded-[3rem] flex items-center justify-center mb-10 text-blue-100">
                    <i class="fas fa-layer-group text-7xl"></i>
                </div>
                <h4 class="text-3xl font-black text-slate-800 tracking-tight">Belum Ada Proyek Baru</h4>
                <p class="text-slate-400 mt-4 max-w-sm font-medium">Silakan kembali dalam beberapa waktu.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection