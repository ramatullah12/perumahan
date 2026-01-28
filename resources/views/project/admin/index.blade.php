@extends('layout.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Proyek</h2>
            <p class="text-gray-500 text-sm mt-1 flex items-center font-medium">
                <span class="flex h-2 w-2 rounded-full bg-blue-500 mr-2"></span>
                Kelola ketersediaan unit dan status proyek perumahan secara real-time
            </p>
        </div>
        <a href="{{ route('admin.project.create') }}" class="group bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center">
            <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform"></i> Tambah Proyek Baru
        </a>
    </div>

    {{-- Daftar Proyek --}}
    <div class="grid grid-cols-1 gap-8">
        @forelse($projects as $project)
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-2 transition-all hover:shadow-2xl hover:shadow-gray-200/50 group">
            <div class="flex flex-col lg:flex-row gap-6 p-4">
                
                {{-- Foto Proyek dengan Overlay --}}
                <div class="w-full lg:w-96 h-64 rounded-[2rem] overflow-hidden flex-shrink-0 bg-gray-100 border border-gray-50 shadow-inner relative">
                    @if($project->gambar)
                        <img src="{{ asset('storage/' . $project->gambar) }}" 
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" 
                             alt="{{ $project->nama_proyek }}">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                            <i class="fas fa-image text-5xl mb-3"></i>
                            <span class="text-xs font-bold tracking-widest uppercase">No Preview Image</span>
                        </div>
                    @endif
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-4 left-4">
                        @php
                            $statusClasses = [
                                'Sedang Berjalan' => 'bg-blue-600 text-white border-blue-400',
                                'Selesai' => 'bg-emerald-600 text-white border-emerald-400',
                                'Pre-Launch' => 'bg-amber-500 text-white border-amber-300'
                            ];
                            $currentStatus = $project->status ?? 'Sedang Berjalan';
                        @endphp
                        <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] shadow-lg backdrop-blur-md border {{ $statusClasses[$currentStatus] ?? 'bg-gray-600 text-white' }}">
                            {{ $currentStatus }}
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>

                {{-- Detail Proyek --}}
                <div class="flex-1 flex flex-col py-2 px-2">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-2xl font-black text-gray-800 group-hover:text-blue-600 transition-colors leading-tight">{{ $project->nama_proyek }}</h3>
                            <p class="text-gray-500 font-bold flex items-center mt-1 text-sm">
                                <i class="fas fa-map-marker-alt mr-2 text-rose-500"></i> {{ $project->lokasi }}
                            </p>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('admin.project.edit', $project->id) }}" 
                               class="bg-blue-50 text-blue-600 w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-blue-600 hover:text-white transition-all shadow-sm active:scale-90"
                               title="Edit Proyek">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    onclick="btnDeleteProject('{{ $project->id }}', '{{ $project->nama_proyek }}')"
                                    class="bg-rose-50 text-rose-600 w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-rose-600 hover:text-white transition-all shadow-sm active:scale-90"
                                    title="Hapus Proyek">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>

                    <p class="text-gray-500 text-sm mb-6 line-clamp-2 leading-relaxed font-medium italic">
                        {{ $project->deskripsi ?? 'Informasi detail mengenai proyek perumahan ini belum diperbarui oleh tim lapangan.' }}
                    </p>

                    {{-- Progress Bar Kapasitas --}}
                    @php
                        $total = $project->total_unit > 0 ? $project->total_unit : 1;
                        $sold_booked = $project->booked + $project->terjual;
                        $percent = ($sold_booked / $total) * 100;
                    @endphp
                    <div class="mb-6">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Okupansi Proyek</span>
                            <span class="text-xs font-black text-blue-600">{{ round($percent) }}% Terisi</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(37,99,235,0.3)]" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    {{-- Statistik Unit --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-auto">
                        <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100 transition-colors group-hover:bg-white">
                            <p class="text-[9px] uppercase font-black text-gray-400 mb-1">Total Unit</p>
                            <p class="text-lg font-black text-gray-800">{{ number_format($project->total_unit) }}</p>
                        </div>
                        <div class="bg-emerald-50/50 p-3 rounded-2xl border border-emerald-100 transition-colors group-hover:bg-emerald-50">
                            <p class="text-[9px] uppercase font-black text-emerald-600 mb-1">Tersedia</p>
                            <p class="text-lg font-black text-emerald-700">{{ number_format($project->tersedia) }}</p>
                        </div>
                        <div class="bg-orange-50/50 p-3 rounded-2xl border border-orange-100 transition-colors group-hover:bg-orange-50">
                            <p class="text-[9px] uppercase font-black text-orange-600 mb-1">Booked</p>
                            <p class="text-lg font-black text-orange-700">{{ number_format($project->booked) }}</p>
                        </div>
                        <div class="bg-blue-50/50 p-3 rounded-2xl border border-blue-100 transition-colors group-hover:bg-blue-50">
                            <p class="text-[9px] uppercase font-black text-blue-600 mb-1">Terjual</p>
                            <p class="text-lg font-black text-blue-700">{{ number_format($project->terjual) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-50">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center">
                            <i class="far fa-clock mr-1.5 text-blue-500"></i> Terakhir diubah {{ $project->updated_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('admin.unit.index', ['project_id' => $project->id]) }}" 
                           class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 transition-all hover:gap-3">
                            Kelola Detail Unit <i class="fas fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[3rem] p-24 text-center border-2 border-dashed border-gray-200 shadow-inner">
            <div class="bg-gray-50 w-28 h-28 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-200">
                <i class="fas fa-city text-5xl"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-800 mb-2">Belum Ada Proyek</h3>
            <p class="text-gray-500 mb-10 max-w-md mx-auto font-medium">Sistem tidak menemukan data proyek. Mulai bangun portofolio Anda dengan menambahkan proyek perumahan pertama.</p>
            <a href="{{ route('admin.project.create') }}" class="inline-flex items-center bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i> Tambah Proyek Sekarang
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- SweetAlert2 Logic Tetap Sama --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function btnDeleteProject(id, name) {
        Swal.fire({
            title: 'Hapus Proyek?',
            html: `<div class="p-2 text-gray-600">Apakah Anda yakin ingin menghapus proyek <b class="text-gray-900">${name}</b>?<br><div class="mt-3 p-3 bg-rose-50 rounded-xl border border-rose-100 text-[11px] text-rose-600 font-bold uppercase tracking-wider"><i class="fas fa-exclamation-triangle mr-1"></i> Data unit & booking akan ikut terhapus permanen!</div></div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batalkan',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl',
                confirmButton: 'rounded-xl px-6 py-3 font-bold text-sm',
                cancelButton: 'rounded-xl px-6 py-3 font-bold text-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-project-' + id).submit();
            }
        })
    }
</script>
@endsection