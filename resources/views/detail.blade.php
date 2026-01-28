<x-guest-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <style>
        body { font-family: "Plus Jakarta Sans", sans-serif; background: #f8fafc; color: #1e293b; scroll-behavior: smooth; }
        .hero-title { text-shadow: 0 4px 20px rgba(0,0,0,0.5); letter-spacing: -0.04em; }
        .glass-nav { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .glass-nav:hover { background: rgba(255, 255, 255, 0.25); transform: translateX(5px); }
        .card-premium { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #f1f5f9; background: #ffffff; }
        .card-premium:hover { transform: translateY(-12px); box-shadow: 0 40px 80px -15px rgba(0, 0, 0, 0.1); border-color: #3b82f6; }
        .progress-bar-mini { height: 4px; border-radius: 10px; background: #e2e8f0; overflow: hidden; position: relative; }
        .progress-fill { position: absolute; left: 0; top: 0; height: 100%; transition: width 1s ease-in-out; }
        .animate-float { animation: float 5s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-15px); } }
    </style>

    <div class="min-h-screen pb-24">
        {{-- Hero Header --}}
        <div class="relative h-[650px] w-full overflow-hidden bg-slate-950">
            <img src="{{ asset('storage/' . $project->gambar) }}" 
                 class="w-full h-full object-cover opacity-60 scale-105 transition-transform duration-[15s] hover:scale-100" 
                 alt="{{ $project->nama_proyek }}"
                 onerror="this.src='{{ asset('images/rumah.jpg') }}'">
            
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full p-8 lg:p-20 pb-56 z-20">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col gap-4">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center text-white/90 hover:text-white mb-8 glass-nav px-8 py-3 rounded-2xl font-bold text-[10px] uppercase tracking-[0.2em] group w-fit">
                            <i class="bi bi-arrow-left me-3 group-hover:-translate-x-2 transition-transform"></i> Eksplorasi Proyek Lain
                        </a>
                        <div class="flex items-center gap-4 mb-2">
                            <span class="bg-blue-600 h-10 w-1 rounded-full shadow-[0_0_20px_rgba(37,99,235,0.8)]"></span>
                            <span class="text-blue-400 font-black text-xs uppercase tracking-[0.4em]">Masterpiece Collection</span>
                        </div>
                        <h1 class="text-6xl lg:text-8xl font-black text-white leading-none tracking-tighter hero-title mb-4">
                            {{ $project->nama_proyek }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-6">
                            <p class="text-white/80 flex items-center text-xl font-medium tracking-wide">
                                <i class="bi bi-geo-alt-fill me-3 text-blue-500 text-2xl"></i> {{ $project->lokasi }}
                            </p>
                            <span class="h-6 w-[1px] bg-white/20 hidden md:block"></span>
                            <p class="text-white/80 flex items-center text-xl font-medium tracking-wide italic">
                                <i class="bi bi-tag-fill me-3 text-emerald-500 text-2xl"></i> Investasi Masa Depan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Grid --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-36 relative z-30">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-16">
                    
                    {{-- Quick Stats Card --}}
                    <div class="bg-white/80 backdrop-blur-2xl p-3 rounded-[3.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="text-center py-10 rounded-[3rem] bg-slate-50 border border-slate-100 group transition-all hover:bg-slate-900">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-slate-500">Inventory</p>
                                <p class="text-4xl font-black text-slate-800 group-hover:text-white tracking-tighter">{{ $project->total_unit }} <span class="text-xs opacity-50">Unit</span></p>
                            </div>
                            <div class="text-center py-10 rounded-[3rem] bg-emerald-50 border border-emerald-100 group transition-all hover:bg-emerald-600">
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3 group-hover:text-emerald-100">Ready</p>
                                <p class="text-4xl font-black text-slate-800 group-hover:text-white tracking-tighter">{{ $project->tersedia }}</p>
                            </div>
                            <div class="text-center py-10 rounded-[3rem] bg-amber-50 border border-amber-100 group transition-all hover:bg-amber-500">
                                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-3 group-hover:text-amber-100">Reserved</p>
                                <p class="text-4xl font-black text-slate-800 group-hover:text-white tracking-tighter">{{ $project->booked }}</p>
                            </div>
                            <div class="text-center py-10 rounded-[3rem] bg-slate-100 border border-slate-200 group transition-all hover:bg-red-600">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 group-hover:text-red-100">Sold Out</p>
                                <p class="text-4xl font-black text-slate-800 group-hover:text-white tracking-tighter">{{ $project->terjual }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Katalog Tipe --}}
                    <div class="space-y-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="w-2 h-12 bg-blue-600 rounded-full"></div>
                                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Katalog Pilihan</h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            @foreach($project->tipes as $tipe)
                            <div class="rounded-[3rem] overflow-hidden card-premium group">
                                <div class="p-10">
                                    <div class="flex justify-between items-start mb-10">
                                        <div>
                                            <span class="px-4 py-1.5 bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all">Best Seller</span>
                                            <h3 class="text-3xl font-black text-slate-800 mt-4 tracking-tighter">Tipe {{ $tipe->nama_tipe }}</h3>
                                        </div>
                                        <div class="bg-slate-100 p-4 rounded-[2rem] text-slate-800 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                                            <i class="bi bi-house-heart-fill text-2xl"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-6 mb-10">
                                        @php
                                            $specs = [
                                                ['icon' => 'bi-aspect-ratio', 'label' => 'Tanah', 'val' => $tipe->luas_tanah . ' m²'],
                                                ['icon' => 'bi-building', 'label' => 'Bangunan', 'val' => $tipe->luas_bangunan . ' m²'],
                                                ['icon' => 'bi-door-open', 'label' => 'K. Tidur', 'val' => ($tipe->kamar_tidur ?? '2') . ' Unit'],
                                                ['icon' => 'bi-droplet', 'label' => 'K. Mandi', 'val' => ($tipe->kamar_mandi ?? '1') . ' Unit']
                                            ];
                                        @endphp
                                        @foreach($specs as $spec)
                                        <div class="p-4 bg-slate-50 rounded-3xl border border-slate-100 flex items-center gap-4 group-hover:bg-white transition-colors">
                                            <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-blue-600">
                                                <i class="bi {{ $spec['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-tighter">{{ $spec['label'] }}</p>
                                                <p class="text-sm font-bold text-slate-800">{{ $spec['val'] }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="pt-8 border-t border-slate-100 flex justify-between items-center">
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Mulai Dari</p>
                                            <p class="text-3xl font-black text-blue-600 tracking-tighter">Rp {{ number_format($tipe->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <a href="{{ route('login') }}" class="h-14 w-14 bg-slate-900 text-white rounded-[1.5rem] flex items-center justify-center hover:bg-blue-600 hover:rotate-12 transition-all shadow-xl shadow-slate-200">
                                            <i class="bi bi-arrow-right-short text-3xl"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Table Unit Status --}}
                    <div class="bg-white rounded-[3.5rem] shadow-2xl border border-slate-100 overflow-hidden">
                        <div class="p-10 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Ketersediaan Unit</h2>
                                <p class="text-slate-400 text-xs font-medium mt-1 italic">Pantau status & progres pembangunan secara live.</p>
                            </div>
                            <span class="hidden md:block bg-blue-100 text-blue-600 text-[10px] font-black px-5 py-2 rounded-full uppercase tracking-widest border border-blue-200 shadow-sm animate-pulse">Live Tracking</span>
                        </div>
                        <div class="overflow-x-auto p-8">
                            <table class="w-full text-left border-separate border-spacing-y-4">
                                <thead>
                                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                                        <th class="px-8 pb-2 text-center">Blok</th>
                                        <th class="px-8 pb-2">Detail Tipe</th>
                                        <th class="px-8 pb-2">Fase Progres</th>
                                        <th class="px-8 pb-2">Status</th>
                                        <th class="px-8 pb-2 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->units as $unit)
                                    <tr class="hover:bg-blue-50/30 transition-all bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] rounded-3xl group">
                                        <td class="px-8 py-6 rounded-l-[2rem] text-center border-y border-l border-slate-100">
                                            <span class="block text-xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ $unit->block }}-{{ $unit->no_unit }}</span>
                                        </td>
                                        <td class="px-8 py-6 border-y border-slate-100">
                                            <p class="text-slate-700 font-black text-sm uppercase tracking-tighter">{{ $unit->tipe->nama_tipe ?? 'Signature' }}</p>
                                        </td>
                                        <td class="px-8 py-6 border-y border-slate-100 min-w-[150px]">
                                            <div class="flex flex-col gap-2">
                                                <div class="flex justify-between items-center text-[9px] font-black uppercase text-slate-400">
                                                    <span>Pembangunan</span>
                                                    <span class="text-emerald-500">{{ $unit->progres ?? '0' }}%</span>
                                                </div>
                                                <div class="progress-bar-mini">
                                                    <div class="progress-fill bg-emerald-500" style="width: {{ $unit->progres ?? '0' }}%"></div>
                                                </div>
                                            </div>
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
                                            <span class="px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest border {{ $statusStyle }}">
                                                {{ $unit->status }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right rounded-r-[2rem] border-y border-r border-slate-100">
                                            @if($unit->status == 'Tersedia')
                                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg hover:-translate-y-1">Book Now</a>
                                            @else
                                                <span class="text-slate-300 font-black text-[10px] uppercase italic tracking-widest">Reserved</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:sticky lg:top-32 space-y-10">
                    <div class="bg-slate-900 p-12 rounded-[4rem] shadow-3xl relative overflow-hidden text-white animate-float border border-slate-800">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600 rounded-full -mr-32 -mt-32 opacity-20 blur-[80px]"></div>
                        
                        <div class="relative z-10 space-y-10">
                            <div>
                                <div class="w-12 h-1 bg-blue-500 rounded-full mb-6"></div>
                                <h2 class="text-4xl font-black mb-6 tracking-tighter leading-none">The Visionary Space</h2>
                                <div class="text-slate-400 text-base leading-relaxed opacity-90 font-medium">
                                    {!! $project->deskripsi !!}
                                </div>
                            </div>

                            <div class="pt-10 border-t border-white/10">
                                <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.3em] mb-8">Priority Assistance</p>
                                <a href="https://wa.me/6281310619585" target="_blank" class="flex items-center gap-6 p-6 rounded-[2.5rem] bg-white/5 border border-white/10 hover:bg-emerald-600 hover:border-emerald-500 transition-all group shadow-2xl">
                                    <div class="h-16 w-16 bg-emerald-500 rounded-3xl flex items-center justify-center text-white text-3xl shadow-lg shadow-emerald-500/40 group-hover:rotate-[360deg] transition-all duration-700">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1 group-hover:text-emerald-100 transition-colors">Konsultasi Live</p>
                                        <p class="text-xl font-black tracking-tight group-hover:text-white transition-colors">Hubungi Sales</p>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="space-y-5 pt-4">
                                @foreach(['Eco-friendly Smart Living', '24/7 Elite Security System', 'Strategic Golden Area'] as $benefit)
                                <div class="flex items-center gap-4 text-xs font-bold text-slate-300">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.8)]"></div>
                                    {{ $benefit }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Small Map/Location Card Preview --}}
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 group cursor-pointer overflow-hidden relative">
                        <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-5 transition-opacity"></div>
                        <div class="flex items-center gap-5 relative z-10">
                            <div class="h-14 w-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                <i class="bi bi-geo-fill"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-800 tracking-tight">Cek Lokasi</h4>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-tighter">Buka di Google Maps <i class="bi bi-arrow-up-right ms-1 text-[10px]"></i></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>