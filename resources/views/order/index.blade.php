@extends('layouts.app')

@section('content')
<h4>Pesanan</h4>

@if($orderAktif >= 20)
<div class="alert alert-danger">
    🚫 Pesanan penuh! Maksimal 20 pesanan aktif.
</div>
@endif

<table class="table table-striped">
    <tr>
        <th>Customer</th>
        <th>Total</th>
        <th>Status</th>
    </tr>

    @foreach($orders as $order)
    <tr>
        <td>{{ $order->customer->nama_customer }}</td>
        <td>{{ $order->total_harga }}</td>
        <td>
            <span class="badge bg-info">{{ $order->status }}</span>
        </td>
    </tr>
    @endforeach
</table>
@endsection
