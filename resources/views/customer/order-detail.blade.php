@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: var(--nra-green);">Detail Pesanan</h1>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-body p-5">
                    <!-- Order Status Badge -->
                    <div class="text-center mb-4">
                        <span class="badge badge-{{ strtolower($order->status) }} fs-5 px-4 py-2">
                            @if($order->status == 'menunggu')
                                <i class="bi bi-clock-history"></i> Menunggu Konfirmasi
                            @elseif($order->status == 'diproses')
                                <i class="bi bi-gear"></i> Sedang Diproses
                            @elseif($order->status == 'selesai')
                                <i class="bi bi-check-circle"></i> Pesanan Selesai
                            @else
                                {{ ucfirst($order->status) }}
                            @endif
                        </span>
                    </div>

                    <!-- Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">ID Pesanan</label>
                                <div class="fw-bold fs-5" style="color: var(--nra-green);">{{ $order->no_order }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Nama Pemesan</label>
                                <div class="fw-bold">{{ $order->nama_pemesan }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Waktu Pemesanan</label>
                                <div class="fw-bold">{{ $order->created_at->format('d/m/Y, H:i') }} WIB</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Nomor HP</label>
                                <div class="fw-bold">{{ $order->no_hp }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Alamat Pengiriman</label>
                                <div class="fw-bold">{{ $order->alamat }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Metode Pembayaran</label>
                                <div class="fw-bold text-capitalize">{{ $order->metode_pembayaran }}</div>
                            </div>
                        </div>
                    </div>

                    @if($order->catatan_tambahan)
                    <div class="alert alert-info mb-4">
                        <strong><i class="bi bi-info-circle"></i> Catatan:</strong><br>
                        {{ $order->catatan_tambahan }}
                    </div>
                    @endif
                    
                    <!-- Order Items -->
                    <div class="mb-4">
                        <label class="text-muted small mb-3">Pesanan</label>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderDetails as $detail)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($detail->menu->image)
                                                <img src="{{ asset('storage/' . $detail->menu->image) }}" 
                                                     alt="{{ $detail->menu->nama_menu }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" 
                                                     class="me-2">
                                                @endif
                                                <span>{{ $detail->menu->nama_menu }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $detail->jumlah }}</td>
                                        <td class="text-end">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                        <td class="text-end fw-bold">
                                            Rp{{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr class="table-success">
                                        <td colspan="3" class="text-end fw-bold fs-5">Total</td>
                                        <td class="text-end fw-bold fs-5" style="color: var(--nra-green);">
                                            Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="mb-4">
                        <label class="text-muted small mb-3">Status Pesanan</label>
                        <div class="timeline">
                            <div class="timeline-item {{ in_array($order->status, ['menunggu','diproses','selesai']) ? 'active' : '' }}">
                                <div class="timeline-marker">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pesanan Diterima</h6>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="timeline-item {{ in_array($order->status, ['diproses','selesai']) ? 'active' : '' }}">
                                <div class="timeline-marker">
                                    <i class="bi bi-{{ in_array($order->status, ['diproses','selesai']) ? 'check-circle-fill' : 'circle' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Sedang Diproses</h6>
                                </div>
                            </div>
                            <div class="timeline-item {{ $order->status == 'selesai' ? 'active' : '' }}">
                                <div class="timeline-marker">
                                    <i class="bi bi-{{ $order->status == 'selesai' ? 'check-circle-fill' : 'circle' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pesanan Selesai</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 mt-5">
                        <a href="{{ route('customer.orders') }}" class="btn btn-nra-primary btn-lg flex-fill">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
                        </a>
                        <a href="{{ route('customers.export', $order->id) }}"
                               target="_blank"
                               class="btn btn-success btn-lg flex-fill">
                                <i class="bi bi-printer"></i> Cetak Nota
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
    padding-bottom: 30px;
    opacity: 0.5;
}
.timeline-item.active {
    opacity: 1;
}
.timeline-marker {
    position: absolute;
    left: -23px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #dee2e6;
}
.timeline-item.active .timeline-marker {
    color: var(--nra-green);
}
</style>
@endpush
@endsection