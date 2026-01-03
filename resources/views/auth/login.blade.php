<?php
// ============================================
// FILE 3: resources/views/auth/login.blade.php
// ============================================
?>
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom fade-in">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4" style="color: var(--nra-green); font-weight: 700;">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </h2>
                    
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            </div>
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>
                        
                        <button type="submit" class="btn btn-nra-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center mb-0">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="fw-semibold" style="color: var(--nra-green);">Daftar disini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
