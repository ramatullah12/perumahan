@extends('layout.admin')

@section('content')
<div class="p-6 md:p-8 bg-[#F8FAFC] min-h-screen">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Unit</h2>
            <p class="text-slate-500 text-sm font-medium">Monitoring ketersediaan unit dan pemetaan blok</p>
        </div>
        <button onclick="openModal()" class="bg-blue-600 text-white px-8 py-3.5 rounded-2xl font-black shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all hover:-translate-y-1 flex items-center group">
            <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-90"></i> Tambah Unit Baru
        </button>
    </div>

    {{-- Stats & Filter Box --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <div class="lg:col-span-3 bg-white p-4 rounded-[2rem] shadow-sm border border-slate-100 flex flex-wrap items-center gap-4">
            <form action="{{ route('admin.unit.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                <div class="flex items-center bg-slate-50 rounded-xl px-4 py-2 border border-slate-100">
                    <i class="fas fa-filter text-blue-500 mr-3 text-xs"></i>
                    <select name="project_id" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-black uppercase tracking-widest text-slate-600 outline-none cursor-pointer">
                        <option value="">Semua Proyek</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->nama_proyek }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center bg-slate-50 rounded-xl px-4 py-2 border border-slate-100">
                    <select name="status" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-black uppercase tracking-widest text-slate-600 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach(['Tersedia', 'Dibooking', 'Terjual'] as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('admin.unit.index') }}" class="text-slate-400 hover:text-blue-600 transition-colors">
                    <i class="fas fa-sync-alt text-sm"></i>
                </a>

                <div class="ml-auto pr-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Unit Terdata</p>
                    <p class="text-sm font-bold text-slate-700 text-right">{{ $units->count() }} Unit</p>
                </div>
            </form>
        </div>
        
        <div class="bg-blue-600 rounded-[2rem] p-4 flex items-center justify-between text-white shadow-lg shadow-blue-100">
            <div class="pl-4">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Available</p>
                <p class="text-2xl font-black">{{ $units->where('status', 'Tersedia')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-2">
                <i class="fas fa-check-double text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 uppercase text-[10px] font-black text-slate-400 tracking-[0.15em]">
                        <th class="p-6 text-center w-20">No</th>
                        <th class="p-6">Lokasi & Proyek</th>
                        <th class="p-6">Identitas Unit</th>
                        <th class="p-6">Tipe Rumah</th>
                        <th class="p-6 text-right">Harga Final</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6 text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($units as $index => $unit)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="p-6 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-6">
                            <p class="font-black text-slate-800 leading-tight">{{ $unit->project->nama_proyek }}</p>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">Lokasi Terverifikasi</p>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center">
                                <span class="bg-slate-100 text-slate-700 px-3 py-1.5 rounded-xl font-black text-xs border border-slate-200">
                                    {{ $unit->block }}-{{ $unit->no_unit }}
                                </span>
                            </div>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs">
                                    <i class="fas fa-home"></i>
                                </div>
                                <span class="font-bold text-slate-600">{{ $unit->tipe->nama_tipe }}</span>
                            </div>
                        </td>
                        <td class="p-6 text-right">
                            <p class="text-blue-600 font-black tracking-tight">Rp {{ number_format($unit->harga, 0, ',', '.') }}</p>
                        </td>
                        <td class="p-6 text-center">
                            @php
                                $statusClasses = [
                                    'Tersedia' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                    'Dibooking' => 'bg-amber-100 text-amber-600 border-amber-200',
                                    'Terjual' => 'bg-slate-100 text-slate-500 border-slate-200'
                                ];
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClasses[$unit->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $unit->status }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.unit.edit', $unit->id) }}" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-pencil-alt text-xs"></i>
                                </a>
                                <button onclick="deleteUnit({{ $unit->id }})" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                <form id="delete-form-{{ $unit->id }}" action="{{ route('admin.unit.destroy', $unit->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-6 border border-slate-100">
                                    <i class="fas fa-box-open text-4xl text-slate-200"></i>
                                </div>
                                <p class="text-slate-400 uppercase tracking-[0.3em] text-[10px] font-black">Data unit kosong</p>
                                <button onclick="openModal()" class="mt-4 text-blue-600 text-xs font-bold hover:underline">Tambah unit pertama sekarang</button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH UNIT --}}
<div id="modalUnit" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] w-full max-w-xl overflow-hidden shadow-2xl transition-all duration-300 scale-90 opacity-0" id="modalContent">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter">Registrasi Unit</h3>
                <p class="text-xs text-slate-400 font-medium">Lengkapi rincian properti di bawah ini</p>
            </div>
            <button onclick="closeModal()" class="w-10 h-10 bg-slate-50 rounded-full text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.unit.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Proyek Strategis</label>
                    <div class="relative">
                        <select name="project_id" id="project_select" required class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer appearance-none shadow-inner">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->nama_proyek }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Varian Tipe Rumah</label>
                    <div class="relative">
                        <select name="tipe_id" id="tipe_select" required class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none cursor-not-allowed appearance-none shadow-inner" disabled>
                            <option value="">Pilih Proyek Dahulu</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Blok</label>
                    <input type="text" name="block" required placeholder="A / B" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">No. Unit</label>
                    <input type="text" name="no_unit" required placeholder="01" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
                </div>

                <div class="col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Penyesuaian Harga (Opsional)</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-bold text-xs group-focus-within:text-blue-500 transition-colors">Rp</span>
                        <input type="number" name="harga" required min="0" placeholder="0" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-sm font-black text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block ml-1 text-center">Set Status Unit</label>
                    <div class="flex gap-3">
                        <label class="flex-1 group">
                            <input type="radio" name="status" value="Tersedia" class="hidden peer" checked>
                            <div class="p-4 text-center rounded-2xl bg-slate-50 text-[10px] font-black text-slate-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-emerald-100 cursor-pointer transition-all uppercase tracking-widest border border-slate-100">
                                Tersedia
                            </div>
                        </label>
                        <label class="flex-1 group">
                            <input type="radio" name="status" value="Dibooking" class="hidden peer">
                            <div class="p-4 text-center rounded-2xl bg-slate-50 text-[10px] font-black text-slate-400 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-100 cursor-pointer transition-all uppercase tracking-widest border border-slate-100">
                                Dibooking
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white p-5 rounded-[1.5rem] font-black shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 uppercase tracking-widest text-[10px]">
                    <i class="fas fa-save mr-2"></i> Daftarkan Unit
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModal() { 
        const modal = $('#modalUnit');
        const content = $('#modalContent');
        modal.removeClass('hidden').addClass('flex');
        setTimeout(() => { 
            content.removeClass('scale-90 opacity-0').addClass('scale-100 opacity-100'); 
        }, 10);
    }

    function closeModal() { 
        const modal = $('#modalUnit');
        const content = $('#modalContent');
        content.removeClass('scale-100 opacity-100').addClass('scale-90 opacity-0');
        setTimeout(() => { 
            modal.addClass('hidden').removeClass('flex'); 
        }, 200);
    }

    $('#project_select').on('change', function() {
        let projectId = $(this).val();
        let tipeSelect = $('#tipe_select');
        
        if (projectId) {
            tipeSelect.prop('disabled', false).removeClass('cursor-not-allowed bg-slate-100').empty().append('<option value="">Memuat Tipe...</option>');
            $.ajax({
                url: '/admin/get-tipe/' + projectId,
                type: 'GET',
                success: function(data) {
                    tipeSelect.empty().append('<option value="">-- Pilih Tipe --</option>');
                    $.each(data, function(k, v) {
                        tipeSelect.append('<option value="' + v.id + '">Tipe ' + v.nama_tipe + '</option>');
                    });
                }
            });
        } else {
            tipeSelect.prop('disabled', true).addClass('cursor-not-allowed bg-slate-100').empty().append('<option value="">Pilih Proyek Dahulu</option>');
        }
    });

    function deleteUnit(id) {
        Swal.fire({
            title: '<span class="text-2xl font-black uppercase tracking-tighter">Hapus Unit?</span>',
            html: "Data unit yang dihapus tidak dapat dipulihkan kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-bold uppercase py-3 px-6',
                cancelButton: 'rounded-xl font-bold uppercase py-3 px-6'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection