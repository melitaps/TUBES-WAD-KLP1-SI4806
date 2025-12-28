@extends('layouts.app')

@section('content')
<h4>Laporan Penjualan</h4>

<a href="/laporan/export" class="btn btn-success mb-3">Export Excel</a>

<table class="table table-bordered">
    <tr>
        <th>Tanggal</th>
        <th>Total Pesanan</th>
        <th>Pendapatan</th>
    </tr>

    @foreach($reports as $r)
    <tr>
        <td>{{ $r->tanggal }}</td>
        <td>{{ $r->jumlah }}</td>
        <td>{{ $r->total }}</td>
    </tr>
    @endforeach
</table>
@endsection
