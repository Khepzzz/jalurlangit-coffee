<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // ==================== BAGIAN UNTUK PELANGGAN ==================== //

    // Menampilkan semua produk untuk pelanggan
    public function index(Request $request)
{
    // Ambil nilai pencarian dari input
    $search = $request->get('search');
    
    // Mengambil produk dengan rating tertinggi yang dihitung berdasarkan jumlah ulasan
    $produk = Produk::withCount('ulasan') // Menghitung jumlah ulasan
                    ->withAvg('ulasan', 'rating') // Menghitung rata-rata rating
                    ->when($search, function ($query, $search) {
                        // Menambahkan filter pencarian nama produk
                        return $query->where('nama_produk', 'like', '%'.$search.'%');
                    })
                    ->orderByRaw("CASE WHEN stok = 'tersedia' THEN 1 ELSE 2 END") // Mengurutkan produk yang tersedia di atas
                    ->orderByDesc('ulasan_count') // Mengurutkan berdasarkan jumlah ulasan terbanyak
                    ->orderByDesc('ulasan_avg_rating') // Mengurutkan berdasarkan rata-rata rating tertinggi
                    ->orderBy('nama_produk', 'asc') // Urutkan berdasarkan nama produk
                    ->get();
    
    return view('produk.index', compact('produk'));
}

    
    // Menampilkan detail produk untuk pelanggan
    public function show($id)
    {
        $produk = Produk::with(['ulasan.pelanggan'])->findOrFail($id);
        $ulasan = $produk->ulasan()->orderByDesc('tanggal_ulasan')->get();
        $jumlahUlasan = $ulasan->count();
    
        return view('produk.detail', compact('produk', 'ulasan', 'jumlahUlasan'));
    }
    
    // ==================== BAGIAN UNTUK PEGAWAI/ADMIN ==================== //

    // Menampilkan daftar produk (khusus pegawai/admin)
    public function adminIndex()
    {
        // Produk dipaginasi untuk admin
        $produk = Produk::orderBy('nama_produk', 'asc')->paginate(50); 
        return view('pegawai.produk.index', compact('produk'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        return view('pegawai.produk.create');
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|string',
            'deskripsi' => 'required|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['nama_produk', 'kategori', 'harga', 'stok', 'deskripsi']);

        // Menyimpan gambar produk jika ada
        if ($request->hasFile('gambar_produk')) {
            $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        // Simpan produk ke database
        Produk::create($data);

        return redirect()->route('pegawai.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan form edit produk
    public function edit($id)
    {
        // Ambil produk berdasarkan ID untuk diubah
        $produk = Produk::findOrFail($id);
        return view('pegawai.produk.edit', compact('produk'));
    }

    // Mengupdate produk
    public function update(Request $request, $id)
    {
        // Validasi inputan
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|string',
            'deskripsi' => 'required|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $produk = Produk::findOrFail($id);

        $data = $request->only(['nama_produk', 'kategori', 'harga', 'stok', 'deskripsi']);

        // Jika ada gambar baru di-upload
        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar_produk) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }
            // Simpan gambar baru
            $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        // Update produk di database
        $produk->update($data);

        return redirect()->route('pegawai.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus gambar produk jika ada
        if ($produk->gambar_produk) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        // Hapus produk dari database
        $produk->delete();

        return redirect()->route('pegawai.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    
}
