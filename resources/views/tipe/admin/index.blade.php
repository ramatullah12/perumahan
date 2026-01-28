@extends('layout.admin')

@section('content')
<div class="p-6 bg-[#F8FAFC] min-h-screen">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Tipe Rumah</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                <p class="text-slate-500 text-sm font-medium">Spesifikasi unit & katalog harga properti</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            {{-- Quick Stats Glance --}}
            <div class="hidden lg:flex items-center bg-white px-5 py-2 rounded-2xl shadow-sm border border-slate-100 mr-2">
                <div class="text-right mr-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Koleksi</p>
                    <p class="text-sm font-bold text-slate-700">{{ $tipes->count() }} Tipe</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100 mx-2"></div>
                <i class="fas fa-th-large text-blue-100 text-xl"></i>
            </div>
            
            <a href="{{ route('admin.tipe.create') }}" class="bg-blue-600 text-white px-8 py-3.5 rounded-[1.25rem] font-black shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all flex items-center group">
                <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-90"></i> Tambah Tipe Baru
            </a>
        </div>
    </div>

    {{-- Grid Tipe Rumah --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-10">
        @forelse($tipes as $tipe)
        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden group hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 flex flex-col">
            
            {{-- Foto Unit dengan Overlay Gradient --}}
            <div class="h-72 overflow-hidden relative bg-slate-100">
                @if($tipe->gambar)
                    <img src="{{ asset('storage/' . $tipe->gambar) }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-1000" 
                         alt="{{ $tipe->nama_tipe }}"
                         onerror="this.onerror=null;this.src='https://placehold.co/800x600?text=Image+Not+Found';">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                        <i class="fas fa-mountain-city text-6xl mb-3 opacity-20"></i>
                        <span class="text-[10px] font-black uppercase tracking-tighter">Belum ada foto</span>
                    </div>
                @endif
                
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

                {{-- Project Tag --}}
                <div class="absolute top-6 left-6 flex items-center gap-2">
                    <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/30">
                        <p class="text-[9px] font-black text-white uppercase tracking-widest leading-none mb-1">Proyek Lokasi</p>
                        <p class="text-xs font-bold text-white">{{ $tipe->project->nama_proyek ?? 'Unassigned' }}</p>
                    </div>
                </div>

                {{-- Floating Action Menu --}}
                <div class="absolute top-6 right-6 flex flex-col gap-2 translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                    <a href="{{ route('admin.tipe.edit', $tipe->id) }}" class="bg-white p-3.5 rounded-2xl text-slate-600 shadow-xl hover:bg-blue-600 hover:text-white transition-all transform hover:scale-110">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" onclick="btnDelete('{{ $tipe->id }}', '{{ $tipe->nama_tipe }}')" class="bg-white p-3.5 rounded-2xl text-rose-500 shadow-xl hover:bg-rose-500 hover:text-white transition-all transform hover:scale-110">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                {{-- Price Label --}}
                <div class="absolute bottom-6 left-8">
                    <p class="text-white/80 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Mulai Dari</p>
                    <h4 class="text-white text-3xl font-black tracking-tight">Rp {{ number_format($tipe->harga, 0, ',', '.') }}</h4>
                </div>
            </div>

            {{-- Detail Section --}}
            <div class="p-10">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $tipe->nama_tipe }}</h3>
                        <p class="text-slate-400 text-xs font-medium mt-1">Spesifikasi Standar Properti</p>
                    </div>
                    <div class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                        Ready to Build
                    </div>
                </div>

                {{-- Specs Grid dengan Ikon Lebih Bold --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center group/item hover:bg-white hover:border-blue-200 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mr-4 text-blue-500 group-hover/item:bg-blue-600 group-hover/item:text-white transition-all">
                            <i class="fas fa-vector-square text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Luas Tanah</p>
                            <p class="text-sm font-bold text-slate-700">{{ $tipe->luas_tanah }} m²</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center group/item hover:bg-white hover:border-blue-200 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mr-4 text-emerald-500 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-all">
                            <i class="fas fa-border-all text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Bangunan</p>
                            <p class="text-sm font-bold text-slate-700">{{ $tipe->luas_bangunan }} m²</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center group/item hover:bg-white hover:border-blue-200 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mr-4 text-orange-500 group-hover/item:bg-orange-500 group-hover/item:text-white transition-all">
                            <i class="fas fa-bed text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kamar Tidur</p>
                            <p class="text-sm font-bold text-slate-700">{{ $tipe->kamar_tidur }} Kamar</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center group/item hover:bg-white hover:border-blue-200 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mr-4 text-cyan-500 group-hover/item:bg-cyan-500 group-hover/item:text-white transition-all">
                            <i class="fas fa-shower text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kamar Mandi</p>
                            <p class="text-sm font-bold text-slate-700">{{ $tipe->kamar_mandi }} Mandi</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form id="form-delete-{{ $tipe->id }}" action="{{ route('admin.tipe.destroy', $tipe->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
        @empty
        <div class="col-span-full py-32 flex flex-col items-center justify-center bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-layer-group text-4xl text-slate-200"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-widest">Katalog Kosong</h3>
            <p class="text-slate-400 mt-2 font-medium">Anda belum menambahkan tipe rumah apapun untuk dipasarkan.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- SweetAlert2 & Logic --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function btnDelete(id, name) {
        Swal.fire({
            title: '<span class="font-black text-2xl uppercase tracking-tighter">Hapus Tipe Properti?</span>',
            html: `Apakah Anda yakin ingin menghapus tipe <b>${name}</b>?<br><p class="mt-2 text-sm text-rose-500 font-bold">Tindakan ini permanen dan akan menghapus unit terkait!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            padding: '2rem',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-bold uppercase tracking-widest py-3 px-6',
                cancelButton: 'rounded-xl font-bold uppercase tracking-widest py-3 px-6'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        })
    }
</script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#f0fdf4',
        iconColor: '#22c55e',
    });
</script>
@endif
@endsection