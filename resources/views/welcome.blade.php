<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Status Pesanan</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        /* NAVBAR */
        .navbar {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ddd;
        }
        .nav-left {
            display: flex;
            gap: 25px;
            align-items: center;
        }
        .logo {
            width: 40px;
            height: 40px;
            background: #ccc;
            border-radius: 50%;
        }
        .nav-left a {
            text-decoration: none;
            color: black;
        }
        .nav-left .active {
            border-bottom: 4px solid gray;
            padding-bottom: 5px;
        }

        /* CONTENT */
        .container {
            padding: 30px 40px;
        }
        .top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        /* FILTER */
        .filter {
            display: flex;
            gap: 15px;
        }
        input, select {
            background: #d9d9d9;
            border: none;
            padding: 8px;
        }

        /* SLOT CARD */
        .slot {
            background: #d9d9d9;
            padding: 20px 30px;
            width: 280px;
        }
        .slot h1 {
            margin: 10px 0;
            font-size: 38px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #e0e0e0;
        }
        th, td {
            border: 2px solid white;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #cfcfcf;
        }
        .btn {
            padding: 6px 14px;
            border: none;
            background: #8a8a8a;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <div class="logo"></div>
        <a href="#">Dashboard</a>
        <a href="#">Manajemen Menu</a>
        <a href="#">Manajemen Pelanggan</a>
        <a href="#" class="active">Status Pesanan</a>
        <a href="#">Laporan & Statistik</a>
    </div>
    <a href="#">Logout</a>
</div>

<!-- CONTENT -->
<div class="container">

    <div class="top">
        <div class="filter">
            <input type="text" placeholder="ID Pesanan">
            <select>
                <option>Status</option>
                <option>Menunggu</option>
                <option>Diproses</option>
                <option>Selesai</option>
            </select>
        </div>

        <div class="slot">
            <div>Slot Pesanan Aktif</div>
            <h1>{{ $slotAktif ?? 1 }} / 20</h1>
            <small>30 hari terakhir</small>
        </div>
    </div>

    <div style="text-align:right;margin-bottom:10px;">
        <button class="btn">Export</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Nomor Pesanan</th>
                <th>Nama Pemesan</th>
                <th>Status</th>
                <th>Admin/Kasir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->no_order }}</td>
                <td>{{ $order->nama_pemesan }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>-</td>
                <td>
                    @if ($order->status === 'menunggu')
                        <button class="btn"
                            onclick="updateStatus({{ $order->id }}, 'diproses')">
                            Proses
                        </button>
                    @elseif ($order->status === 'diproses')
                        <button class="btn"
                            onclick="updateStatus({{ $order->id }}, 'selesai')">
                            Selesai
                        </button>
                    @else
                        ✔
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<script>
function updateStatus(id, status) {
    if (!confirm('Ubah status pesanan?')) return;

    fetch(/orders/${id}/status, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}
</script>

</body>
</html>