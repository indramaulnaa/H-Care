<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Pastikan sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Jika jabatannya (role) SESUAI dengan yang diizinkan, silakan masuk
        if (Auth::user()->role === $role) {
            return $next($request);
        }

        // 3. Jika jabatannya TIDAK SESUAI, tendang kembali ke halamannya masing-masing!
        if (Auth::user()->role === 'admin_puskesmas') {
            return redirect('/dashboard/puskesmas')->withErrors(['error' => 'Akses Ditolak! Anda dilarang masuk ke area Dinas Kesehatan.']);
        } elseif (Auth::user()->role === 'admin_dinkes') {
            return redirect('/dashboard/dinkes')->withErrors(['error' => 'Akses Ditolak! Anda dilarang masuk ke area Puskesmas.']);
        }

        return redirect('/login');
    }
}