<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Memproses Login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required', 
            'password' => 'required',
        ]);

        // Coba login
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();

            // Cek Role & Arahkan ke Dashboard yang sesuai secara PAKSA (Tanpa intended)
            $role = Auth::user()->role;

            if ($role === 'admin_dinkes') {
                return redirect('/dashboard/dinkes'); // <-- Diubah di sini
            } elseif ($role === 'admin_puskesmas') {
                return redirect('/dashboard/puskesmas'); // <-- Diubah di sini
            }

            // Default jika role tidak dikenali
            return redirect('/');
        }

        // Jika gagal
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // 3. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}