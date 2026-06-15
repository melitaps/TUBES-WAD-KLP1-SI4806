<!DOCTYPE html>
<html>
<head>
    <title>Nota Pesanan - {{ $order->no_order }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1a5c1a; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info td { padding: 5px 0; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nusantara Rasa</h1>
        <h2>Nota Pesanan</h2>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="20%"><strong>No. Order</strong></td>
                <td width="30%">: {{ $order->no_order }}</td>
                <td width="20%"><strong>Tanggal</strong></td>
                <td width="30%">: {{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Nama Pemesan</strong></td>
                <td>: {{ $order->nama_pemesan }}</td>
                <td><strong>No. HP</strong></td>
                <td>: {{ $order->no_hp }}</td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>: {{ $order->alamat }}</td>
                <td><strong>Pembayaran</strong></td>
                <td>: {{ ucfirst($order->metode_pembayaran) }}</td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>: {{ ucfirst($order->status) }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->menu->nama_menu }}</td>
                <td class="text-right">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $detail->jumlah }}</td>
                <td class="text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
                <td class="text-right">Rp{{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>Total</strong></td>
                <td class="text-right total">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px;">
        <p><strong>Catatan Tambahan:</strong></p>
        <p>{{ $order->catatan_tambahan ?: '-' }}</p>
    </div>

    <div style="margin-top: 50px; text-align: center;">
        <p>Terima kasih atas pesanan Anda!</p>
    </div>
</body>
</html>
