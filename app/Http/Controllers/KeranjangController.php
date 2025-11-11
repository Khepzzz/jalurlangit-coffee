<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pelanggan;

class KeranjangController extends Controller
{
    // Menampilkan isi keranjang
    public function lihatKeranjang()
    {
        $token = session('token');

        if (!$token) {
            return redirect('/')->with('error', 'Silakan ambil token terlebih dahulu.');
        }

        $keranjang = session()->get("keranjang_$token", []);
        return view('keranjang.keranjang', compact('keranjang'));
    }

    // Menambahkan produk ke keranjang (session)
    public function tambahKeKeranjang(Request $request)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/')->with('error', 'Silakan ambil token terlebih dahulu.');
        }

        $produk = Produk::find($request->id_produk);

        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $keranjang = session()->get("keranjang_$token", []);

        // Jika produk sudah ada di keranjang, tambahkan jumlahnya
        if (isset($keranjang[$produk->id_produk])) {
            $keranjang[$produk->id_produk]['jumlah'] += 1;
            $keranjang[$produk->id_produk]['subtotal'] = $keranjang[$produk->id_produk]['jumlah'] * $produk->harga;
        } else {
            // Jika produk baru pertama kali ditambahkan ke keranjang
            $keranjang[$produk->id_produk] = [
                'nama' => $produk->nama_produk,
                'harga' => $produk->harga,
                'jumlah' => 1,
                'subtotal' => $produk->harga,
                'gambar_produk' => $produk->gambar_produk ?? 'default.png'
            ];
        }

        session()->put("keranjang_$token", $keranjang);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    // Menghapus produk dari keranjang
    public function hapusDariKeranjang(Request $request)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/')->with('error', 'Silakan ambil token terlebih dahulu.');
        }

        $keranjang = session()->get("keranjang_$token", []);

        if (isset($keranjang[$request->id_produk])) {
            unset($keranjang[$request->id_produk]);
            session()->put("keranjang_$token", $keranjang);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    // Mengubah jumlah produk di keranjang
    public function updateJumlah(Request $request)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/')->with('error', 'Silakan ambil token terlebih dahulu.');
        }

        $keranjang = session()->get("keranjang_$token", []);

        if (isset($keranjang[$request->id_produk])) {
            if ($request->action === 'tambah') {
                $keranjang[$request->id_produk]['jumlah'] += 1;
            } elseif ($request->action === 'kurang' && $keranjang[$request->id_produk]['jumlah'] > 1) {
                $keranjang[$request->id_produk]['jumlah'] -= 1;
            }

            $keranjang[$request->id_produk]['subtotal'] = $keranjang[$request->id_produk]['jumlah'] * $keranjang[$request->id_produk]['harga'];

            session()->put("keranjang_$token", $keranjang);
        }

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    // Proses checkout
    public function checkout(Request $request)
    {
        $token = $request->token;

        $keranjang = session()->get("keranjang_$token", []);

        if (empty($keranjang)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        // Validasi token
        $request->validate([
            'token' => 'required|exists:pelanggans,token'
        ]);

        // Cari pelanggan berdasarkan token
        $pelanggan = Pelanggan::where('token', $token)->first();

        if (!$pelanggan || $pelanggan->expired_at < now('Asia/Jakarta')) {
            return back()->with('error', 'Token tidak valid atau sudah kadaluarsa.');
        }

        // Hitung total harga pesanan
        $totalHarga = array_sum(array_column($keranjang, 'subtotal'));

        // Simpan pesanan ke database
        $pesanan = Pesanan::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'total_harga' => $totalHarga,
            'tanggal_pesanan' => now(),
            'status_pesanan' => 'Pending'
        ]);

        // Simpan detail pesanan ke database
        foreach ($keranjang as $id_produk => $item) {
            DetailPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_produk' => $id_produk,
                'jumlah' => $item['jumlah'],
                'subtotal' => $item['subtotal']
            ]);
        }

        // Hapus session keranjang untuk token ini
        session()->forget("keranjang_$token");

        return redirect()->route('pembayaran.index', ['id_pesanan' => $pesanan->id_pesanan])->with('success', 'Pesanan berhasil dibuat.');

    }
}
