@extends('layout.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Proyek</h2>
            <p class="text-gray-500 text-sm mt-1 flex items-center">
                <i class="fas fa-layer-group mr-2"></i> Kelola ketersediaan unit dan status proyek perumahan
            </p>
        </div>
        <a href="{{ route('admin.project.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Proyek
        </a>
    </div>

    {{-- Daftar Proyek --}}
    <div class="space-y-6">
        @forelse($projects as $project)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-xl hover:-translate-y-1 relative group">
            <div class="flex flex-col md:flex-row gap-8">
                
                {{-- Foto Proyek --}}
                <div class="w-full md:w-80 h-52 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100 border border-gray-100 shadow-inner relative">
                    @if($project->gambar)
                        <img src="{{ asset('storage/' . $project->gambar) }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                             alt="{{ $project->nama_proyek }}">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                            <i class="fas fa-house-damage text-4xl mb-2"></i>
                            <span class="text-xs font-semibold">Gambar Belum Tersedia</span>
                        </div>
                    @endif
                    
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm border
                            {{ $project->status == 'Sedang Berjalan' ? 'bg-blue-600 text-white border-blue-400' : 'bg-green-600 text-white border-green-400' }}">
                            {{ $project->status }}
                        </span>
                    </div>
                </div>

                {{-- Detail Proyek --}}
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-black text-gray-800 group-hover:text-blue-600 transition-colors">{{ $project->nama_proyek }}</h3>
                            <p class="text-gray-500 font-medium flex items-center mt-1">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> {{ $project->lokasi }}
                            </p>
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div class="flex gap-2">
                            <a href="{{ route('admin.project.edit', $project->id) }}" 
                               class="bg-blue-50 text-blue-600 w-11 h-11 flex items-center justify-center rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                               title="Edit Proyek">
                                <i class="fas fa-pencil-alt text-sm"></i>
                            </a>

                            <button type="button" 
                                    onclick="btnDeleteProject('{{ $project->id }}', '{{ $project->nama_proyek }}')"
                                    class="bg-red-50 text-red-600 w-11 h-11 flex items-center justify-center rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm"
                                    title="Hapus Proyek">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>

                            <form id="form-delete-project-{{ $project->id }}" action="{{ route('admin.project.destroy', $project->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-6 line-clamp-2 leading-relaxed italic border-l-4 border-gray-100 pl-4">
                        "{{ $project->deskripsi ?? 'Proyek ini belum memiliki deskripsi detail.' }}"
                    </p>

                    {{-- PERBAIKAN: Statistik Unit (Menggunakan variabel otomatis dari Controller) --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-3 rounded-2xl text-center border border-gray-100 shadow-sm">
                            <p class="text-[10px] uppercase tracking-tighter font-black text-gray-400 mb-1">Target Kapasitas</p>
                            <p class="text-xl font-black text-gray-800">{{ number_format($project->total_unit) }}</p>
                        </div>
                        <div class="bg-green-50/50 p-3 rounded-2xl text-center border border-green-100 shadow-sm">
                            <p class="text-[10px] uppercase tracking-tighter font-black text-green-600 mb-1">Tersedia Riil</p>
                            {{-- Mengambil data riil dari unit dengan status 'Tersedia' --}}
                            <p class="text-xl font-black text-green-700">{{ number_format($project->tersedia) }}</p>
                        </div>
                        <div class="bg-orange-50/50 p-3 rounded-2xl text-center border border-orange-100 shadow-sm">
                            <p class="text-[10px] uppercase tracking-tighter font-black text-orange-600 mb-1">Booked</p>
                            {{-- Mengambil data riil dari unit dengan status 'Dibooking' --}}
                            <p class="text-xl font-black text-orange-700">{{ number_format($project->booked) }}</p>
                        </div>
                        <div class="bg-blue-50/50 p-3 rounded-2xl text-center border border-blue-100 shadow-sm">
                            <p class="text-[10px] uppercase tracking-tighter font-black text-blue-600 mb-1">Terjual</p>
                            {{-- Mengambil data riil dari unit dengan status 'Terjual' --}}
                            <p class="text-xl font-black text-blue-700">{{ number_format($project->terjual) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-50">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center">
                            <i class="far fa-clock mr-1.5"></i> Update {{ $project->updated_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('admin.unit.index', ['project_id' => $project->id]) }}" class="text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 transition-colors">
                            Lihat Detail Unit <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-gray-200">
            <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                <i class="fas fa-building text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-800">Tidak Ada Proyek</h3>
            <p class="text-gray-500 mb-8 max-w-sm mx-auto">Anda belum menambahkan proyek perumahan apapun ke dalam sistem.</p>
            <a href="{{ route('admin.project.create') }}" class="inline-flex items-center bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                + Tambah Sekarang
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- Script SweetAlert2 & Toast --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function btnDeleteProject(id, name) {
        Swal.fire({
            title: 'Hapus Proyek?',
            html: `Apakah Anda yakin ingin menghapus proyek <b>${name}</b>?<br><small class="text-red-500">Peringatan: Seluruh data unit dan booking di proyek ini akan terhapus permanen!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-project-' + id).submit();
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