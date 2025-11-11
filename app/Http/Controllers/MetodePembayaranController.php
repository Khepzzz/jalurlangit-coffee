<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $metodes = MetodePembayaran::all();
        return view('pegawai.metode.index', compact('metodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'nomor_tujuan' => 'nullable|string|max:50',
        ]);
    
        MetodePembayaran::create($request->only(['nama_metode', 'keterangan', 'nomor_tujuan']));
    
        return back()->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'nomor_tujuan' => 'nullable|string|max:50',
        ]);
    
        $metode = MetodePembayaran::where('id_metode', $id)->firstOrFail();
        $metode->update($request->only(['nama_metode', 'keterangan', 'nomor_tujuan']));
    
        return back()->with('success', 'Metode pembayaran berhasil diperbarui.');
    }
    
    

    public function destroy($id)
    {
        $metode = MetodePembayaran::where('id_metode', $id)->firstOrFail();;
        $metode->delete();

        return back()->with('success', 'Metode pembayaran dihapus.');
    }
}
