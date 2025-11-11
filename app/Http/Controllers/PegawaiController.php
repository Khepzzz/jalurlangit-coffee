<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Pegawai;
use Carbon\Carbon;

class PegawaiController extends Controller
{
    // Method untuk menampilkan halaman dashboard pegawai
    public function dashboard()
    {
        // Menghitung jumlah produk
        $totalProduk = Produk::count();
        
        // Menghitung pesanan yang belum selesai
        $pesananBelumSelesai = Pesanan::where('status_pesanan', '!=', 'Selesai')
                                  ->where('status_pesanan', '!=', 'Dibatalkan')
                                  ->count();
        // Menghitung total uang dari penjualan yang status pesannya 'Selesai'
        // Ambil parameter bulan dari request
        $bulan = request('bulan');

        // Query untuk menghitung total uang dari penjualan dengan status 'selesai'
        $query = Pesanan::where('status_pesanan', 'selesai');

        // Jika ada filter bulan, tambahkan kondisi ke query
        if ($bulan) {
            $query->whereMonth('tanggal_pesanan', $bulan);
        }

        // Hitung total penghasilan
        $totalUangPenjualan = $query->sum('total_harga');
        
        // Mengambil total penjualan per bulan
        $penjualanBulanan = Pesanan::selectRaw('SUM(total_harga) as total, MONTH(tanggal_pesanan) as bulan')
            ->whereYear('tanggal_pesanan', Carbon::now()->year) // Mengambil data untuk tahun berjalan
            ->groupBy('bulan')
            ->get();

        // Kirim data ke view
        return view('pegawai.dashboard', [
            'totalProduk' => $totalProduk,
            'pesananBelumSelesai' => $pesananBelumSelesai,
            'totalUangPenjualan' => $totalUangPenjualan,
            'penjualanBulanan' => $penjualanBulanan->pluck('total'),
            'bulan' => $penjualanBulanan->pluck('bulan'),
        ]);
    }

    
    // ========================
    // Data Pegawai
    // ========================

    // Menampilkan semua pegawai
    public function pegawaiIndex()
    {
         $pegawai = Pegawai::orderBy('nama_pegawai', 'asc')->paginate(10); 
         return view('pegawai.data-pegawai.index', compact('pegawai'));
    }

    // Menampilkan form tambah pegawai
    public function pegawaiCreate()
    {
        return view('pegawai.data-pegawai.create');
    }

    // Menyimpan data pegawai baru
    public function pegawaiStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawais,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Pegawai::create([
            'nama_pegawai' => $validated['nama_pegawai'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Hash password
        ]);

        return redirect()->route('pegawai.data-pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    // Menampilkan form edit pegawai
    public function pegawaiEdit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.data-pegawai.edit', compact('pegawai'));
    }

    // Update data pegawai
    public function pegawaiUpdate(Request $request, $id)
{
    $pegawai = Pegawai::findOrFail($id);

    $validated = $request->validate([
        'nama_pegawai' => 'required|string|max:255',
        'email' => 'required|email|unique:pegawais,email,' . $pegawai->id_pegawai . ',id_pegawai',
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $pegawai->nama_pegawai = $validated['nama_pegawai'];
    $pegawai->email = $validated['email'];

    // Update password jika diisi
    if (!empty($validated['password'])) {
        $pegawai->password = bcrypt($validated['password']);
    }

    $pegawai->save();

    return redirect()->route('pegawai.data-pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
}


    // Hapus pegawai
    public function pegawaiDestroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawai.data-pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
    
}
