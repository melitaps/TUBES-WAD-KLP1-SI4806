<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ayam Goreng App</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        {{-- JUDUL KIRI --}}
        <a class="navbar-brand fw-bold" href="#">
            Ayam Goreng App
        </a>

        {{-- MENU KANAN --}}
        <div class="d-flex">
            <a href="{{ route('register') }}" class="nav-link">Register</a>
            <p>     </p>
            <a href="{{ route('login') }}" class="nav-link">Login</a>
        </div>
    </div>
</nav>

{{-- KONTEN --}}
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 80vh">
        <div class="col text-center">
            {{-- LOGO --}}
            <img src="{{ asset('logo.png') }}" alt="Logo Ayam" width="200" class="mb-3">

            <h4 class="fw-semibold">Selamat Datang</h4>
            <p class="text-muted">Aplikasi Pemesanan Ayam Goreng</p>
        </div>
    </div>
</div>

</body>
</html>
