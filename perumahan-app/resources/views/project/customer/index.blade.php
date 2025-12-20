@extends('dashboard.customer')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    {{-- Header Section --}}
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 tracking-tighter leading-none mb-3">
            Jelajahi Proyek Perumahan
        </h2>
        <p class="text-slate-500 font-medium">Temukan unit rumah impian Anda dengan fasilitas terbaik</p>
    </div>

    {{-- Grid Kartu Proyek --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($units as $unit)
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100 group transition-all duration-500 hover:-translate-y-2">
                
                {{-- Gambar Unit --}}
                <div class="h-60 bg-slate-200 relative overflow-hidden">
                    @if($unit->tipe && $unit->tipe->foto)
                        <img src="{{ asset('storage/' . $unit->tipe->foto) }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                             alt="{{ $unit->project->nama_proyek }}">
                    @else
                        <div class="flex items-center justify-center h-full text-slate-400 bg-slate-100">
                            <i class="fas fa-image text-5xl"></i>
                        </div>
                    @endif
                    
                    {{-- Badge Status --}}
                    <div class="absolute top-6 right-6 bg-blue-600 text-white px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg">
                        Tersedia
                    </div>
                </div>

                {{-- Detail Konten --}}
                <div class="p-8">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $unit->project->nama_proyek ?? 'Nama Proyek' }}
                        </h3>
                        <div class="flex items-center text-slate-400 font-bold text-sm">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                            {{ $unit->project->lokasi ?? 'Lokasi Proyek' }}
                        </div>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2 italic">
                        Nikmati hunian eksklusif tipe {{ $unit->tipe->nama_tipe }} dengan konsep modern dan lingkungan asri untuk keluarga tercinta.
                    </p>

                    {{-- Info Harga & Tombol --}}
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Mulai</p>
                            <p class="text-xl font-black text-blue-600 tracking-tight">
                                Rp {{ number_format($unit->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <a href="{{ route('customer.booking.create', ['unit_id' => $unit->id]) }}" 
                           class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-blue-600 hover:rotate-12 transition-all duration-300">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- Tampilan Jika Kosong --}}
            <div class="col-span-full py-20 bg-white rounded-[3rem] border-4 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-house-chimney-crack text-4xl text-slate-200"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800 uppercase tracking-tight">Belum Ada Proyek Aktif</h4>
                <p class="text-slate-400 mt-2 max-w-xs mx-auto">Saat ini belum ada unit tersedia yang dapat dipesan. Silakan cek kembali nanti.</p>
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