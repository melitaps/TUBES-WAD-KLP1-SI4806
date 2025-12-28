<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Menu - Ayam NRA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .navbar {
            background: #fff;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #ddd;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: #ccc;
            border-radius: 50%;
        }

        .nav-menu a {
            text-decoration: none;
            color: #000;
            font-weight: bold;
            padding: 10px;
        }

        .active {
            border-bottom: 3px solid #555;
        }

        .container {
            padding: 30px;
        }

        .top-action {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        input {
            padding: 8px;
            width: 200px;
        }

        button {
            padding: 8px 15px;
            cursor: pointer;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-box {
            background: #ddd;
            padding: 20px;
            width: 200px;
            text-align: center;
        }

        .stat-box h1 {
            margin: 0;
            font-size: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ddd;
        }

        th, td {
            padding: 12px;
            border: 2px solid #fff;
            text-align: center;
        }

        .btn-edit {
            background: #999;
            color: #000;
            border: none;
        }

        .btn-delete {
            background: #777;
            color: #000;
            border: none;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <div class="logo"></div>
        <div class="nav-menu">
            <a href="#">Dashboard</a>
            <a href="#" class="active">Manajemen Menu</a>
            <a href="#">Manajemen Pelanggan</a>
            <a href="#">Laporan & Statistik</a>
        </div>
    </div>
    <a href="#">Logout</a>
</div>

<!-- CONTENT -->
<div class="container">

    <!-- SEARCH & ACTION -->
    <div class="top-action">
        <input type="text" placeholder="Cari menu...">
        <div>
            <button onclick="tambahMenu()">Tambah Menu</button>
            <button>Export</button>
        </div>
    </div>

    <!-- STAT -->
    <div class="stats">
        <div class="stat-box">
            <p>Total Menu</p>
            <h1 id="totalMenu">0</h1>
        </div>
        <div class="stat-box">
            <p>Total Kategori</p>
            <h1 id="totalKategori">0</h1>
        </div>
        <div class="stat-box">
            <p>Hasil Pencarian</p>
            <h1>0</h1>
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="menuTable"></tbody>
    </table>

</div>

<script>
const API = '/api/menu';

document.addEventListener('DOMContentLoaded', loadMenu);

function loadMenu() {
    fetch(API)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('menuTable');
            tbody.innerHTML = '';

            document.getElementById('totalMenu').innerText = data.length;

            let kategoriSet = new Set();

            data.forEach(menu => {
                kategoriSet.add(menu.kategori.nama_kategori);

                tbody.innerHTML += `
                    <tr>
                        <td>${menu.nama_menu}</td>
                        <td>${menu.kategori.nama_kategori}</td>
                        <td>Rp${menu.harga}</td>
                        <td>${menu.deskripsi ?? '-'}</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-delete" onclick="hapusMenu(${menu.id})">Hapus</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('totalKategori').innerText = kategoriSet.size;
        });
}

function tambahMenu() {
    const nama = prompt('Nama Menu');
    const harga = prompt('Harga');
    const kategori = prompt('ID Kategori');

    fetch(API, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            nama_menu: nama,
            harga: harga,
            kategori_id: kategori
        })
    }).then(() => loadMenu());
}

function hapusMenu(id) {
    if (!confirm('Hapus menu ini?')) return;

    fetch(`${API}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => loadMenu());
}
</script>

</body>
</html>