@extends('produk.app')

@section('content')
<div class="container text-center">
    <h2>Terima Kasih!</h2>
    <p>Pesanan Anda telah diterima dan sedang diproses.</p>
    <a href="{{ route('produk') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
