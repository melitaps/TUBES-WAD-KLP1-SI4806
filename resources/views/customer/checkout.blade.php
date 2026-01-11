@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h1 class="display-5 fw-bold mb-5" style="color: var(--nra-green);">
        <i class="bi bi-credit-card"></i> Checkout
    </h1>
    
    <form action="{{ route('customer.orders.store') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <h5 class="mb-4"><i class="bi bi-person"></i> Informasi Pemesan</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pemesan" class="form-control" required value="{{ auth()->user()->name }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                            <input type="tel" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="3" required placeholder="Masukkan alamat lengkap untuk pengiriman"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="metode_pembayaran" class="form-select" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan_tambahan" class="form-control" rows="2" placeholder="Contoh: Tambah nasi, pedas level 1, dll"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card card-custom sticky-summary">
                    <div class="card-body">
                        <h5 class="mb-4">Ringkasan Pesanan</h5>
                        
                        <div id="orderItems" class="mb-3">
                            <!-- Order items will be loaded here -->
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal">Rp0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong id="total" style="color: var(--nra-green); font-size: 1.5rem;">Rp0</strong>
                        </div>
                        
                        <input type="hidden" name="total_harga" id="totalHargaInput">
                        <input type="hidden" name="items" id="itemsInput">
                        
                        <button type="submit" class="btn btn-nra-primary w-100 btn-lg mb-2">
                            <i class="bi bi-check-circle"></i> Buat Pesanan
                        </button>
                        <a href="{{ route('customer.cart') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function loadCheckoutSummary() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    if (cart.length === 0) {
        window.location.href = '{{ route('customer.menu') }}';
        return;
    }
    
    // Display items
    let html = '';
    cart.forEach(item => {
        html += `
            <div class="d-flex justify-content-between align-items-center mb-2 small">
                <span>${item.name} x${item.quantity}</span>
                <span class="fw-bold">Rp${(item.price * item.quantity).toLocaleString('id-ID')}</span>
            </div>
        `;
    });
    document.getElementById('orderItems').innerHTML = html;
    
    // Calculate totals
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    document.getElementById('total').textContent = 'Rp' + total.toLocaleString('id-ID');
    document.getElementById('totalHargaInput').value = total;
    document.getElementById('itemsInput').value = JSON.stringify(cart);
}

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    fetch('{{ route('customer.orders.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear cart
            localStorage.removeItem('cart');
            updateCartCount();
            
            // Redirect to success page
            alert('Pesanan berhasil dibuat! No. Pesanan: ' + data.order_no);
            window.location.href = '{{ route('customer.orders') }}';
        } else {
            alert('Gagal membuat pesanan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membuat pesanan');
    });
});

document.addEventListener('DOMContentLoaded', loadCheckoutSummary);
</script>
@endpush
@endsection
