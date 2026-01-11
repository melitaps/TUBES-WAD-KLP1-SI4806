?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NRA Ayam Goreng')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --nra-green: #1a5c1a;
            --nra-green-dark: #144814;
            --nra-green-light: #2d8b2d;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Custom Navbar */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .nav-link {
            font-weight: 500;
            color: #333 !important;
            padding: 0.5rem 1rem !important;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--nra-green) !important;
            border-bottom-color: var(--nra-green);
        }

        /* Buttons */
        .btn-nra-primary {
            background: var(--nra-green);
            border-color: var(--nra-green);
            color: white;
        }

        .btn-nra-primary:hover {
            background: var(--nra-green-dark);
            border-color: var(--nra-green-dark);
            color: white;
        }

        .btn-nra-outline {
            border: 2px solid var(--nra-green);
            color: var(--nra-green);
            background: transparent;
        }

        .btn-nra-outline:hover {
            background: var(--nra-green);
            color: white;
        }

        /* Cards */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        /* Stats Card */
        .stats-card {
            background: linear-gradient(135deg, var(--nra-green) 0%, var(--nra-green-light) 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .stats-card .stats-value {
            font-size: 3rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .stats-card .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Badges */
        .badge-menunggu {
            background-color: #ffc107;
            color: #000;
        }

        .badge-diproses {
            background-color: #17a2b8;
        }

        .badge-selesai {
            background-color: #28a745;
        }

        /* Menu Card */
        .menu-card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .menu-card img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .menu-card .card-title {
            color: var(--nra-green);
            font-weight: 700;
        }

        .menu-card .price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--nra-green);
        }

        /* Cart Badge */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* Hero Section */
        .hero-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d2818 0%, var(--nra-green) 100%);
        }

        .hero-content {
            background: white;
            border-radius: 24px;
            padding: 4rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        /* Quantity Controls */
        .qty-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: var(--nra-green);
            color: white;
            border-color: var(--nra-green);
        }

        .qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 0.25rem;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        /* Sticky Cart Summary */
        .sticky-summary {
            position: sticky;
            top: 20px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="NRA Logo" class="me-2">
                <span class="fw-bold" style="color: var(--nra-green);">NRA Ayam Goreng</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    @if(auth()->user()->role === 'admin')
                    <!-- Admin Menu -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('menu*') ? 'active' : '' }}" href="{{ url('/menu') }}">Manajemen Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.indexWeb') }}">Manajemen Pelanggan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Laporan & Statistik</a>
                        </li>
                    </ul>
                    @else
                    <!-- Customer Menu -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.menu') ? 'active' : '' }}" href="{{ route('customer.menu') }}">
                                <i class="bi bi-grid"></i> Menu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}" href="{{ route('customer.orders') }}">
                                <i class="bi bi-receipt"></i> Pesanan Saya
                            </a>
                        </li>
                    </ul>
                    @endif
                    
                    <div class="d-flex align-items-center gap-3">
                        @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.cart') }}" class="btn btn-nra-outline position-relative">
                            <i class="bi bi-cart3"></i> Keranjang
                            <span class="cart-badge" id="cartCount">0</span>
                        </a>
                        @endif
                        <div class="dropdown">
                            <button class="btn btn-nra-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                <div class="ms-auto">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-nra-primary">Registrasi</a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    @yield('content')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Cart Counter Script -->
    <script>
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const badge = document.getElementById('cartCount');
            if (badge) {
                badge.textContent = count;
            }
        }
        
        // Update on page load
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
    
    @stack('scripts')
</body>
</html>