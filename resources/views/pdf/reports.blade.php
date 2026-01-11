<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1a5c1a;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1a5c1a;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #999;
            font-size: 10px;
        }
        
        .summary {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid #1a5c1a;
        }
        
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .summary-row:last-child {
            margin-bottom: 0;
        }
        
        .summary-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
        }
        
        .summary-value {
            display: table-cell;
            width: 60%;
            color: #1a5c1a;
            font-size: 14px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: #1a5c1a;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-menunggu {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-diproses {
            background-color: #17a2b8;
            color: white;
        }
        
        .badge-selesai {
            background-color: #28a745;
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>NRA AYAM GORENG</h1>
        <h2>{{ $title }}</h2>
        <p>Dicetak pada: {{ $exportDate }}</p>
    </div>
    
    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <div class="summary-label">Periode Laporan:</div>
            <div class="summary-value">{{ $period }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Total Pesanan:</div>
            <div class="summary-value">{{ $totalPesanan }} pesanan</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Total Pendapatan:</div>
            <div class="summary-value">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th width="15%">No. Pesanan</th>
                <th width="20%">Nama Pemesan</th>
                <th width="25%">Pesanan</th>
                <th width="15%">Tanggal</th>
                <th width="15%" class="text-right">Total Harga</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td><strong>{{ $order->id }}</strong></td>
                <td>{{ $order->nama_pemesan }}</td>
                <td>
                    @foreach($order->items as $item)
                        {{ $item->nama_menu }} ({{ $item->jumlah }}x)
                        @if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                <td class="text-right"><strong>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</strong></td>
                <td>
                    <span class="badge badge-{{ strtolower($order->status) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>