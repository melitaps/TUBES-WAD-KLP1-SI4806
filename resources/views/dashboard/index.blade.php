@extends('layouts.app')

@section('content')
<h4>Dashboard</h4>

<div class="row mt-3">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6>Total Pesanan</h6>
                <h3>{{ $totalOrder }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h6>Pendapatan</h6>
                <h3>Rp {{ number_format($pendapatan) }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
