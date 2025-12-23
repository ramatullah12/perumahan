<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Properti</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .stat-box { background: #f8fafc; padding: 15px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #f1f5f9; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENJUALAN UNIT</h2>
        <p>PropTech Realty - Kedaton Sejahtera Abadi</p>
    </div>

    <div class="stat-box">
        <p><strong>Total Revenue:</strong> Rp {{ number_format($totalRevenue) }}</p>
        <p><strong>Total Unit Terjual:</strong> {{ $unitTerjual }} Unit</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Proyek</th>
                <th>Unit Terjual</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $p)
            <tr>
                <td>{{ $p->nama_proyek }}</td>
                <td>{{ $p->sold_count }}</td>
                <td>Rp {{ number_format($p->sold_count * 100000000) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>