<x-guest-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <style>
        body { font-family: "Plus Jakarta Sans", sans-serif; background: #fdfdfe; color: #1e293b; scroll-behavior: smooth; }
        .hero-title { text-shadow: 0 4px 15px rgba(0,0,0,0.4); }
        .glass-nav { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease; }
        .glass-nav:hover { background: rgba(255, 255, 255, 0.2); transform: translateY(-2px); }
        .card-premium { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #f1f5f9; }
        .card-premium:hover { transform: translateY(-10px); box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.08); border-color: #3b82f6; }
        .stat-box { transition: all 0.3s ease; }
        .stat-box:hover { transform: scale(1.05); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    </style>

    <div class="min-h-screen pb-24">
        <div class="relative h-[600px] w-full overflow-hidden bg-slate-950">
            <img src="{{ asset('storage/' . $project->gambar) }}" 
                 class="w-full h-full object-cover opacity-50 scale-105 transition-transform duration-[10s] hover:scale-100" 
                 alt="{{ $project->nama_proyek }}"
                 onerror="this.src='{{ asset('images/rumah.jpg') }}'">
            
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full p-8 lg:p-20 pb-52 z-20">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col gap-4">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center text-white/90 hover:text-white mb-6 glass-nav px-6 py-2.5 rounded-full font-bold text-xs uppercase tracking-widest group w-fit">
                            <i class="bi bi-arrow-left me-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Beranda
                        </a>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="bg-blue-600 h-8 w-1 rounded-full shadow-lg shadow-blue-500/50"></span>
                            <span class="text-blue-400 font-black text-xs uppercase tracking-[0.3em]">Exclusive Project</span>
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-black text-white leading-tight tracking-tighter hero-title">
                            {{ $project->nama_proyek }}
                        </h1>
                        <p class="text-white/80 flex items-center text-lg font-medium tracking-wide">
                            <i class="bi bi-geo-alt-fill me-2 text-blue-500"></i> {{ $project->lokasi }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-28 relative z-30">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                
                <div class="lg:col-span-2 space-y-12">
                    
                    <div class="bg-white/90 backdrop-blur-xl p-2 rounded-[3rem] shadow-2xl border border-white">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <div class="text-center py-8 rounded-[2.5rem] bg-slate-50 border border-slate-100/50 group transition-all hover:bg-blue-600">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-blue-100">Total Unit</p>
                                <p class="text-3xl font-black text-slate-800 group-hover:text-white">{{ $project->total_unit }}</p>
                            </div>
                            <div class="text-center py-8 rounded-[2.5rem] bg-emerald-50 border border-emerald-100/50 group transition-all hover:bg-emerald-600">
                                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2 group-hover:text-emerald-100">Tersedia</p>
                                <p class="text-3xl font-black text-slate-800 group-hover:text-white">{{ $project->tersedia }}</p>
                            </div>
                            <div class="text-center py-8 rounded-[2.5rem] bg-amber-50 border border-amber-100/50 group transition-all hover:bg-amber-600">
                                <p class="text-[9px] font-black text-amber-500 uppercase tracking-widest mb-2 group-hover:text-amber-100">Booking</p>
                                <p class="text-3xl font-black text-slate-800 group-hover:text-white">{{ $project->booked }}</p>
                            </div>
                            <div class="text-center py-8 rounded-[2.5rem] bg-slate-900 group transition-all hover:bg-red-600">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 group-hover:text-red-100">Terjual</p>
                                <p class="text-3xl font-black text-white tracking-tighter">{{ $project->terjual }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-10 bg-blue-600 rounded-full"></div>
                            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Katalog Pilihan Tipe</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($project->tipes as $tipe)
                            <div class="bg-white rounded-[2.5rem] overflow-hidden card-premium group">
                                <div class="p-8">
                                    <div class="flex justify-between items-start mb-8">
                                        <div>
                                            <h3 class="text-2xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">Tipe {{ $tipe->nama_tipe }}</h3>
                                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Premium Residence</span>
                                        </div>
                                        <div class="bg-blue-50 p-3 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            <i class="bi bi-house-door-fill fs-4"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="p-3 bg-slate-50 rounded-xl flex items-center gap-3">
                                            <i class="bi bi-aspect-ratio text-blue-500"></i>
                                            <div>
                                                <p class="text-[8px] text-slate-400 font-black uppercase">LT</p>
                                                <p class="text-xs font-bold text-slate-700">{{ $tipe->luas_tanah }} m²</p>
                                            </div>
                                        </div>
                                        <div class="p-3 bg-slate-50 rounded-xl flex items-center gap-3">
                                            <i class="bi bi-building text-blue-500"></i>
                                            <div>
                                                <p class="text-[8px] text-slate-400 font-black uppercase">LB</p>
                                                <p class="text-xs font-bold text-slate-700">{{ $tipe->luas_bangunan }} m²</p>
                                            </div>
                                        </div>
                                        <div class="p-3 bg-slate-50 rounded-xl flex items-center gap-3">
                                            <i class="bi bi-door-open text-blue-500"></i>
                                            <div>
                                                <p class="text-[8px] text-slate-400 font-black uppercase">Bed</p>
                                                <p class="text-xs font-bold text-slate-700">{{ $tipe->kamar_tidur ?? '2' }} Unit</p>
                                            </div>
                                        </div>
                                        <div class="p-3 bg-slate-50 rounded-xl flex items-center gap-3">
                                            <i class="bi bi-droplet text-blue-500"></i>
                                            <div>
                                                <p class="text-[8px] text-slate-400 font-black uppercase">Bath</p>
                                                <p class="text-xs font-bold text-slate-700">{{ $tipe->kamar_mandi ?? '1' }} Unit</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                                        <div>
                                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Mulai Dari</p>
                                            <p class="text-2xl font-black text-blue-600">Rp {{ number_format($tipe->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <a href="{{ route('login') }}" class="h-12 w-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all shadow-lg">
                                            <i class="bi bi-chevron-right fs-5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100 overflow-hidden">
                        <div class="p-10 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Status Unit Proyek</h2>
                            <span class="bg-blue-100 text-blue-600 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-blue-200">Real-time Update</span>
                        </div>
                        <div class="overflow-x-auto p-6">
                            <table class="w-full text-left border-separate border-spacing-y-3">
                                <thead>
                                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] px-4">
                                        <th class="px-8 py-2">Identifier</th>
                                        <th class="px-8 py-2">Unit Type</th>
                                        <th class="px-8 py-2">Availability</th>
                                        <th class="px-8 py-2 text-center">Direct Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->units as $unit)
                                    <tr class="hover:bg-slate-50 transition-all bg-white shadow-sm ring-1 ring-slate-100 rounded-2xl">
                                        <td class="px-8 py-6 rounded-l-2xl border-y border-l border-slate-100">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-xs">{{ $unit->block }}</div>
                                                <span class="font-black text-slate-700 text-lg">No. {{ $unit->no_unit }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 border-y border-slate-100">
                                            <p class="text-slate-600 font-bold text-sm tracking-tight">{{ $unit->tipe->nama_tipe ?? 'Signature Tipe' }}</p>
                                        </td>
                                        <td class="px-8 py-6 border-y border-slate-100">
                                            @php
                                                $statusStyle = match($unit->status) {
                                                    'Tersedia' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'Dibooking' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'Terjual' => 'bg-slate-100 text-slate-400 border-slate-200',
                                                    default => 'bg-gray-50 text-gray-400'
                                                };
                                            @endphp
                                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $statusStyle }}">
                                                {{ $unit->status }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-center rounded-r-2xl border-y border-r border-slate-100">
                                            @if($unit->status == 'Tersedia')
                                                <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-xl font-black text-[9px] uppercase hover:bg-blue-700 transition-all shadow-md">Book Now</a>
                                            @else
                                                <span class="text-slate-300 font-black text-[9px] uppercase italic">Reserved</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="lg:sticky lg:top-32 space-y-8">
                    <div class="bg-slate-900 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden text-white animate-float border border-slate-800">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600 rounded-full -mr-24 -mt-24 opacity-20 blur-3xl"></div>
                        
                        <div class="relative z-10 space-y-8">
                            <div>
                                <h2 class="text-2xl font-black mb-4 tracking-tight">The Vision</h2>
                                <div class="text-slate-300 text-sm leading-relaxed prose prose-invert opacity-80">
                                    {!! $project->deskripsi !!}
                                </div>
                            </div>

                            <div class="pt-8 border-t border-white/10">
                                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-6">Priority Inquiries</p>
                                <a href="https://wa.me/6281310619585" target="_blank" class="flex items-center gap-5 p-5 rounded-3xl bg-white/5 border border-white/10 hover:bg-emerald-600 transition-all group">
                                    <div class="h-12 w-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-tighter mb-1 group-hover:text-white">Customer Support</p>
                                        <p class="text-base font-black tracking-tight group-hover:text-white">Live Consult</p>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="space-y-4 pt-2">
                                @foreach(['Eco-friendly Smart Home', '24/7 Premium Security', 'Prime Investment Location'] as $benefit)
                                <div class="flex items-center gap-3 text-[10px] font-bold text-slate-300">
                                    <i class="bi bi-check-circle-fill text-blue-500"></i> {{ $benefit }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>