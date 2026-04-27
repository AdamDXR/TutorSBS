<?php

namespace App\Http\Controllers;

use App\Models\MasterTutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MasterTutorialController extends Controller
{
    /**
     * Fungsi helper untuk mengambil daftar mata kuliah dari API eksternal.
     * Jika token sudah kedaluwarsa (401), sesi dihapus dan user dialihkan ke login.
     */
    private function fetchMakul()
    {
        // --- Panggil API getMakul dengan menyertakan Bearer token dari session ---
        $response = Http::withToken(session('refreshToken'))
            ->get('https://jwt-auth-eight-neon.vercel.app/getMakul');

        // --- Pengecekan token kedaluwarsa (HTTP 401 Unauthorized) ---
        // Jika API mengembalikan 401, artinya refreshToken sudah tidak valid.
        // Maka: hapus semua data session, lalu redirect ke halaman login
        // dengan pesan flashdata agar ditampilkan oleh SweetAlert2.
        if ($response->status() === 401) {
            session()->flush(); // Hapus semua data session
            return redirect()->route('login')
                ->with('error', 'Sesi API Anda telah berakhir, silakan login kembali.');
        }

        // --- Kembalikan data JSON jika token masih valid ---
        return $response->json();
    }

    /**
     * Tampilkan daftar semua master tutorial (DataTables).
     */
    public function index()
    {
        $tutorials = MasterTutorial::orderBy('created_at', 'desc')->get();
        return view('master_tutorial.index', compact('tutorials'));
    }

    /**
     * Tampilkan form tambah master tutorial.
     * Dropdown mata kuliah diambil dari API eksternal.
     */
    public function create()
    {
        $makul = $this->fetchMakul();

        // Jika fetchMakul() mengembalikan RedirectResponse (karena 401), langsung return
        if ($makul instanceof \Illuminate\Http\RedirectResponse) {
            return $makul;
        }

        return view('master_tutorial.create', compact('makul'));
    }

    /**
     * Simpan data master tutorial baru ke database.
     * URL presentation dan URL finished di-generate sebagai slug unik.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'kode_makul'       => 'required|string|max:50',
            'url_presentation' => 'required|string|max:255|unique:master_tutorial,url_presentation',
            'url_finished'     => 'required|string|max:255|unique:master_tutorial,url_finished',
        ]);

        MasterTutorial::create([
            'judul'            => $request->judul,
            'kode_makul'       => $request->kode_makul,
            'url_presentation' => Str::slug($request->url_presentation),
            'url_finished'     => Str::slug($request->url_finished),
            'creator_email'    => session('email'),
        ]);

        return redirect()->route('master-tutorial.index')
            ->with('success', 'Master Tutorial berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit master tutorial.
     * Dropdown mata kuliah diambil dari API eksternal.
     */
    public function edit($id)
    {
        $makul = $this->fetchMakul();

        // Jika token expired (401), redirect ke login
        if ($makul instanceof \Illuminate\Http\RedirectResponse) {
            return $makul;
        }

        $data = MasterTutorial::findOrFail($id);
        return view('master_tutorial.edit', compact('data', 'makul'));
    }

    /**
     * Update data master tutorial di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'kode_makul'       => 'required|string|max:50',
            'url_presentation' => 'required|string|max:255|unique:master_tutorial,url_presentation,' . $id,
            'url_finished'     => 'required|string|max:255|unique:master_tutorial,url_finished,' . $id,
        ]);

        $tutorial = MasterTutorial::findOrFail($id);
        $tutorial->update([
            'judul'            => $request->judul,
            'kode_makul'       => $request->kode_makul,
            'url_presentation' => Str::slug($request->url_presentation),
            'url_finished'     => Str::slug($request->url_finished),
        ]);

        return redirect()->route('master-tutorial.index')
            ->with('success', 'Master Tutorial berhasil diperbarui!');
    }

    /**
     * Hapus data master tutorial (beserta detail terkait via CASCADE).
     */
    public function destroy($id)
    {
        $tutorial = MasterTutorial::findOrFail($id);
        $tutorial->delete();

        return redirect()->route('master-tutorial.index')
            ->with('success', 'Master Tutorial berhasil dihapus!');
    }
}
