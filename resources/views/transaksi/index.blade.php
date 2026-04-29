<!DOCTYPE html>
<html>
<head>
    <title>Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2 class="mb-4">Data Transaksi</h2>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>User</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $t)
        <tr>
            <td>{{ $t->user->name }}</td>
            <td>{{ $t->kategori->nama }}</td>
            <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
            <td>{{ $t->tanggal }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
    
    <!-- TOTAL -->
    <div class="mb-3">
        <p><strong>Total Pemasukan:</strong> Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        <p><strong>Total Pengeluaran:</strong> Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
    </div>

</body>
</html>