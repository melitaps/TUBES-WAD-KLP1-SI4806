<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Pelanggan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Data Pelanggan</h2>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Nomor HP</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>C{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $customer->nama }}</td>
                <td>{{ $customer->no_hp }}</td>
                <td>{{ $customer->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>