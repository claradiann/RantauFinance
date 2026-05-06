<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; color: #4f46e5; }
        .header p { margin: 5px 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f9fafb; font-weight: bold; color: #374151; }
        
        .tipe-pemasukan { color: #059669; font-weight: bold; }
        .tipe-pengeluaran { color: #dc2626; font-weight: bold; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #999; }
        .summary { margin-top: 20px; width: 250px; margin-left: auto; }
        .summary-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .total { font-size: 14px; font-weight: bold; border-top: 2px solid #4f46e5; padding-top: 10px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>RantauFinance</h2>
        <p>Laporan Riwayat Transaksi</p>
        <p>Nama: {{ $user->name }} | Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Tipe</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $totalIn = 0; $totalOut = 0; @endphp
            @foreach($transaksi as $t)
                @php 
                    if($t->kategori->tipe == 'pemasukan') $totalIn += $t->jumlah;
                    else $totalOut += $t->jumlah;
                @endphp
                <tr>
                    <td>{{ $t->tanggal }}</td>
                    <td>{{ $t->kategori->nama }}</td>
                    <td>{{ $t->keterangan }}</td>
                    <td class="tipe-{{ $t->kategori->tipe }}">{{ ucfirst($t->kategori->tipe) }}</td>
                    <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div style="margin-bottom: 5px;">Total Pemasukan: <span style="color:#059669; float:right;">Rp {{ number_format($totalIn, 0, ',', '.') }}</span></div>
        <div style="margin-bottom: 5px;">Total Pengeluaran: <span style="color:#dc2626; float:right;">Rp {{ number_format($totalOut, 0, ',', '.') }}</span></div>
        <div class="total">Saldo Akhir: <span style="float:right;">Rp {{ number_format($totalIn - $totalOut, 0, ',', '.') }}</span></div>
    </div>

    <div class="footer">
        Dicetak secara otomatis melalui sistem RantauFinance pada {{ date('d-m-Y H:i:s') }}
    </div>
</body>
</html>
