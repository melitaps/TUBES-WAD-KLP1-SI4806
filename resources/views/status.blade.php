@extends('layouts.app')

@section('title', 'Dashboard - Pesanan')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: var(--nra-green);">Pesanan</h1>
    
    <div class="card card-custom">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Waktu</th>
                            <th>Nama</th>
                            <th>Pesanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->no_order }}</strong></td>
                            <td>{{ $order->created_at->format('d/m/Y, H:i') }}</td>
                            <td>{{ $order->nama_pemesan }}</td>
                            <td>
                                @php
                                    $items = $order->orderDetails->map(fn($d) => $d->menu->nama_menu)->take(2)->join(', ');
                                    $more = $order->orderDetails->count() > 2 ? '...' : '';
                                @endphp
                                {{ $items }}{{ $more }}
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower($order->status) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ url('/orders/'.$order->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $order->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">Belum ada pesanan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection