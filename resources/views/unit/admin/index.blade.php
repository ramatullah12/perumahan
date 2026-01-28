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
                    <select name="project_id" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-black uppercase tracking-widest text-slate-600 outline-none cursor-pointer focus:ring-0">
                        <option value="">Semua Proyek</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->nama_proyek }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center bg-slate-50 rounded-xl px-4 py-2 border border-slate-100">
                    <select name="status" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-black uppercase tracking-widest text-slate-600 outline-none cursor-pointer focus:ring-0">
                        <option value="">Semua Status</option>
                        @foreach(['Tersedia', 'Dibooking', 'Terjual'] as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('admin.unit.index') }}" class="text-slate-300 hover:text-blue-600 transition-colors ml-2" title="Reset Filter">
                    <i class="fas fa-sync-alt text-sm"></i>
                </a>

                <div class="ml-auto pr-4 hidden md:block border-l border-slate-100 pl-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Terfilter</p>
                    <p class="text-sm font-bold text-slate-700">{{ $units->count() }} Unit</p>
                </div>
            </form>
        </div>
        
        <div class="bg-blue-600 rounded-[2rem] p-4 flex items-center justify-between text-white shadow-lg shadow-blue-100 relative overflow-hidden group">
            <i class="fas fa-home absolute -right-4 -bottom-4 text-7xl opacity-10 group-hover:scale-110 transition-transform"></i>
            <div class="pl-4 relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Unit Tersedia</p>
                <p class="text-2xl font-black">{{ $units->where('status', 'Tersedia')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-2 relative z-10">
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
                        <th class="p-6">Proyek & Lokasi</th>
                        <th class="p-6">Identitas</th>
                        <th class="p-6">Tipe Rumah</th>
                        <th class="p-6 text-right">Harga Final</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6 text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($units as $index => $unit)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="p-6 text-center font-bold text-slate-300">{{ $index + 1 }}</td>
                        <td class="p-6">
                            <p class="font-black text-slate-800 leading-tight">{{ $unit->project->nama_proyek }}</p>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-map-marker-alt text-[8px] text-blue-400 mr-1.5"></i>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Lokasi Proyek</span>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="bg-slate-100 text-slate-700 px-3 py-1.5 rounded-xl font-black text-xs border border-slate-200">
                                {{ $unit->block }}-{{ $unit->no_unit }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs shadow-sm">
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
                                    'Tersedia' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Dibooking' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Terjual' => 'bg-slate-100 text-slate-500 border-slate-200'
                                ];
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClasses[$unit->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $unit->status }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.unit.edit', $unit->id) }}" class="w-10 h-10 bg-white text-slate-400 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-slate-100">
                                    <i class="fas fa-pencil-alt text-xs"></i>
                                </a>
                                <button onclick="deleteUnit({{ $unit->id }})" class="w-10 h-10 bg-white text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-slate-100">
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
                                <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 border-2 border-dashed border-slate-200">
                                    <i class="fas fa-box-open text-4xl text-slate-200"></i>
                                </div>
                                <p class="text-slate-400 uppercase tracking-[0.3em] text-[10px] font-black">Data unit tidak ditemukan</p>
                                <button onclick="openModal()" class="mt-4 text-blue-600 text-xs font-bold hover:text-blue-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-1"></i> Daftarkan unit sekarang
                                </button>
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
<div id="modalUnit" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden z-[100] items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-[3rem] w-full max-w-xl shadow-2xl transition-all duration-300 scale-90 opacity-0 relative my-auto" id="modalContent">
        <div class="p-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter uppercase">Registrasi Unit</h3>
                <p class="text-[10px] text-blue-600 font-black uppercase tracking-[0.2em] mt-1">Sistem Manajemen Inventaris</p>
            </div>
            <button onclick="closeModal()" class="w-12 h-12 bg-white rounded-2xl text-slate-400 hover:text-rose-500 transition-all flex items-center justify-center shadow-sm border border-slate-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.unit.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Proyek Strategis</label>
                    <div class="relative group">
                        <select name="project_id" id="project_select_modal" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-0 focus:border-blue-500 outline-none cursor-pointer appearance-none shadow-inner transition-all">
                            <option value="">-- Cari Proyek --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->nama_proyek }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-xs group-focus-within:text-blue-500"></i>
                    </div>
                </div>

                <div class="col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Varian Tipe Rumah</label>
                    <div class="relative group">
                        <select name="tipe_id" id="tipe_select_modal" required class="w-full bg-slate-100 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-400 focus:ring-0 outline-none cursor-not-allowed appearance-none shadow-inner transition-all" disabled>
                            <option value="">Tentukan Proyek Dahulu</option>
                        </select>
                        <i class="fas fa-home absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Blok</label>
                    <input type="text" name="block" required placeholder="Contoh: A" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:border-blue-500 outline-none shadow-inner transition-all uppercase">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">No. Unit</label>
                    <input type="text" name="no_unit" required placeholder="Contoh: 01" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:border-blue-500 outline-none shadow-inner transition-all">
                </div>

                <div class="col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga Unit (IDR)</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-blue-500 font-black text-xs">Rp</span>
                        <input type="number" name="harga" id="harga_input_modal" required min="0" placeholder="0" class="w-full pl-12 pr-5 py-4 bg-blue-50/50 border-2 border-blue-100/50 rounded-2xl text-sm font-black text-blue-600 focus:border-blue-500 outline-none shadow-inner transition-all">
                    </div>
                    <p class="text-[9px] text-slate-400 italic ml-1">* Harga akan terisi otomatis sesuai tipe, namun tetap dapat diubah.</p>
                </div>

                <div class="col-span-2 mt-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block text-center tracking-[0.3em]">Set Status Awal</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="Tersedia" class="hidden peer" checked>
                            <div class="p-4 text-center rounded-2xl bg-slate-50 border-2 border-slate-50 text-[10px] font-black text-slate-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 peer-checked:shadow-lg peer-checked:shadow-emerald-100 transition-all uppercase">
                                <i class="fas fa-check-circle mr-1 text-xs"></i> Tersedia
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="Dibooking" class="hidden peer">
                            <div class="p-4 text-center rounded-2xl bg-slate-50 border-2 border-slate-50 text-[10px] font-black text-slate-400 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-600 peer-checked:shadow-lg peer-checked:shadow-amber-100 transition-all uppercase">
                                <i class="fas fa-clock mr-1 text-xs"></i> Booking
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-blue-600 text-white p-5 rounded-2xl font-black shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] uppercase tracking-[0.2em] text-xs flex items-center justify-center">
                    <i class="fas fa-save mr-3"></i> Daftarkan Unit Baru
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
        $('body').addClass('overflow-hidden');
    }

    function closeModal() { 
        const modal = $('#modalUnit');
        const content = $('#modalContent');
        content.removeClass('scale-100 opacity-100').addClass('scale-90 opacity-0');
        setTimeout(() => { 
            modal.addClass('hidden').removeClass('flex'); 
            $('body').removeClass('overflow-hidden');
        }, 200);
    }

    // AJAX Filter Tipe & Auto Harga
    $('#project_select_modal').on('change', function() {
        let projectId = $(this).val();
        let tipeSelect = $('#tipe_select_modal');
        let hargaInput = $('#harga_input_modal');
        
        if (projectId) {
            tipeSelect.prop('disabled', false).removeClass('bg-slate-100 text-slate-400').addClass('bg-slate-50 text-slate-700 border-slate-50').empty().append('<option value="">⏳ Memuat Tipe...</option>');
            
            $.ajax({
                url: '/admin/get-tipe/' + projectId,
                type: 'GET',
                success: function(data) {
                    tipeSelect.empty().append('<option value="">-- Pilih Tipe --</option>');
                    $.each(data, function(k, v) {
                        // Simpan harga dalam atribut data untuk akses cepat
                        tipeSelect.append('<option value="' + v.id + '" data-harga="' + v.harga + '">Tipe ' + v.nama_tipe + '</option>');
                    });
                },
                error: function() {
                    tipeSelect.empty().append('<option value="">Gagal memuat tipe</option>');
                }
            });
        } else {
            tipeSelect.prop('disabled', true).addClass('bg-slate-100 text-slate-400').removeClass('bg-slate-50 text-slate-700').empty().append('<option value="">Tentukan Proyek Dahulu</option>');
            hargaInput.val(0);
        }
    });

    // Auto-fill harga saat tipe dipilih
    $('#tipe_select_modal').on('change', function() {
        let selectedOption = $(this).find('option:selected');
        let harga = selectedOption.data('harga');
        if (harga) {
            $('#harga_input_modal').val(harga);
        }
    });

    function deleteUnit(id) {
        Swal.fire({
            title: '<span class="text-2xl font-black uppercase tracking-tighter">Hapus Unit?</span>',
            html: '<p class="text-slate-500 text-sm font-medium">Data unit yang dihapus tidak dapat dipulihkan kembali dan akan hilang dari database.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'YA, HAPUS PERMANEN',
            cancelButtonText: 'BATALKAN',
            customClass: {
                popup: 'rounded-[2.5rem] p-8',
                confirmButton: 'rounded-xl font-black uppercase py-4 px-8 text-[10px] tracking-widest',
                cancelButton: 'rounded-xl font-black uppercase py-4 px-8 text-[10px] tracking-widest'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    // Tutup modal jika klik di luar area modal content
    $('#modalUnit').on('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection