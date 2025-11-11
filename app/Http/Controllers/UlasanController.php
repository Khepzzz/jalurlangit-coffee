<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\DetailPesanan;
use App\Models\Pelanggan;
USE App\Models\Produk;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    //PELANGGAN
    
    // ✅ Simpan Ulasan dari Pelanggan
    public function store(Request $request)
    {
        // Ambil token dari session
        $token = session('token');

        // Pastikan token valid dan ambil data pelanggan
        $pelanggan = Pelanggan::where('token', $token)->firstOrFail();

        // Validasi data ulasan
        $validated = $request->validate([
            'id_detail_pesanan' => 'required|exists:detail_pesanans,id_detail_pesanan',
            'id_produk' => 'required|exists:produks,id_produk',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:255',
        ]);

        // Ambil ID pesanan
        $detailPesanan = DetailPesanan::find($validated['id_detail_pesanan']);
        $id_pesanan = $detailPesanan->id_pesanan;

        // ✅ Cek apakah ulasan untuk produk ini dalam pesanan ini sudah ada
        $sudahAda = Ulasan::where('id_pelanggan', $pelanggan->id_pelanggan)
                          ->where('id_pesanan', $id_pesanan)
                          ->where('id_produk', $validated['id_produk'])
                          ->exists();

        if ($sudahAda) {
            return redirect()->back()->with('warning', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        // Simpan ulasan
        Ulasan::create([
            'id_pelanggan'    => $pelanggan->id_pelanggan,
            'id_pesanan'      => $id_pesanan,
            'id_produk'       => $validated['id_produk'],
            'rating'          => $validated['rating'],
            'komentar'        => $validated['komentar'],
            'tanggal_ulasan'  => now(),
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim!');
    }

    // Menampilkan riwayat ulasan pelanggan
    public function riwayatUlasan()
    {
        $token = session('token');
        $pelanggan = Pelanggan::where('token', $token)->first();

        if (!$pelanggan) {
            return redirect()->back()->with('warning', 'Pelanggan tidak valid atau belum login.');
        }

        $ulasan = Ulasan::with('produk')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->get();

        return view('ulasan.riwayat-ulasan', compact('ulasan'));
    }

    public function edit($id)
    {
        // Debug: Cek token
        $token = session('token');
        \Log::info('Token: ' . $token);
    
        $pelanggan = Pelanggan::where('token', $token)->first();
        if (!$pelanggan) {
            \Log::error('Pelanggan tidak ditemukan atau token tidak valid');
            return redirect()->route('login')->with('warning', 'Pelanggan tidak valid atau belum login.');
        }
    
        $ulasan = Ulasan::with('produk')->findOrFail($id);
    
        // Pastikan hanya pelanggan yang sesuai yang bisa mengedit ulasan ini
        if ($ulasan->id_pelanggan != $pelanggan->id_pelanggan) {
            return redirect()->route('riwayat.ulasan')->with('warning', 'Anda tidak dapat mengedit ulasan ini.');
        }
    
        return response()->json([
            'id' => $ulasan->id_ulasan,
            'komentar' => $ulasan->komentar,
            'rating' => $ulasan->rating,
            'nama_produk' => $ulasan->produk->nama_produk ?? 'Produk',
        ]);
    }
    
public function updateUlasan(Request $request, $id)
{
    // Validasi input
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'required|string|max:255',
    ]);

    // Ambil data ulasan berdasarkan ID
    $ulasan = Ulasan::findOrFail($id);

    // Update data ulasan
    $ulasan->update([
        'rating' => $validated['rating'],
        'komentar' => $validated['komentar'],
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('riwayat.ulasan')->with('success', 'Ulasan berhasil diperbarui!');
}

//PEGAWAI

public function ulasanIndex(Request $request)
{
    // Ambil semua produk untuk filter
    $produks = Produk::withCount('ulasan')->get();

    // Ambil ID produk yang difilter
    $produkId = $request->input('produk_id');

    // Query ulasan untuk perhitungan statistik (semua data)
    $queryAll = Ulasan::with('produk', 'pelanggan');
    if ($produkId) {
        $queryAll->where('id_produk', $produkId);
    }
    $ulasanAll = $queryAll->get();

    // Query ulasan untuk ditampilkan (dibatasi 50 per halaman)
    $queryDisplay = Ulasan::with('produk', 'pelanggan');
    if ($produkId) {
        $queryDisplay->where('id_produk', $produkId);
    }
    $ulasan = $queryDisplay->orderBy('rating', 'asc')->paginate(50);

    // Perhitungan metode CAST berdasarkan semua data
    $totalRating = $ulasanAll->sum('rating');
    $jumlahPemberiRating = $ulasanAll->count();
    $nilaiEkspektasi = $jumlahPemberiRating * 5;
    $nilaiPersepsi = $totalRating;
    $nilaiKepuasan = $nilaiEkspektasi > 0 ? ($nilaiPersepsi / $nilaiEkspektasi) * 100 : 0;

    return view('pegawai.ulasan.index', compact('ulasan', 'produks', 'produkId', 'nilaiPersepsi', 'nilaiEkspektasi', 'nilaiKepuasan', 'ulasanAll'));
}

}
