@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <h1 class="display-5 fw-bold mb-5" style="color: var(--nra-green);">
        <i class="bi bi-cart3"></i> Keranjang Belanja
    </h1>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-body">
                    <div id="cartItems">
                        <!-- Cart items will be loaded here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-custom sticky-summary">
                <div class="card-body">
                    <h5 class="mb-4">Ringkasan Pesanan</h5>
                    

                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong id="total" style="color: var(--nra-green); font-size: 1.5rem;">Rp0</strong>
                    </div>
                    
                    <a href="{{ route('customer.checkout') }}" class="btn btn-nra-primary w-100 btn-lg mb-2" id="checkoutBtn">
                        <i class="bi bi-credit-card"></i> Lanjut ke Pembayaran
                    </a>
                    <a href="{{ route('customer.menu') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const cartItemsDiv = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Keranjang Anda kosong</p>
                <a href="{{ route('customer.menu') }}" class="btn btn-nra-primary mt-3">
                    Mulai Belanja
                </a>
            </div>
        `;
        document.getElementById('checkoutBtn').style.display = 'none';
        return;
    }
    
    let html = '';
    cart.forEach((item, index) => {
        const imgSrc = item.image ? `/storage/${item.image}` : `https://via.placeholder.com/80/1a5c1a/ffffff?text=${encodeURIComponent(item.name)}`;
        html += `
            <div class="d-flex align-items-center border-bottom py-3">
                <img src="${imgSrc}" alt="${item.name}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">${item.name}</h6>
                    <p class="text-muted small mb-1">Rp${item.price.toLocaleString('id-ID')}</p>
                    <div class="qty-control">
                        <button class="qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                        <input type="number" class="qty-input" value="${item.quantity}" readonly>
                        <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                </div>
                <div class="text-end">
                    <p class="fw-bold mb-2" style="color: var(--nra-green);">Rp${(item.price * item.quantity).toLocaleString('id-ID')}</p>
                    <button class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    cartItemsDiv.innerHTML = html;
    calculateTotal();
}

function updateQuantity(index, change) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart[index].quantity += change;
    
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    updateCartCount();
}

function removeItem(index) {
    if (confirm('Hapus item ini dari keranjang?')) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        updateCartCount();
    }
}

function calculateTotal() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    document.getElementById('total').textContent = 'Rp' + total.toLocaleString('id-ID');
}

document.addEventListener('DOMContentLoaded', loadCart);
</script>
@endpush
@endsection
