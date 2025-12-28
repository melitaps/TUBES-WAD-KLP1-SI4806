<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // ===============1==============
    // Periksa apakah pengguna terautentikasi dan apakah perannya sesuai dengan salah satu peran yang diizinkan.
        if (!$user || !in_array($user->role, $roles)) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman tersebut!');
        }

        return $next($request);
    }
}