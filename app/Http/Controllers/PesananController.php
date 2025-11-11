<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    // =========================
    // PELANGGAN
    // =========================

    // Simpan pesanan dari pelanggan
    public function store(Request $request)
    {
        $keranjang = session()->get('keranjang');

        if (!$keranjang || count($keranjang) == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();
        try {
            // Simpan data pesanan
            $pesanan = Pesanan::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'nomor_kursi' => $request->nomor_kursi,
                'total_harga' => array_sum(array_column($keranjang, 'subtotal')),
                'status_pesanan' => 'Pending',
                'tanggal_pesanan' => now(),
            ]);

            // Simpan detail pesanan
            foreach ($keranjang as $id_produk => $item) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk' => $id_produk,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Kosongkan keranjang setelah checkout
            session()->forget('keranjang');

            DB::commit();
            return redirect('/pembayaran/' . $pesanan->id_pesanan)->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat checkout.');
        }
    }

    // Tampilkan detail pesanan untuk pelanggan
    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk'])->findOrFail($id);
        return view('pesanan.show', compact('pesanan'));
    }

    // Menampilkan daftar pesanan pelanggan berdasarkan token
    public function listPesanan(Request $request)
    {
        $token = session('token');
        $pelanggan = Pelanggan::where('token', $token)->firstOrFail();

    // Ambil semua pesanan milik pelanggan ini
    $pesanan = Pesanan::with(['detailPesanan.produk', 'pelanggan'])
        ->where('id_pelanggan', $pelanggan->id_pelanggan)
        ->orderBy('tanggal_pesanan', 'desc')
        ->get();

    return view('pesanan.index', compact('pesanan'));
    }
    
// Menghapus produk dari detail pesanan (hanya jika status pesanan masih pending)
public function hapusDetail($id)
{
    $detail = DetailPesanan::findOrFail($id); 
    $pesanan = $detail->pesanan; // Ambil relasi langsung

    // Kurangi total harga
    $pesanan->total_harga -= $detail->subtotal;
    $pesanan->save();

    // Hapus detail pesanan
    $detail->delete();

    // Jika tidak ada detail tersisa, hapus pesanan
    if ($pesanan->detailPesanan()->count() == 0) {
        $pesanan->delete();
    }

    return redirect()->back()->with('success', 'Produk berhasil dihapus dari pesanan.');
}
// Konfirmasi pesanan diterima oleh pelanggan
public function terimaOrderPelanggan($id)
{
    $pesanan = Pesanan::findOrFail($id);
    
    // Check payment status
    $pembayaran = Pembayaran::where('id_pesanan', $id)
                    ->where('status_pembayaran', 'dibayar')
                    ->first();
    
    // Hanya boleh mengubah status jika pesanan sedang diproses dan sudah dibayar
    if ($pesanan->status_pesanan == 'diproses' && $pembayaran) {
        $pesanan->status_pesanan = 'selesai';
        $pesanan->save();
        
        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi diterima.');
    }
    
    return redirect()->back()->with('error', 'Status pesanan tidak dapat diubah. Pastikan sudah dibayar dan sedang diproses.');
}
    // =========================
    // PEGAWAI
    // =========================

    // Daftar semua pesanan (admin/pegawai)
    public function pesananIndex(Request $request)
    {
        $query = Pesanan::with('pelanggan');
        
        // Filter berdasarkan status pesanan jika ada
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_pesanan', $request->status);
        }
    
        // Urutkan berdasarkan status pesanan (Proses di atas, Pending setelah itu, Selesai dan Dibatalkan di bawah)
        // Kemudian urutkan berdasarkan tanggal pesanan (terlama di atas)
        $daftar_pesanan = $query->orderByRaw("FIELD(status_pesanan, 'Diproses') DESC")  // Proses di atas
                                ->orderByRaw("FIELD(status_pesanan, 'Pending') DESC")  // Pending di bawah proses
                                ->orderByRaw("FIELD(status_pesanan, 'Selesai', 'Dibatalkan') ASC")  // Selesai dan Dibatalkan di bawah
                                ->orderBy('tanggal_pesanan', 'asc')  // Urutkan berdasarkan tanggal (terlama di atas)
                                ->paginate(25);
    
        return view('pegawai.pesanan.index', compact('daftar_pesanan'));
    }
    

    // Detail satu pesanan untuk pegawai
    public function pesananShow($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk'])->findOrFail($id);
        return view('pegawai.pesanan.show', compact('pesanan'));
    }

    // Update status pesanan oleh pegawai
    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'required|in:Pending,Diproses,Selesai,Dibatalkan',
        ]);
    
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status_pesanan = $request->status_pesanan;
        $pesanan->save();
    
        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
