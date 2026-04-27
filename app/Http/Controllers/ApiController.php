<?php

namespace App\Http\Controllers;

use App\Models\MasterTutorial;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * REST API Internal: Menampilkan daftar master_tutorial.
     * Bisa difilter berdasarkan kode mata kuliah via query parameter.
     *
     * Endpoint: GET /api/tutorials?kode_makul=PWL
     */
    public function index(Request $request)
    {
        $kodeMakul = $request->query('kode_makul');

        if ($kodeMakul) {
            $data = MasterTutorial::where('kode_makul', $kodeMakul)->get();
        } else {
            $data = MasterTutorial::all();
        }

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'total'  => $data->count(),
        ]);
    }
}
