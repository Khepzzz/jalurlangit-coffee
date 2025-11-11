@extends('produk.app')

@section('content')
@php $token = session('token'); @endphp

<div class="container-fluid px-4 py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="display-6 text-center mb-5 fw-bold">🛒 Keranjang Belanja Anda</h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show custom-success-alert mx-auto mt-3 shadow" role="alert" id="successAlert">
    <div class="d-flex align-items-center justify-content-center">
        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
        <div class="text-start">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    </div>
</div>


    <script>
        setTimeout(function () {
            let alert = document.getElementById('successAlert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 300);
            }
        }, 3000);
    </script>
    @endif

    @if(session("keranjang_$token") && count(session("keranjang_$token")) > 0)
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Detail Produk</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalHarga = 0; @endphp
                                @foreach(session("keranjang_$token") as $id => $item)
                                <tr class="align-middle">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center flex-column">
                                            <img src="{{ asset('storage/' . ($item['gambar_produk'] ?? 'default.png')) }}"
                                                 class="img-thumbnail me-3"
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 alt="{{ $item['nama'] }}">
                                            <div class="text-center">
                                                <span class="fw-bold">{{ $item['nama'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        Rp{{ number_format($item['harga'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('keranjang.update') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="id_produk" value="{{ $id }}">
                                                <input type="hidden" name="token" value="{{ $token }}">
                                                <button type="submit" name="action" value="kurang" class="btn btn-sm btn-outline-secondary">-</button>
                                                <span class="btn btn-sm btn-light px-3">{{ $item['jumlah'] }}</span>
                                                <button type="submit" name="action" value="tambah" class="btn btn-sm btn-outline-primary">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">
                                        Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('keranjang.hapus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_produk" value="{{ $id }}">
                                            <input type="hidden" name="token" value="{{ $token }}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @php $totalHarga += $item['subtotal']; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0">Total Harga</h4>
                </div>
                <div class="card-body">
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0">Total</h5>
                        <h4 class="text-primary fw-bold mb-0">
                            Rp{{ number_format($totalHarga, 0, ',', '.') }}
                        </h4>
                    </div>
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ session('token') }}">
                        <input type="hidden" name="nama_pelanggan" value="Nama Pelanggan">
                        <input type="hidden" name="nomor_kursi" value="12">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-cart-check me-2"></i>Checkout Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
<div class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body text-center p-5">
                <div class="empty-cart-icon mb-4">
                    <i class="bi bi-cart-x text-warning" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">Keranjang Anda Masih Kosong</h3>
                <p class="text-muted mb-4">Silakan tambahkan produk ke keranjang untuk mulai berbelanja</p>
                <a href="/produk" class="btn btn-primary btn-lg px-4 py-2">
                    <i class="bi bi-card-list me-2"></i> Jelajahi Menu
                </a>
            </div>
        </div>
    </div>
</div>
@endif
</div>
@endsection
