@extends('dashboard.customer')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen animate-fade-in">
    {{-- Header Notifikasi --}}
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Notifikasi</h2>
        <p class="text-slate-500 font-medium mt-1">Update terbaru tentang booking dan progres pembangunan</p>
    </div>

    <div class="space-y-6 max-w-5xl">
        @forelse($notifications as $notif)
            {{-- Card Notifikasi dengan Border dinamis sesuai tipe --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 {{ $notif->type == 'booking' ? 'border-green-500' : 'border-blue-500' }} flex gap-6 items-start relative group transition-all hover:shadow-md">
                
                {{-- Icon Notifikasi Dinamis --}}
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $notif->type == 'booking' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600' }}">
                    <i class="fas {{ $notif->type == 'booking' ? 'fa-check-circle' : 'fa-chart-line' }} text-xl"></i>
                </div>

                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">{{ $notif->title }}</h3>
                        
                        {{-- Badge "Baru" untuk pesan yang belum dibaca --}}
                        @if(!$notif->is_read)
                            <span class="bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-sm shadow-blue-200">Baru</span>
                        @endif
                    </div>
                    
                    <p class="text-slate-500 leading-relaxed mb-3">
                        {{ $notif->message }}
                    </p>

                    {{-- Format Waktu Presisi (Lengkap dengan Hari dan Jam) --}}
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center">
                        <i class="far fa-clock mr-2"></i> 
                        {{-- Carbon perlu dipastikan menggunakan locale ID untuk format hari Indonesia --}}
                        {{ \Carbon\Carbon::parse($notif->created_at)->translatedFormat('l, d F Y') }} pukul {{ \Carbon\Carbon::parse($notif->created_at)->format('H.i') }}
                    </span>
                </div>
            </div>
        @empty
            {{-- Empty State jika data kosong --}}
            <div class="py-24 text-center bg-white rounded-[2.5rem] border-4 border-dashed border-slate-100">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                    <i class="fas fa-bell-slash text-3xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800 uppercase tracking-tight">Belum Ada Notifikasi</h4>
                <p class="text-slate-400 mt-2 max-w-xs mx-auto">Kami akan memberitahu Anda segera setelah ada update mengenai unit properti Anda.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes fade-in { 
        from { opacity: 0; transform: translateY(10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .animate-fade-in { animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection