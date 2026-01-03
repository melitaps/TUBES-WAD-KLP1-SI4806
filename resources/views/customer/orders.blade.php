@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-5">
    <h1 class="display-5 fw-bold mb-5" style="color: var(--nra-green);">
        <i class="bi bi-receipt"></i> Pesanan Saya
    </h1>
    
    <div class="row">
        @forelse($orders as $order)
        <div class="col-md-6 mb-4">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="mb-1">{{ $order->no_order }}</h6>
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <span class="badge badge-{{ strtolower($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Items:</small>
                        @foreach($order->orderDetails->take(2) as $detail)
                        <div class="small">• {{ $detail->menu->nama_menu }} ({{ $detail->jumlah }}x)</div>
                        @endforeach
                        @if($order->orderDetails->count() > 2)
                        <small class="text-muted">dan {{ $order->orderDetails->count() - 2 }} item lainnya</small>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total</small>
                            <div class="fw-bold" style="color: var(--nra-green);">
                                Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                            </div>
                        </div>
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-nra-outline btn-sm">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Anda belum memiliki pesanan</p>
                <a href="{{ route('customer.menu') }}" class="btn btn-nra-primary mt-3">
                    Mulai Pesan Sekarang
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
