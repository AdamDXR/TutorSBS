<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function login()
    {
        // Jika sudah login, langsung arahkan ke dashboard
        if (session('isLoggedIn')) {
            return redirect()->route('master-tutorial.index');
        }

        return view('auth.login');
    }

    /**
     * Proses login: Kirim data ke API eksternal JWT.
     * Menggunakan Http::post() bawaan Laravel untuk integrasi API.
     */
    public function process(Request $request)
    {
        // Validasi input form
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // --- Kirim data login ke API eksternal JWT ---
        // Menggunakan Http::post() bawaan Laravel untuk integrasi API
        $response = Http::post('https://jwt-auth-eight-neon.vercel.app/login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        // --- Cek apakah login berhasil (HTTP 200) ---
        if ($response->successful()) {
            $data = $response->json();

            // Simpan token dan status login ke session
            session([
                'refreshToken' => $data['refreshToken'] ?? null,
                'accessToken'  => $data['accessToken'] ?? null,
                'email'        => $request->email,
                'isLoggedIn'   => true,
            ]);

            return redirect()->route('master-tutorial.index')
                ->with('success', 'Login berhasil!');
        }

        // --- Login gagal: kembali ke halaman login dengan pesan error ---
        return back()->with('error', 'Email atau password salah.');
    }

    /**
     * Proses logout: Kirim request ke API logout, lalu hapus session.
     */
    public function logout()
    {
        // --- Kirim request logout ke API eksternal ---
        try {
            Http::withToken(session('refreshToken'))
                ->delete('https://jwt-auth-eight-neon.vercel.app/logout');
        } catch (\Exception $e) {
            // Abaikan error API, tetap lanjutkan proses logout lokal
        }

        // --- Hapus semua data session ---
        session()->flush();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
