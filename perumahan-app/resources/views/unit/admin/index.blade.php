@extends('layout.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Manajemen Unit</h2>
            <p class="text-gray-500 text-sm italic">Kelola unit rumah dan status ketersediaan secara real-time</p>
        </div>
        <button onclick="openModal()" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 shadow-lg flex items-center transition-all hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Tambah Unit
        </button>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100 mb-8 flex items-center gap-4">
        <form action="{{ route('admin.unit.index') }}" method="GET" class="flex items-center gap-4 w-full">
            <div class="p-2 text-gray-400"><i class="fas fa-filter text-blue-500"></i></div>
            <select name="project_id" onchange="this.form.submit()" class="bg-gray-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none min-w-[200px] cursor-pointer">
                <option value="">Semua Proyek</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->nama_proyek }}</option>
                @endforeach
            </select>
            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none min-w-[150px] cursor-pointer">
                <option value="">Semua Status</option>
                @foreach(['Tersedia', 'Dibooking', 'Terjual'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
            <div class="ml-auto text-gray-400 text-xs font-medium italic">
                Menampilkan <span class="text-blue-600 font-bold">{{ $units->count() }}</span> unit
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 uppercase text-[10px] font-black text-gray-400 tracking-widest">
                    <th class="p-6 text-center">No</th>
                    <th class="p-6">Proyek</th>
                    <th class="p-6">Posisi</th>
                    <th class="p-6">Tipe</th>
                    <th class="p-6">Harga</th>
                    <th class="p-6">Status</th>
                    <th class="p-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm font-bold text-gray-600">
                @forelse($units as $index => $unit)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-6 text-center text-gray-400">{{ $index + 1 }}</td>
                    <td class="p-6 font-black text-gray-800">{{ $unit->project->nama_proyek }}</td>
                    <td class="p-6 text-center">
                        <span class="bg-gray-100 px-3 py-1 rounded-lg text-xs">Blok {{ $unit->block }} No. {{ $unit->no_unit }}</span>
                    </td>
                    <td class="p-6 text-gray-500">Tipe {{ $unit->tipe->nama_tipe }}</td>
                    <td class="p-6 text-blue-600 italic font-black">Rp {{ number_format($unit->harga, 0, ',', '.') }}</td>
                    <td class="p-6">
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider
                            {{ $unit->status == 'Tersedia' ? 'bg-green-100 text-green-600' : '' }}
                            {{ $unit->status == 'Dibooking' ? 'bg-orange-100 text-orange-600' : '' }}
                            {{ $unit->status == 'Terjual' ? 'bg-blue-100 text-blue-600' : '' }}">
                            {{ $unit->status }}
                        </span>
                    </td>
                    <td class="p-6 text-center">
                        {{-- PERBAIKAN: Hapus opacity-0 agar tombol langsung terlihat --}}
                        <div class="flex justify-center gap-2 transition-all">
                            <a href="{{ route('admin.unit.edit', $unit->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteUnit({{ $unit->id }})" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $unit->id }}" action="{{ route('admin.unit.destroy', $unit->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-20 text-center flex flex-col items-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-home text-4xl text-gray-200"></i>
                        </div>
                        <p class="text-gray-400 uppercase tracking-widest text-xs font-black">Unit Tidak Ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH UNIT --}}
<div id="modalUnit" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl animate-fade-in scale-95 transition-transform duration-300" id="modalContent">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter"><i class="fas fa-house-chimney mr-2 text-blue-600"></i> Tambah Unit Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fas fa-times text-lg"></i></button>
        </div>
        <form action="{{ route('admin.unit.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Pilih Proyek</label>
                    <select name="project_id" id="project_select" required class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                        <option value="">Pilih Proyek</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->nama_proyek }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Pilih Tipe Rumah</label>
                    <select name="tipe_id" id="tipe_select" required class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none cursor-not-allowed" disabled>
                        <option value="">Pilih Proyek Dahulu</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Blok</label>
                    <input type="text" name="block" required placeholder="A/B/C" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">No. Unit</label>
                    <input type="text" name="no_unit" required placeholder="01" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Harga Unit (Rp)</label>
                    <input type="number" name="harga" required min="0" placeholder="Masukkan harga tanpa titik" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Status Awal</label>
                    <div class="flex gap-2">
                        <label class="flex-1">
                            <input type="radio" name="status" value="Tersedia" class="hidden peer" checked>
                            <div class="p-3 text-center rounded-xl bg-gray-50 text-xs font-bold text-gray-400 peer-checked:bg-green-500 peer-checked:text-white cursor-pointer transition-all uppercase">Tersedia</div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="status" value="Dibooking" class="hidden peer">
                            <div class="p-3 text-center rounded-xl bg-gray-50 text-xs font-bold text-gray-400 peer-checked:bg-orange-500 peer-checked:text-white cursor-pointer transition-all uppercase">Dibooking</div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="pt-4 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white p-4 rounded-2xl font-black shadow-lg hover:bg-blue-700 transition-all active:scale-95 uppercase tracking-widest">Simpan Unit</button>
                <button type="button" onclick="closeModal()" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold hover:bg-gray-200 transition-all uppercase text-xs">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModal() { 
        $('#modalUnit').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modalContent').removeClass('scale-95').addClass('scale-100'); }, 10);
    }

    function closeModal() { 
        $('#modalContent').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#modalUnit').addClass('hidden').removeClass('flex'); }, 200);
    }

    $('#project_select').on('change', function() {
        let projectId = $(this).val();
        let tipeSelect = $('#tipe_select');
        
        if (projectId) {
            tipeSelect.prop('disabled', false).removeClass('cursor-not-allowed').empty().append('<option value="">Memuat...</option>');
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
        } else {
            tipeSelect.prop('disabled', true).addClass('cursor-not-allowed').empty().append('<option value="">Pilih Proyek Dahulu</option>');
        }
    });

    function deleteUnit(id) {
        Swal.fire({
            title: 'Hapus Unit?',
            text: "Data unit yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

<style>
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endsection