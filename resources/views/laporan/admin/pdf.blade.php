<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Kedaton Sejahtera Abadi</title>
    <style>
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #334155; 
            line-height: 1.5;
        }
        
        /* Kop Surat */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .kop-surat h1 { margin: 0; font-size: 22px; color: #0f172a; text-transform: uppercase; }
        .kop-surat p { margin: 5px 0; font-size: 12px; color: #64748b; }

        .title { text-align: center; text-decoration: underline; margin-bottom: 20px; font-size: 16px; font-weight: bold; }

        /* Summary Box */
        .summary-wrapper { width: 100%; margin-bottom: 30px; }
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
        }
        .summary-card table { border: none; margin: 0; }
        .summary-card td { border: none; padding: 5px 0; font-size: 14px; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { 
            background-color: #0f172a; 
            color: white; 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 0.5px;
            padding: 12px 10px;
            border: 1px solid #0f172a;
        }
        td { 
            border: 1px solid #e2e8f0; 
            padding: 10px; 
            font-size: 11px; 
            vertical-align: middle;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        /* Footer Tanda Tangan */
        .footer-sig { margin-top: 50px; width: 100%; }
        .sig-box { float: right; width: 200px; text-align: center; font-size: 12px; }
        .sig-space { height: 70px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h1>PT KEDATON SEJAHTERA ABADI</h1>
        <p>Jl. Jenderal Sudirman No. 123, Palembang, Sumatera Selatan</p>
        <p>Email: info@kedatonsejahtera.com | Telp: (0711) 123456</p>
    </div>

    <div class="title">LAPORAN PENJUALAN UNIT PROPERTI</div>

    <div class="summary-wrapper">
        <div class="summary-card">
            <table style="width: 100%;">
                <tr>
                    <td width="20%">Tanggal Laporan</td>
                    <td width="2%">:</td>
                    <td class="font-bold">{{ date('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Total Revenue</td>
                    <td>:</td>
                    <td class="font-bold" style="color: #2563eb;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Volume Penjualan</td>
                    <td>:</td>
                    <td class="font-bold">{{ $unitTerjual }} Unit Terjual</td>
                </tr>
            </table>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Deskripsi Proyek</th>
                <th width="20%" class="text-center">Unit Terjual</th>
                <th width="30%" class="text-right">Akumulasi Revenue</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($projects as $p)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>
                    <div class="font-bold" style="font-size: 12px;">{{ $p->nama_proyek }}</div>
                    <div style="color: #64748b; font-size: 10px;">Kedaton Group Property</div>
                </td>
                <td class="text-center">{{ $p->sold_count }} Unit</td>
                <td class="text-right font-bold">
                    {{-- PERBAIKAN: Gunakan variabel revenue proyek dari controller agar sinkron --}}
                    Rp {{ number_format($p->revenue_proyek, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc;">
                <td colspan="2" class="text-center font-bold">TOTAL KESELURUHAN</td>
                <td class="text-center font-bold">{{ $unitTerjual }} Unit</td>
                <td class="text-right font-bold" style="color: #2563eb;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-sig">
        <div class="sig-box">
            <p>Palembang, {{ date('d F Y') }}</p>
            <p><strong>Direktur Operasional,</strong></p>
            <div class="sig-space"></div>
            <p>__________________________</p>
            <p>NIP. KSA-{{ date('Ymd') }}</p>
        </div>
    </div>
</body>
</html>