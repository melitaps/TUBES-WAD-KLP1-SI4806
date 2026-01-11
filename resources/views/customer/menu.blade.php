@extends('layouts.app')

@section('title', 'Menu - NRA Ayam Goreng')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3" style="color: var(--nra-green);">
            <i class="bi bi-grid-3x3-gap"></i> Menu Kami
        </h1>
        <p class="lead text-muted">Pilih menu favorit Anda dan tambahkan ke keranjang</p>
    </div>
    
    <!-- Category Filter -->
    <div class="mb-4">
        <div class="btn-group flex-wrap" role="group">
            <button type="button" class="btn btn-nra-outline active" data-category="all">
                <i class="bi bi-grid"></i> Semua
            </button>
            @foreach($kategori as $kat)
            <button type="button" class="btn btn-nra-outline" data-category="{{ $kat->id }}">
                {{ $kat->nama_kategori }}
            </button>
            @endforeach
        </div>
    </div>
    
    <!-- Menu Grid -->
    <div class="row g-4" id="menuGrid">
        @foreach($menu as $item)
        <div class="col-md-4 menu-item" data-category="{{ $item->kategori_id }}">
            <div class="card menu-card card-custom h-100">
                @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->nama_menu }}">
                @else
                <img src="https://via.placeholder.com/400x200/1a5c1a/ffffff?text={{ urlencode($item->nama_menu) }}" 
                     class="card-img-top" alt="{{ $item->nama_menu }}">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $item->nama_menu }}</h5>
                    <p class="card-text text-muted small mb-3">{{ Str::limit($item->deskripsi, 80) }}</p>
                    <p class="price mb-3 mt-auto">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                    
                    <div class="d-flex gap-2 align-items-center">
                        <div class="qty-control flex-grow-1">
                            <button class="qty-btn" onclick="decreaseQty({{ $item->id }})">-</button>
                            <input type="number" class="qty-input" id="qty{{ $item->id }}" value="1" min="1" readonly>
                            <button class="qty-btn" onclick="increaseQty({{ $item->id }})">+</button>
                        </div>
                        <button class="btn btn-nra-primary" onclick="addToCart({{ $item->id }}, '{{ addslashes($item->nama_menu) }}', {{ $item->harga }}, '{{ $item->image }}')">
                            <i class="bi bi-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($menu->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
        <p class="mt-3 text-muted">Belum ada menu tersedia</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
// Quantity Controls
function increaseQty(id) {
    const input = document.getElementById('qty' + id);
    input.value = parseInt(input.value) + 1;
}

function decreaseQty(id) {
    const input = document.getElementById('qty' + id);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Add to Cart
function addToCart(id, name, price, image) {
    const qty = parseInt(document.getElementById('qty' + id).value);
    
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        existingItem.quantity += qty;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: qty,
            image: image
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    
    // Show success message with Bootstrap toast
    const toastHtml = `
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
            <div class="toast show" role="alert">
                <div class="toast-header bg-success text-white">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong class="me-auto">Berhasil!</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${name} (${qty}x) ditambahkan ke keranjang!
                </div>
            </div>
        </div>
    `;
    
    // Remove old toast if exists
    const oldToast = document.querySelector('.toast');
    if (oldToast) oldToast.remove();
    
    // Add new toast
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        const toast = document.querySelector('.toast');
        if (toast) toast.remove();
    }, 3000);
    
    // Reset quantity
    document.getElementById('qty' + id).value = 1;
}

// Category Filter
document.querySelectorAll('[data-category]').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.dataset.category;
        
        // Update active button
        document.querySelectorAll('[data-category]').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filter menu items
        document.querySelectorAll('.menu-item').forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection