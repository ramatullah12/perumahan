@extends('dashboard.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Update Progres Pembangunan</h2>
        <p class="text-gray-500 italic text-sm mt-1">Kelola progres pembangunan untuk setiap unit</p>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl flex justify-between items-center shadow-sm animate-fade-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Grid Progres Unit --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($units as $unit)
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 hover:shadow-xl transition-all group">
            <div class="mb-6">
                {{-- Nama Proyek --}}
                <h3 class="text-xl font-black text-gray-800 group-hover:text-blue-600 transition-colors">
                    {{ $unit->project->nama_proyek }}
                </h3>
                {{-- Nomor Unit --}}
                <p class="text-gray-500 font-bold">Unit {{ $unit->block }}{{ $unit->no_unit }}</p>
                {{-- Nama Pembeli (Relasi ke Booking) --}}
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-300 mt-1 italic">
                    Pembeli: {{ $unit->booking->nama_pembeli ?? 'Belum Ada Pembeli' }}
                </p>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-end">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Progres Saat Ini</span>
                    <span class="text-blue-600 font-black text-xl leading-none">
                        {{ $unit->latestProgres->persentase ?? 0 }}%
                    </span>
                </div>
                
                {{-- Progress Bar Dinamis --}}
                <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden shadow-inner">
                    <div class="bg-blue-600 h-full transition-all duration-1000 ease-out" 
                         style="width: {{ $unit->latestProgres->persentase ?? 0 }}%">
                    </div>
                </div>

                {{-- Tahapan Pengerjaan --}}
                <div class="flex items-center text-xs font-bold text-gray-400 italic">
                    <span class="mr-2">Tahap:</span>
                    <span class="text-gray-700">{{ $unit->latestProgres->tahap ?? 'Persiapan Lahan' }}</span>
                </div>

                {{-- Tombol Kelola Progres --}}
                <button onclick="openModalUpdate('{{ $unit->id }}', '{{ $unit->project->nama_proyek }}', '{{ $unit->block }}{{ $unit->no_unit }}')" 
                        class="w-full mt-4 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center">
                    Kelola Progres
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-gray-200">
            <i class="fas fa-hammer text-6xl text-gray-100 mb-4"></i>
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Tidak ada unit yang sedang dibangun</p>
            <p class="text-gray-300 text-[10px] mt-2 italic">Hanya unit dengan status 'Dibooking' atau 'Terjual' yang muncul di sini.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL UPDATE PROGRES --}}
<div id="modalProgres" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl animate-fade-in">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Update Progres Unit</h3>
                <p id="modal-unit-info" class="text-xs font-bold text-blue-600 mt-1"></p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-800 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.progres.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="unit_id" id="unit_id_input">
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Persentase Pembangunan (%)</label>
                <div class="relative">
                    <input type="number" name="persentase" min="0" max="100" required 
                           class="w-full bg-gray-50 border-none rounded-2xl p-4 font-black text-blue-600 outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                           placeholder="0 - 100">
                    <span class="absolute right-4 top-4 font-black text-gray-300">%</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Tahap Saat Ini</label>
                <input type="text" name="tahap" placeholder="Contoh: Pemasangan Bata / Atap" required 
                       class="w-full bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-600 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3" placeholder="Opsional..." 
                          class="w-full bg-gray-50 border-none rounded-2xl p-4 font-medium text-gray-600 outline-none focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Foto Dokumentasi</label>
                <div class="bg-gray-50 p-4 rounded-2xl border-2 border-dashed border-gray-200">
                    <input type="file" name="foto" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white p-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                    Simpan Update
                </button>
                <button type="button" onclick="closeModal()" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold hover:bg-gray-200 transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function openModalUpdate(unitId, projectName, unitName) {
        document.getElementById('unit_id_input').value = unitId;
        document.getElementById('modal-unit-info').innerText = projectName + ' - Unit ' + unitName;
        document.getElementById('modalProgres').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scroll
    }

    function closeModal() {
        document.getElementById('modalProgres').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Enable scroll
    }
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
</style>
@endsection