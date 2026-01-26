@extends('layout.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Manajemen Tipe Rumah</h2>
            <p class="text-gray-500 text-sm italic">Daftar spesifikasi unit berdasarkan proyek perumahan</p>
        </div>
        <a href="{{ route('admin.tipe.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Tipe Baru
        </a>
    </div>

    {{-- Grid Tipe Rumah --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
        @forelse($tipes as $tipe)
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl transition-all duration-500">
            
            {{-- Foto Unit --}}
            <div class="h-64 overflow-hidden relative bg-gray-100">
                @if($tipe->gambar)
                    {{-- Perbaikan: Memastikan path gambar benar --}}
                    <img src="{{ asset('storage/' . $tipe->gambar) }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700" 
                         alt="Foto Tipe"
                         onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=Gambar+Tidak+Ditemukan';">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-5xl"></i>
                    </div>
                @endif
                
                {{-- Badge Nama Proyek --}}
                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-4 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest text-blue-600 shadow-sm border border-blue-50">
                    {{ $tipe->project->nama_proyek ?? 'Tanpa Proyek' }}
                </div>

                {{-- Group Tombol Aksi Overlay --}}
                <div class="absolute top-4 right-4 flex gap-2">
                    <a href="{{ route('admin.tipe.edit', $tipe->id) }}" class="bg-white/90 backdrop-blur p-3 rounded-xl text-blue-600 shadow-sm hover:bg-blue-600 hover:text-white transition-all">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button type="button" onclick="btnDelete('{{ $tipe->id }}', '{{ $tipe->nama_tipe }}')" class="bg-white/90 backdrop-blur p-3 rounded-xl text-red-600 shadow-sm hover:bg-red-600 hover:text-white transition-all">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <form id="form-delete-{{ $tipe->id }}" action="{{ route('admin.tipe.destroy', $tipe->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            {{-- Info Detail --}}
            <div class="p-8">
                <div class="mb-4">
                    <div class="flex justify-between items-start">
                        <h3 class="text-2xl font-black text-gray-800 leading-tight">{{ $tipe->nama_tipe }}</h3>
                        <span class="text-[10px] bg-gray-100 px-2 py-1 rounded text-gray-500 font-bold uppercase">Unit Aktif</span>
                    </div>
                    <p class="text-blue-600 font-bold text-xl mt-2 italic">Rp {{ number_format($tipe->harga, 0, ',', '.') }}</p>
                </div>

                {{-- Spesifikasi Unit --}}
                <div class="grid grid-cols-2 gap-y-4 border-t border-gray-100 pt-6 mt-6">
                    <div class="flex items-center text-gray-600 text-sm font-medium">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3">
                            <i class="fas fa-expand-arrows-alt text-gray-400 text-xs"></i>
                        </div>
                        LT: {{ $tipe->luas_tanah }}m²
                    </div>
                    <div class="flex items-center text-gray-600 text-sm font-medium">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3">
                            <i class="fas fa-home text-gray-400 text-xs"></i>
                        </div>
                        LB: {{ $tipe->luas_bangunan }}m²
                    </div>
                    <div class="flex items-center text-gray-600 text-sm font-medium">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3">
                            <i class="fas fa-bed text-gray-400 text-xs"></i>
                        </div>
                        {{ $tipe->kamar_tidur }} Kamar
                    </div>
                    <div class="flex items-center text-gray-600 text-sm font-medium">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3">
                            <i class="fas fa-bath text-gray-400 text-xs"></i>
                        </div>
                        {{ $tipe->kamar_mandi }} Mandi
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[2rem] p-20 text-center border-4 border-dashed border-gray-100">
            <i class="fas fa-layer-group text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-400 uppercase tracking-widest">Belum ada tipe rumah</h3>
            <p class="text-gray-400 mt-2 text-sm italic">Silakan klik tombol "Tambah Tipe Baru" untuk memulai.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Script SweetAlert2 & Delete Logic --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function btnDelete(id, name) {
        Swal.fire({
            title: 'Hapus Tipe Rumah?',
            html: `Hapus <b>${name}</b>?<br><small class="text-red-500">Data unit yang terkait juga akan terhapus secara permanen.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '25px',
            background: '#ffffff',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        })
    }
</script>

@if(session('success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });
</script>
@endif
@endsection