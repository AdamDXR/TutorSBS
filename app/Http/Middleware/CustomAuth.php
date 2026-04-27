<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomAuth
{
    /**
     * Middleware untuk memproteksi halaman yang membutuhkan login.
     * Cek apakah session 'isLoggedIn' bernilai true.
     * Jika tidak, redirect ke halaman login.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('isLoggedIn')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
