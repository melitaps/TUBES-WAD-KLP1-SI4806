<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke home
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->boolean('remember');

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->route('home')
                ->with('success', 'Berhasil login.');
        }

        // Jika gagal
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password salah.'
            ]);
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Berhasil logout.');
    }


    public function loginApi(Request $request)
    {
        /**
         * =========4===========
         * Validasi data login yang masuk
         */
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        /**
         * =========5===========
         * Generate token API untuk user yang terautentikasi
         * Atur token agar expired dalam 1 jam
         */
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token', ['access-api'], Carbon::now()->addMinutes(60))->plainTextToken;

        /**
         * =========6===========
         * Kembalikan response sukses dengan data $user dan $token
         */
        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
            ], 200);
    }

    public function logoutApi(Request $request)
    {
        /**
         * =========7===========
         * Invalidate token yang digunakan untuk autentikasi request saat ini
         */
        $request->user()->currentAccessToken()->delete();

        /**
         * =========8===========
         * Kembalikan response sukses
         */
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
