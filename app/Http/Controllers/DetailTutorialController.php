<?php

namespace App\Http\Controllers;

use App\Models\DetailTutorial;
use App\Models\MasterTutorial;
use Illuminate\Http\Request;

class DetailTutorialController extends Controller
{
    /**
     * Tampilkan daftar detail tutorial berdasarkan master_tutorial_id.
     */
    public function byMaster($masterId)
    {
        $master  = MasterTutorial::findOrFail($masterId);
        $details = DetailTutorial::where('master_tutorial_id', $masterId)
            ->orderBy('order')
            ->get();

        return view('detail_tutorial.index', compact('master', 'details'));
    }

    /**
     * Tampilkan form tambah detail tutorial.
     */
    public function create(Request $request)
    {
        $masterId = $request->query('master_id');
        $master   = MasterTutorial::findOrFail($masterId);

        return view('detail_tutorial.create', compact('master'));
    }

    /**
     * Simpan detail tutorial baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'master_tutorial_id' => 'required|exists:master_tutorial,id',
            'text'               => 'nullable|string',
            'gambar_file'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_url'         => 'nullable|url|max:500',
            'code'               => 'nullable|string',
            'url'                => 'nullable|url|max:500',
            'order'              => 'required|integer|min:0',
            'status'             => 'required|in:show,hide',
        ]);

        $gambar = null;
        if ($request->hasFile('gambar_file')) {
            $path = $request->file('gambar_file')->store('tutorials', 'public');
            $gambar = asset('storage/' . $path);
        } elseif ($request->filled('gambar_url')) {
            $gambar = $request->gambar_url;
        }

        DetailTutorial::create([
            'master_tutorial_id' => $request->master_tutorial_id,
            'text'               => $request->text,
            'gambar'             => $gambar,
            'code'               => $request->code,
            'url'                => $request->url,
            'order'              => $request->order,
            'status'             => $request->status,
        ]);

        return redirect()->route('detail-tutorial.byMaster', $request->master_tutorial_id)
            ->with('success', 'Detail Tutorial berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit detail tutorial.
     */
    public function edit($id)
    {
        $detail = DetailTutorial::findOrFail($id);
        $master = $detail->master;

        return view('detail_tutorial.edit', compact('detail', 'master'));
    }

    /**
     * Update data detail tutorial di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'text'        => 'nullable|string',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_url'  => 'nullable|url|max:500',
            'code'        => 'nullable|string',
            'url'         => 'nullable|url|max:500',
            'order'       => 'required|integer|min:0',
            'status'      => 'required|in:show,hide',
        ]);

        $detail = DetailTutorial::findOrFail($id);

        $gambar = $detail->gambar;
        
        // Hapus gambar jika diset dari form edit (tombol silang)
        if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
            $gambar = null;
        }

        if ($request->hasFile('gambar_file')) {
            $path = $request->file('gambar_file')->store('tutorials', 'public');
            $gambar = asset('storage/' . $path);
        } elseif ($request->filled('gambar_url')) {
            $gambar = $request->gambar_url;
        }

        $detail->update([
            'text'   => $request->text,
            'gambar' => $gambar,
            'code'   => $request->code,
            'url'    => $request->url,
            'order'  => $request->order,
            'status' => $request->status,
        ]);

        return redirect()->route('detail-tutorial.byMaster', $detail->master_tutorial_id)
            ->with('success', 'Detail Tutorial berhasil diperbarui!');
    }

    /**
     * Hapus detail tutorial.
     */
    public function destroy($id)
    {
        $detail   = DetailTutorial::findOrFail($id);
        $masterId = $detail->master_tutorial_id;
        $detail->delete();

        return redirect()->route('detail-tutorial.byMaster', $masterId)
            ->with('success', 'Detail Tutorial berhasil dihapus!');
    }
}
