@extends('layouts.app')

@section('content')
<h4>Laporan Pendapatan</h4>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6>Total Pesanan</h6>
                <h3>{{ $totalPesanan }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h6>Total Pendapatan</h6>
                <h3>Rp {{ number_format($totalPendapatan) }}</h3>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered">
    <tr>
        <th>ID Pesanan</th>
        <th>Nama Pemesan</th>
        <th>Pesanan</th>
        <th>Total Harga</th>
        <th>Status</th>
    </tr>

    @foreach($orders as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->nama_customer }}</td>
        <td>
            @foreach($order->items as $item)
                {{ $item->nama_menu }} ({{ $item->qty }})<br>
            @endforeach
        </td>
        <td>Rp {{ number_format($order->total_harga) }}</td>
        <td>
            <span class="badge bg-info">{{ $order->status }}</span>
        </td>
    </tr>
    @endforeach
</table>
@endsection
