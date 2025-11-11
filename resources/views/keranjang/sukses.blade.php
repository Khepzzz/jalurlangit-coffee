@extends('produk.app')

@section('content')
<style>
    .success-box {
        background: #f8fff5;
        border: 1px solid #d4edda;
        border-radius: 16px;
        padding: 40px 30px;
        max-width: 600px;
        margin: 40px auto;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .success-icon {
        font-size: 60px;
        color: #28a745;
        margin-bottom: 20px;
    }

    .btn-bayar {
        background-color: #28a745;
        border: none;
        padding: 12px 28px;
        font-size: 16px;
        border-radius: 8px;
    }

    .btn-bayar:hover {
        background-color: #218838;
    }
</style>

<div class="container py-5">
    <div class="success-box text-center">
        <div class="success-icon">✅</div>
        <h2 class="text-success mb-3">Pesanan Berhasil Dibuat!</h2>
        <p class="lead mb-4">Terima kasih telah memesan di <strong>Jalur Langit Coffee</strong> ☕.  
        Silakan lanjutkan ke pembayaran untuk memproses pesanan Anda.</p>

        {{-- Ganti "#" dengan route atau URL halaman pembayaran --}}
        <a href="{{ url('/pembayaran/' . session('id_pesanan_terakhir')) }}" class="btn btn-bayar">
            💳 Bayar Sekarang
        </a>
    </div>
</div>
@endsection
