<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Menampilkan halaman pembayaran
     */
    public function index($id_pesanan)
    {
        $pesanan = Pesanan::findOrFail($id_pesanan);
            // Ambil detail pesanan yang terkait dengan pesanan ini
            $detailPesanan = DetailPesanan::where('id_pesanan', $id_pesanan)
            ->with('produk')  // Memuat data produk terkait
            ->get();
        // Cek apakah sudah ada pembayaran pending untuk pesanan ini
        $pembayaran = Pembayaran::where('id_pesanan', $id_pesanan)
                              ->where('status_pembayaran', 'pending')
                              ->first();
        
            // Tambahkan logika pembatalan otomatis jika lebih dari 30 menit
            if ($pesanan->tanggal_pesanan) {
                // Mengonversi tanggal_pesanan menjadi objek Carbon
                $batasWaktu = Carbon::parse($pesanan->tanggal_pesanan)->addMinutes(30);
            
                // Cek apakah waktu sekarang lebih dari batas waktu pembayaran
                if (now()->gt($batasWaktu) && $pesanan->status_pesanan === 'pending') {
                    // Ubah status pesanan menjadi dibatalkan
                    $pesanan->status_pesanan = 'dibatalkan';
                    $pesanan->save();
            
                    // Kembali ke halaman dengan pesan error
                    return redirect()->route('/produk')->with('error', 'Waktu pembayaran telah habis. Pesanan dibatalkan otomatis.');
                }
            }
        return view('pembayaran.index', compact('pesanan', 'pembayaran', 'detailPesanan'));
    }

    /**
     * Membuat pembayaran baru atau update pembayaran yang ada
     */
    public function buatPembayaran($id_pesanan, Request $request)
    {
        // Validasi input
        $request->validate([
            'metode_pembayaran' => 'required|in:QR,VA,DANA,GoPay',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        $pesanan = Pesanan::findOrFail($id_pesanan);
        
        // Cek apakah sudah ada pembayaran pending untuk pesanan ini
        $existingPembayaran = Pembayaran::where('id_pesanan', $id_pesanan)
                                     ->where('status_pembayaran', 'pending')
                                     ->first();
        
        if ($existingPembayaran) {
            // Update pembayaran yang sudah ada
            $pembayaran = $existingPembayaran;
            $pembayaran->metode_pembayaran = $request->input('metode_pembayaran');
            $pembayaran->tanggal_pembayaran = now(); // Update tanggal pembayaran
            
            // Hapus file bukti lama jika ada
            if ($pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            }
        } else {
            // Buat pembayaran baru
            $pembayaran = new Pembayaran();
            $pembayaran->id_pesanan = $pesanan->id_pesanan;
            $pembayaran->metode_pembayaran = $request->input('metode_pembayaran');
            $pembayaran->jumlah = $pesanan->total_harga;
            $pembayaran->tanggal_pembayaran = now();
            $pembayaran->status_pembayaran = 'proses'; // Ubah status ke proses karena bukti sudah diupload
        }
        
        // Upload bukti pembayaran
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $fileName = 'bukti_' . $pesanan->id_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti_pembayaran', $fileName, 'public');
            $pembayaran->bukti_pembayaran = $path;
        }
        
        // Simpan pembayaran
        $pembayaran->save();
        
        // Update status pesanan
        $pesanan->status_pesanan = 'diproses';
        $pesanan->save();
        
        return redirect()->route('pesanan.list', ['id_pesanan' => $pesanan->id_pesanan])
                        ->with('success', 'Pembayaran berhasil dikonfirmasi! Silahkan tunggu verifikasi dari pegawai.');
    }
    
/*******************************
 * PEGAWAI
 *******************************/
// Controller PembayaranController

public function daftarPembayaran($id_pesanan)
{
    // Mengambil pembayaran yang terkait dengan pesanan yang dipilih
    $pembayarans = Pembayaran::where('id_pesanan', $id_pesanan)
                               ->orderBy('tanggal_pembayaran', 'desc')
                               ->paginate(10);

    // Mengambil pesanan untuk kebutuhan tampilan
    $pesanan = Pesanan::findOrFail($id_pesanan);

    return view('pegawai.pembayaran.index', compact('pembayarans', 'pesanan'));
}

/**
 * pegawai - Memperbarui status pembayaran
 */
public function updateStatusPembayaran(Request $request, $id_pembayaran)
{
    // Validasi status pembayaran
    $request->validate([
        'status_pembayaran' => 'required|in:pending,proses,dibayar',
    ]);

    // Menemukan pembayaran dan memperbarui status
    $pembayaran = Pembayaran::findOrFail($id_pembayaran);
    $pembayaran->status_pembayaran = $request->input('status_pembayaran');
    $pembayaran->save();

    return redirect()->route('pegawai.pembayaran.daftar', ['id_pesanan' => $pembayaran->id_pesanan])
                     ->with('success', 'Status pembayaran berhasil diperbarui!');
}

    
}