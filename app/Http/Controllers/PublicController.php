<?php

namespace App\Http\Controllers;

use App\Models\MasterTutorial;
use App\Models\DetailTutorial;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicController extends Controller
{
    /**
     * Halaman presentasi publik (tanpa login).
     * Menampilkan detail tutorial yang berstatus 'show' saja.
     */
    public function presentation($url)
    {
        $master = MasterTutorial::where('url_presentation', $url)->firstOrFail();

        $details = DetailTutorial::where('master_tutorial_id', $master->id)
            ->where('status', 'show')
            ->orderBy('order')
            ->get();

        return view('public.presentation', compact('master', 'details', 'url'));
    }

    /**
     * Endpoint JSON untuk AJAX polling (Smart DOM Update).
     * Mengembalikan data detail tutorial terbaru (hanya status 'show').
     * Dipanggil oleh Fetch API setiap 10 detik dari halaman presentation.
     */
    public function getLatestDetails($url)
    {
        $master = MasterTutorial::where('url_presentation', $url)->first();

        if (!$master) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tutorial tidak ditemukan',
            ], 404);
        }

        // --- Ambil detail yang berstatus 'show' saja, urutkan berdasarkan order ---
        $details = DetailTutorial::where('master_tutorial_id', $master->id)
            ->where('status', 'show')
            ->orderBy('order')
            ->get();

        return response()->json([
            'status'  => 'success',
            'master'  => $master,
            'details' => $details,
        ]);
    }

    /**
     * Generate PDF yang berisi seluruh detail tutorial (show + hide).
     * Menggunakan Dompdf dengan isRemoteEnabled = true agar gambar
     * dari URL internet bisa di-render ke dalam file PDF.
     */
    public function pdf($url)
    {
        $master = MasterTutorial::where('url_finished', $url)->firstOrFail();

        // Ambil SEMUA detail (show dan hide) untuk PDF
        $details = DetailTutorial::where('master_tutorial_id', $master->id)
            ->orderBy('order')
            ->get();

        // --- Konfigurasi Dompdf: aktifkan isRemoteEnabled ---
        // Ini WAJIB agar gambar dari URL eksternal bisa diunduh dan dicetak ke PDF
        $pdf = Pdf::loadView('public.pdf_template', compact('master', 'details'));
        $pdf->getDomPDF()->getOptions()->set('isRemoteEnabled', true);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream("tutorial_{$url}.pdf");
    }
}
