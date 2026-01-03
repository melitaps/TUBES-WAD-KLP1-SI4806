@extends('layouts.app')

@section('content')
<div class="text-center mb-5">
    <h2 class="fw-bold">Dashboard Statistik</h2>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card card-custom p-4">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Total Pesanan</h6>
                <h1 class="fw-bold text-success">{{ $totalOrder }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-custom p-4">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Total Pendapatan</h6>
                <h1 class="fw-bold text-success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h1>
            </div>
        </div>
    </div>
</div>
@endsection