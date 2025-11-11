<?php

use App\Models\Pesanan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;
use \App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AuthPegawaiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\MidtransPaymentController;

// =======================
// HALAMAN PELANGGAN (Tanpa Login)
// =======================

//Token
// Token & Identitas
Route::get('/', [PelangganController::class, 'token']); // Halaman Ambil Token + Input Identitas
Route::post('/ambil-token', [PelangganController::class, 'generateToken']); // Generate Token
Route::post('/store-identitas', [PelangganController::class, 'storeTokenData']); // Simpan Identitas
Route::post('/cek-token', [PelangganController::class, 'cekToken']);
Route::post('/get-data-pelanggan', [PelangganController::class, 'getDataPelanggan']);

Route::middleware('check.token')->group(function () {
// Produk
Route::get('/produk', [ProdukController::class, 'index']); // Tampilkan daftar produk
Route::get('/produk/{id_produk}', [ProdukController::class, 'show']); // Detail produk

//keranjang
Route::get('/keranjang', [KeranjangController::class, 'lihatKeranjang'])->name('keranjang.lihat');
Route::post('/keranjang/tambah', [KeranjangController::class, 'tambahKeKeranjang'])->name('keranjang.tambah');
Route::post('/keranjang/hapus', [KeranjangController::class, 'hapusDariKeranjang'])->name('keranjang.hapus');
Route::post('/keranjang/update', [KeranjangController::class, 'updateJumlah'])->name('keranjang.update');
Route::post('/checkout', [KeranjangController::class, 'checkout'])->name('checkout');

// Pesanan
Route::post('/pesan', [PesananController::class, 'store']); // Buat pesanan
Route::get('/pesanan/{id_pesanan}', [PesananController::class, 'show'])->name('pesanan.show'); // Detail pesanan pelanggan
// Lihat semua pesanan pelanggan (berdasarkan token)
Route::get('/pesanan', [PesananController::class, 'listPesanan'])->name('pesanan.list');
Route::put('/pesanan/terima/{id}', [PesananController::class, 'terimaOrderPelanggan'])->name('pesanan.terima');

// Batalkan pesanan (hanya jika status masih Pending)
Route::delete('/pesanan/{id}', [PesananController::class, 'hapusPesanan'])->name('pesanan.hapus');
Route::delete('/pesanan/{id}/hapus-detail', [PesananController::class, 'hapusDetail'])->name('pesanan.hapus-detail');

// Pembayaran
Route::prefix('pembayaran')->group(function () {
    Route::get('/{id_pesanan}', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/{id_pesanan}', [PembayaranController::class, 'buatPembayaran'])->name('pembayaran.buat');
});

// Ulasan
Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
Route::get('/ulasan', [UlasanController::class, 'riwayatUlasan'])->name('riwayat.ulasan');
Route::get('/ulasan/{id}/edit', [UlasanController::class, 'edit'])->name('ulasan.edit');
Route::put('/ulasan/{id}', [UlasanController::class, 'updateUlasan'])->name('ulasan.update');
});



// =======================
// HALAMAN PEGAWAI (Login Khusus)
// =======================

// Login Pegawai
// Form login pegawai
Route::get('/pegawai/login', [AuthPegawaiController::class, 'showLoginForm'])->name('pegawai.login');
Route::post('/pegawai/login', [AuthPegawaiController::class, 'login'])->name('pegawai.login.submit');
Route::post('/pegawai/logout', [AuthPegawaiController::class, 'logout'])->name('pegawai.logout');
// From Register Pegawai
Route::get('/pegawai/register', [AuthPegawaiController::class, 'showRegisterForm'])->name('pegawai.register');
Route::post('/pegawai/register', [AuthPegawaiController::class, 'register'])->name('pegawai.register.submit');


// Semua route pegawai hanya untuk yang sudah login sebagai pegawai
Route::middleware('auth:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PegawaiController::class, 'dashboard'])->name('dashboard');

    // Verifikasi Pembayaran dan Status Pesanan
    Route::post('/verifikasi-pembayaran/{id}', [PegawaiController::class, 'verifikasiPembayaran'])->name('verifikasi.pembayaran');
    Route::post('/update-status-pesanan/{id}', [PegawaiController::class, 'updateStatusPesanan'])->name('update.status.pesanan');

    // ====================
    // Manajemen Produk
    // ====================
    Route::prefix('produk')->name('produk.')->group(function () {
        Route::get('/', [ProdukController::class, 'adminIndex'])->name('index');
        Route::get('/tambah', [ProdukController::class, 'create'])->name('create');
        Route::post('/', [ProdukController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProdukController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('destroy');
    });

    // ====================
    // Kelola Pesanan
    // ====================
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [PesananController::class, 'pesananIndex'])->name('index');
        Route::get('/{id}', [PesananController::class, 'pesananShow'])->name('show');
        Route::post('/{id}/update-status', [PesananController::class, 'updateStatusPesanan'])->name('updateStatus');
    });

    // ====================
    // Kelola Pembayaran
    // ====================
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/{id_pesanan}', [PembayaranController::class, 'daftarPembayaran'])->name('daftar');
        Route::get('/{id_pembayaran}', [PembayaranController::class, 'detailPembayaran'])->name('detail');
        Route::put('/{id_pembayaran}', [PembayaranController::class, 'updateStatusPembayaran'])->name('update');
    });

    // ====================
    // Kelola Metode Pembayaran
    // ====================
    Route::prefix('metode')->name('metode.')->group(function () {
        Route::get('/', [MetodePembayaranController::class, 'index'])->name('index');
        Route::post('/', [MetodePembayaranController::class, 'store'])->name('store');
        Route::put('/{id}', [MetodePembayaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [MetodePembayaranController::class, 'destroy'])->name('destroy');
    });

    // ====================
    // Manajemen Ulasan
    // ====================
    Route::prefix('ulasan')->name('ulasan.')->group(function () {
        Route::get('/', [UlasanController::class, 'ulasanIndex'])->name('index');
        Route::delete('/{id}', [UlasanController::class, 'ulasanDestroy'])->name('destroy');
    });

    // ====================
    // Data Pegawai
    // ====================
    Route::prefix('data-pegawai')->name('data-pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'pegawaiIndex'])->name('index');
        Route::get('/tambah', [PegawaiController::class, 'pegawaiCreate'])->name('create');
        Route::post('/', [PegawaiController::class, 'pegawaiStore'])->name('store');
        Route::get('/{id}/edit', [PegawaiController::class, 'pegawaiEdit'])->name('edit');
        Route::put('/{id}', [PegawaiController::class, 'pegawaiUpdate'])->name('update');
        Route::delete('/{id}', [PegawaiController::class, 'pegawaiDestroy'])->name('destroy');
    });

    // ====================
    // Laporan (Optional)
    // ====================
    Route::get('/laporan', [LaporanController::class, 'laporanIndex'])->name('laporan.index');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportToExcel'])->name('laporan.export.excel');
Route::get('/laporan/export/pdf', [LaporanController::class, 'exportToPDF'])->name('laporan.export.pdf');

});
