<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRA - Manajemen Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Arial, sans-serif; }
    </style>
</head>
<body class="bg-white">

    <div class="container mx-auto px-4 py-6">
        <header class="flex justify-between items-center border-b-2 border-gray-200 pb-4 mb-8">
            <div class="flex items-center space-x-10">
                <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center text-xs font-bold text-center p-2">
                    Logo NRA
                </div>
                <nav class="flex space-x-8 font-bold text-gray-700">
                    <a href="#" class="hover:text-black">Dashboard</a>
                    <a href="#" class="hover:text-black">Manajemen Menu</a>
                    <div class="relative">
                        <a href="{{ route('customers.index') }}" class="text-black">Manajemen Pelanggan</a>
                        <div class="absolute -bottom-5 left-0 w-full h-1.5 bg-gray-500"></div>
                    </div>
                    <a href="#" class="hover:text-black">Laporan & Statistik</a>
                </nav>
            </div>
            <div class="font-bold text-gray-700 cursor-pointer">Logout</div>
        </header>

        <div class="bg-gray-200 p-6 rounded-sm w-80 mb-10 shadow-sm">
            <h3 class="text-sm font-bold text-black mb-1">Total Pelanggan</h3>
            <p class="text-7xl font-bold text-black mb-1">{{ $totalPelanggan }}</p>
            <p class="text-sm font-bold text-black">Pelanggan</p>
        </div>

        <div class="flex justify-between items-end mb-4">
            <div class="flex space-x-10">
                <div class="w-40">
                    <label class="block bg-gray-200 text-center py-1 px-4 font-bold text-sm mb-1 rounded-sm">Tanggal</label>
                    <input type="date" class="w-full border-none bg-transparent focus:ring-0">
                </div>
                <div class="w-80">
                    <label class="block bg-gray-200 text-center py-1 px-4 font-bold text-sm mb-1 rounded-sm text-left px-4">Cari</label>
                    <form action="{{ route('customers.index') }}" method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full bg-gray-200 py-1 px-4 outline-none rounded-sm border-none" 
                               placeholder="...">
                    </form>
                </div>
            </div>
            <div class="pb-1">
                <a href="{{ route('customers.export') }}" class="font-bold text-black hover:underline">Export</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-300 text-sm font-bold text-black">
                        <th class="border border-white p-3 w-12">No</th>
                        <th class="border border-white p-3">ID Pelanggan</th>
                        <th class="border border-white p-3">Nama</th>
                        <th class="border border-white p-3">No HP</th>
                        <th class="border border-white p-3">Total Pesanan</th>
                        <th class="border border-white p-3">Total Transaksi</th>
                        <th class="border border-white p-3">Alamat</th>
                        <th class="border border-white p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $customer)
                    <tr class="bg-gray-200 text-sm text-center font-bold text-black">
                        <td class="border border-white p-3">{{ $index + 1 }}</td>
                        <td class="border border-white p-3">{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="border border-white p-3 text-left px-6">{{ $customer->nama }}</td>
                        <td class="border border-white p-3">{{ $customer->no_hp }}</td>
                        <td class="border border-white p-3">{{ $customer->total_pesanan ?? 0 }}</td>
                        <td class="border border-white p-3">Rp{{ number_format($customer->total_transaksi ?? 0, 0, ',', '.') }}</td>
                        <td class="border border-white p-3 text-left px-6">{{ $customer->alamat }}</td>
                        <td class="border border-white p-3">
                            <button class="bg-gray-500 text-white px-6 py-1 text-xs hover:bg-gray-600 transition">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-gray-100">
                        <td colspan="8" class="p-10 text-center font-bold text-gray-500 italic">Data Pelanggan Belum Tersedia</td>
                    </tr>
                    @endforelse

                    @for ($i = 0; $i < 5; $i++)
                    <tr class="bg-gray-200 h-10">
                        <td class="border border-white p-3"></td>
                        <td class="border border-white p-3"></td>
                        <td class="border border-white p-3"></td>
                        <td class="border border-white p-3"></td>
                        <td class="border border-white p-3"></td>
                        <td class="border border-white p-3"></td>
                        <td class="border border-white p-3"></td>
                        <td class="border border-white p-3"></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>