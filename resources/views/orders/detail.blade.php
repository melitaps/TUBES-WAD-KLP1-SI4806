@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: var(--nra-green);">Detail Pesanan</h1>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-body p-5">
                    <!-- Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">ID Pesanan</label>
                                <div class="fw-bold">{{ $order->no_order }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Nama</label>
                                <div class="fw-bold">{{ $order->nama_pemesan }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Waktu</label>
                                <div class="fw-bold">{{ $order->created_at->format('d/m/Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Nomor HP</label>
                                <div class="fw-bold">{{ $order->no_hp }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Alamat</label>
                                <div class="fw-bold">{{ $order->alamat }}</div>
                            </div>
                        </div>
                    </div>
                    
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
                                        <td>{{ $detail->menu->nama_menu }}</td>
                                        <td class="text-end">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $detail->jumlah }}</td>
                                        <td class="text-end">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold">Total</td>
                                        <td class="text-end fw-bold" style="color: var(--nra-green); font-size: 1.25rem;">
                                            Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <div>
                                <select class="form-select form-select-lg" id="statusSelect" data-order-id="{{ $order->id }}">
                                    <option value="menunggu" {{ $order->status == 'menunggu' ? 'selected' : '' }} class="bg-warning">Menunggu</option>
                                    <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }} class="bg-info">Diproses</option>
                                    <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }} class="bg-success">Selesai</option>
                                </select>
                                <a href="{{ route('reports.export') }}" class="btn btn-nra-outline flex-fill">
                        <i class="bi bi-download"></i> Export PDF
                        </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 mt-5">
                        <button type="button" class="btn btn-nra-primary btn-lg flex-fill" id="simpanBtn">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                        <a href="{{ url('/orders') }}" class="btn btn-outline-secondary btn-lg flex-fill">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('simpanBtn').addEventListener('click', function() {
    const orderId = document.getElementById('statusSelect').dataset.orderId;
    const newStatus = document.getElementById('statusSelect').value;
    
    fetch(`/orders/${orderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Status berhasil diupdate!');
            window.location.href = '/orders';
        } else {
            alert('Gagal mengupdate status: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
    });
});
</script>
@endpush
@endsection
