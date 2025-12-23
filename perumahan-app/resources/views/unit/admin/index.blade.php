@extends('layout.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Manajemen Unit</h2>
            <p class="text-gray-500 text-sm italic">Kelola unit rumah dan status ketersediaan</p>
        </div>
        <button onclick="openModal()" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Unit
        </button>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.unit.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="p-2 text-gray-400">
                <i class="fas fa-filter"></i>
            </div>
            <select name="project_id" onchange="this.form.submit()" class="bg-gray-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none min-w-[200px]">
                <option value="">Semua Proyek</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->nama_proyek }}
                    </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none min-w-[150px]">
                <option value="">Semua Status</option>
                <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Dibooking" {{ request('status') == 'Dibooking' ? 'selected' : '' }}>Dibooking</option>
                <option value="Terjual" {{ request('status') == 'Terjual' ? 'selected' : '' }}>Terjual</option>
            </select>

            <div class="ml-auto text-gray-400 text-xs font-medium italic">
                Menampilkan {{ $units->count() }} unit
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Proyek</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Block</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">No. Unit</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Tipe</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Harga</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($units as $unit)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-6">
                            <span class="font-bold text-gray-800 text-sm">{{ $unit->project->nama_proyek }}</span>
                        </td>
                        <td class="p-6 text-sm font-bold text-gray-600">{{ $unit->block }}</td>
                        <td class="p-6 text-sm font-bold text-gray-600">{{ $unit->no_unit }}</td>
                        <td class="p-6 text-sm font-bold text-gray-600">Tipe {{ $unit->tipe->nama_tipe }}</td>
                        <td class="p-6 text-sm font-bold text-blue-600 italic">
                            Rp {{ number_format($unit->tipe->harga / 1000000, 0) }} jt
                        </td>
                        <td class="p-6">
                            {{-- Update Status Cepat --}}
                            <form action="{{ route('admin.unit.update', $unit->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="project_id" value="{{ $unit->project_id }}">
                                <input type="hidden" name="tipe_id" value="{{ $unit->tipe_id }}">
                                <input type="hidden" name="block" value="{{ $unit->block }}">
                                <input type="hidden" name="no_unit" value="{{ $unit->no_unit }}">
                                <select name="status" onchange="this.form.submit()" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border-none outline-none cursor-pointer
                                    {{ $unit->status == 'Tersedia' ? 'bg-green-100 text-green-600' : '' }}
                                    {{ $unit->status == 'Dibooking' ? 'bg-orange-100 text-orange-600' : '' }}
                                    {{ $unit->status == 'Terjual' ? 'bg-blue-100 text-blue-600' : '' }}">
                                    <option value="Tersedia" {{ $unit->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="Dibooking" {{ $unit->status == 'Dibooking' ? 'selected' : '' }}>Dibooking</option>
                                    <option value="Terjual" {{ $unit->status == 'Terjual' ? 'selected' : '' }}>Terjual</option>
                                </select>
                            </form>
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex justify-center gap-3">
                                {{-- Tombol Edit: Sekarang menggunakan <a> agar aktif ke halaman edit --}}
                                <a href="{{ route('admin.unit.edit', $unit->id) }}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                {{-- Tombol Hapus dengan SweetAlert2 --}}
                                <button type="button" onclick="btnDeleteUnit('{{ $unit->id }}', '{{ $unit->no_unit }}', '{{ $unit->block }}')" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="form-delete-unit-{{ $unit->id }}" action="{{ route('admin.unit.destroy', $unit->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-20 text-center">
                            <i class="fas fa-home text-5xl text-gray-100 mb-4"></i>
                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada unit rumah</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH UNIT --}}
<div id="modalUnit" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl animate-fade-in">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Tambah Unit Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.unit.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Pilih Proyek</label>
                <select name="project_id" id="project_select" required class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Proyek</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->nama_proyek }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Pilih Tipe</label>
                <select name="tipe_id" id="tipe_select" required class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Proyek Dahulu</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="block" required placeholder="Blok (A/B/C)" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 outline-none">
                <input type="text" name="no_unit" required placeholder="No. Unit" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 outline-none">
            </div>
            <div>
                <select name="status" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 outline-none">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Dibooking">Dibooking</option>
                </select>
            </div>
            <div class="pt-4 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white p-4 rounded-2xl font-black shadow-lg hover:bg-blue-700">Simpan Unit</button>
                <button type="button" onclick="closeModal()" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold">Batal</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModal() { $('#modalUnit').removeClass('hidden'); }
    function closeModal() { $('#modalUnit').addClass('hidden'); }

    // AJAX: Tipe berdasarkan Proyek
    $('#project_select').on('change', function() {
        let projectId = $(this).val();
        let tipeSelect = $('#tipe_select');
        tipeSelect.empty().append('<option value="">Memuat tipe...</option>');
        if (projectId) {
            $.ajax({
                url: '/admin/get-tipe/' + projectId,
                type: 'GET',
                success: function(data) {
                    tipeSelect.empty().append('<option value="">Pilih Tipe</option>');
                    $.each(data, function(k, v) {
                        tipeSelect.append('<option value="' + v.id + '">Tipe ' + v.nama_tipe + '</option>');
                    });
                }
            });
        }
    });

    // SweetAlert Hapus
    function btnDeleteUnit(id, no, blk) {
        Swal.fire({
            title: 'Hapus Unit?',
            html: `Yakin menghapus unit <b>${no}</b> blok <b>${blk}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus!',
            borderRadius: '25px'
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('form-delete-unit-' + id).submit(); }
        });
    }
</script>

{{-- Toast Sukses --}}
@if(session('success'))
<script>
    Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
    }).fire({ icon: 'success', title: "{{ session('success') }}" });
</script>
@endif

@endsection