<!DOCTYPE html>
<html>
<head>
    <title>Daftar Menu</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR MENU RESTORAN</h2>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $index => $menu)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ $menu->kategori }}</td>
                <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                <td>{{ $menu->stok > 0 ? 'Tersedia' : 'Habis' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>