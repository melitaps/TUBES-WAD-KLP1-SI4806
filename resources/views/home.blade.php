@extends('layouts.app')

@section('title', 'Selamat Datang - NRA Ayam Goreng')

@section('content')
<div class="hero-section">
    <div class="hero-content text-center fade-in">
        <img src="{{ asset('logo.png') }}" 
             alt="NRA Logo" 
             style="width: 150px; height: 150px;" class="mb-4">
        
        <h1 class="display-5 mb-3" style="color: #0d2818;">Selamat datang di</h1>
        <h2 class="display-4 fw-bold mb-4" style="color: var(--nra-green);">Sistem Order Ayam Goreng NRA</h2>
        
        @guest
        <div class="d-flex gap-3 justify-content-center mt-4">
            <a href="{{ route('login') }}" class="btn btn-nra-primary btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Mulai Memesan
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-person-plus"></i> Daftar
            </a>
        </div>
        @endguest
        
        @auth
        <a href="{{ url('/orders') }}" class="btn btn-nra-primary btn-lg mt-4">
            <i class="bi bi-speedometer2"></i> Lihat Dashboard
        </a>
        @endauth
    </div>
</div>
@endsection