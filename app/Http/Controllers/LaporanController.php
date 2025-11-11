<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use App\Exports\LaporanExport;
class LaporanController extends Controller
{
public function laporanIndex(Request $request)
{
    $tanggal_awal = $request->input('tanggal_awal');
    $tanggal_akhir = $request->input('tanggal_akhir');
    $laporan = collect(); // Koleksi kosong sebagai default
    $total_penjualan = 0;

    // Jalankan query hanya jika tanggal_awal dan tanggal_akhir ada
    if ($tanggal_awal && $tanggal_akhir) {
        $laporan = Pesanan::with(['pelanggan', 'detailPesanan.produk', 'pembayaran'])
            ->where('status_pesanan', 'Selesai')
            ->whereBetween('tanggal_pesanan', [$tanggal_awal, $tanggal_akhir])
            ->orderByDesc('tanggal_pesanan')
            ->get();

        // Hitung total penjualan
        $total_penjualan = $laporan->sum('total_harga');
    }

    return view('pegawai.laporan.index', compact('laporan', 'total_penjualan', 'tanggal_awal', 'tanggal_akhir'));
}
    
        // Method untuk export laporan ke Excel
        public function exportToExcel(Request $request)
        {
            $tanggal_awal = $request->input('tanggal_awal');
            $tanggal_akhir = $request->input('tanggal_akhir');
        
            $laporan = Pesanan::with(['pelanggan', 'detailPesanan.produk', 'pembayaran'])
                ->where('status_pesanan', 'Selesai')
                ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                    $query->whereBetween('tanggal_pesanan', [$tanggal_awal, $tanggal_akhir]);
                })
                ->get();
        
            return Excel::download(new LaporanExport($laporan), 'laporan_penjualan.xlsx');
        }
        
    
        // Method untuk export laporan ke PDF

        public function exportToPDF(Request $request)
        {
            $tanggal_awal = $request->input('tanggal_awal');
            $tanggal_akhir = $request->input('tanggal_akhir');
        
            $laporan = Pesanan::with(['pelanggan', 'detailPesanan.produk', 'pembayaran'])
                ->where('status_pesanan', 'Selesai')
                ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                    $query->whereBetween('tanggal_pesanan', [$tanggal_awal, $tanggal_akhir]);
                })
                ->get();
        
            // Render HTML dari Blade view
            $html = view('pegawai.laporan.pdf', compact('laporan'))->render();
        
            // Buat PDF
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
        
            // Kembalikan sebagai response download
            return response($mpdf->Output('laporan_penjualan.pdf', 'S'))
                ->header('Content-Type', 'application/pdf');
        }
        
}