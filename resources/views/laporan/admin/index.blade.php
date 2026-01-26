@extends('layout.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Laporan & Analitik</h2>
            <p class="text-slate-500 font-medium">Pantau performa penjualan dan progres proyek secara real-time.</p>
        </div>
        
        {{-- Tombol Ekspor PDF --}}
        <a href="{{ route('admin.laporan.export') }}" class="bg-blue-600 text-white px-7 py-3 rounded-2xl font-bold flex items-center gap-3 shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all no-underline transform hover:-translate-y-1">
            <i class="fas fa-file-pdf"></i>
            <span>Ekspor Laporan PDF</span>
        </a>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm mb-8 flex flex-wrap gap-6">
        <div class="flex-1 min-w-[240px]">
            <label class="text-[11px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Pilih Proyek</label>
            <select class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer">
                <option>Semua Proyek Aktif</option>
                @foreach($projects as $p)
                    <option>{{ $p->nama_proyek }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[240px]">
            <label class="text-[11px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Periode Analisis</label>
            <select class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer">
                <option>Bulan Ini ({{ date('F Y') }})</option>
                <option>Tahun Ini ({{ date('Y') }})</option>
                <option>Semua Waktu</option>
            </select>
        </div>
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        {{-- Revenue --}}
        <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white shadow-xl shadow-blue-100 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="text-3xl font-black mb-1">Rp {{ number_format($totalRevenue/1000000) }} jt</div>
                <div class="text-xs font-bold opacity-80 uppercase tracking-widest">Total Pendapatan</div>
            </div>
            <i class="fas fa-chart-line absolute -right-6 -bottom-6 text-9xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        {{-- Proyeksi --}}
        <div class="bg-emerald-500 p-8 rounded-[2.5rem] text-white shadow-xl shadow-emerald-100 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
                <div class="text-3xl font-black mb-1">Rp {{ number_format($projectedRevenue/1000000) }} jt</div>
                <div class="text-xs font-bold opacity-80 uppercase tracking-widest">Proyeksi Pendapatan</div>
            </div>
            <i class="fas fa-history absolute -right-6 -bottom-6 text-9xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        {{-- Terjual --}}
        <div class="bg-orange-500 p-8 rounded-[2.5rem] text-white shadow-xl shadow-orange-100 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <div class="text-4xl font-black mb-1">{{ $unitTerjual }}</div>
                <div class="text-xs font-bold opacity-80 uppercase tracking-widest">Unit Terjual</div>
            </div>
            <i class="fas fa-home absolute -right-6 -bottom-6 text-9xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        {{-- Customer --}}
        <div class="bg-purple-600 p-8 rounded-[2.5rem] text-white shadow-xl shadow-purple-100 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div class="text-4xl font-black mb-1">{{ $customerAktif }}</div>
                <div class="text-xs font-bold opacity-80 uppercase tracking-widest">Pelanggan Aktif</div>
            </div>
            <i class="fas fa-users absolute -right-6 -bottom-6 text-9xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>
    </div>

    {{-- Visualisasi Data --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 mb-8 uppercase tracking-widest text-xs flex items-center gap-3">
                <span class="w-3 h-3 bg-blue-600 rounded-full shadow-lg shadow-blue-100"></span>
                Tren Penjualan per Proyek
            </h3>
            <div class="h-[320px]">
                <canvas id="projectSalesChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm flex flex-col items-center">
            <h3 class="w-full font-black text-slate-800 mb-8 uppercase tracking-widest text-xs text-center flex items-center justify-center gap-3">
                <span class="w-3 h-3 bg-emerald-500 rounded-full shadow-lg shadow-emerald-100"></span>
                Distribusi Status Unit
            </h3>
            <div class="h-[280px] w-full">
                <canvas id="unitStatusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <div>
                <h3 class="font-black text-slate-800 uppercase tracking-wider text-sm mb-1">Rincian Performa Proyek</h3>
                <p class="text-xs text-slate-400 font-bold">Total yang dianalisis: {{ count($projects) }} Proyek</p>
            </div>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                        <th class="px-8 py-4">Nama Proyek</th>
                        <th class="px-8 py-4 text-center">Kapasitas</th>
                        <th class="px-8 py-4 text-center">Terjual</th>
                        <th class="px-8 py-4 text-center">Dibooking</th>
                        <th class="px-8 py-4 text-right">Kontribusi Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $p)
                    <tr class="hover:bg-slate-50 transition-all duration-300 group">
                        <td class="px-8 py-5 rounded-l-3xl bg-white border-y border-l border-slate-100 font-bold text-slate-700">
                            {{ $p->nama_proyek }}
                        </td>
                        <td class="px-8 py-5 bg-white border-y border-slate-100 text-center font-bold text-slate-500">
                            {{ $p->booked_count + $p->sold_count }} Unit
                        </td>
                        <td class="px-8 py-5 bg-white border-y border-slate-100 text-center">
                            <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-xs font-black">{{ $p->sold_count }} Unit</span>
                        </td>
                        <td class="px-8 py-5 bg-white border-y border-slate-100 text-center">
                            <span class="bg-orange-50 text-orange-500 px-4 py-1.5 rounded-full text-xs font-black">{{ $p->booked_count }} Unit</span>
                        </td>
                        <td class="px-8 py-5 rounded-r-3xl bg-white border-y border-r border-slate-100 text-right font-black text-slate-900">
                            Rp {{ number_format($p->sold_count * 100000000) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Skrip Grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { 
                    usePointStyle: true, 
                    padding: 30, 
                    font: { family: "'Poppins', sans-serif", weight: '700', size: 12 } 
                }
            },
            tooltip: {
                backgroundColor: '#1e293b',
                padding: 12,
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 13 },
                cornerRadius: 8
            }
        }
    };

    // Bar Chart
    new Chart(document.getElementById('projectSalesChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($projects->pluck('nama_proyek')) !!},
            datasets: [
                { 
                    label: 'Terjual', 
                    data: {!! json_encode($projects->pluck('sold_count')) !!}, 
                    backgroundColor: '#3b82f6', 
                    borderRadius: 12,
                    barThickness: 28
                },
                { 
                    label: 'Dibooking', 
                    data: {!! json_encode($projects->pluck('booked_count')) !!}, 
                    backgroundColor: '#f97316', 
                    borderRadius: 12,
                    barThickness: 28
                }
            ]
        },
        options: {
            ...chartOptions,
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9', drawTicks: false }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });

    // Doughnut Chart
    new Chart(document.getElementById('unitStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dibooking', 'Terjual'],
            datasets: [{
                data: [{{ $statusStats['tersedia'] }}, {{ $statusStats['booked'] }}, {{ $statusStats['terjual'] }}],
                backgroundColor: ['#10b981', '#f97316', '#3b82f6'],
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: { 
            ...chartOptions, 
            cutout: '78%',
            radius: '90%'
        }
    });
</script>
@endsection