@extends('produk.app')

@section('title', 'Menu Produk | Jalur Langit Coffee')

@section('styles')
<style>
    /* Styling tambahan khusus untuk halaman produk */
    .btn-category {
        margin: 0 8px 12px;
        min-width: 110px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.25s ease;
        padding: 8px 16px;
    }
    
    .btn-category.active {
        background-color: var(--primary-color);
        color: var(--light-text);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .card-title {
        font-size: 1.25rem;
        height: 52px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 10px;
        font-family: 'Playfair Display', serif;
        color: var(--primary-dark);
    }
    
    .rating-container {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }
    
    .card .badge {
        font-size: 0.85rem;
        padding: 6px 12px;
        margin-bottom: 12px;
        display: inline-block;
        border-radius: 6px;
        font-weight: 500;
    }

    .badge-makanan {
    background-color:rgb(10, 160, 62); /* Warna oranye cerah untuk makanan */
    color: white;
    box-shadow: 0 2px 6px rgba(255, 152, 0, 0.3); /* Menambahkan shadow lembut */
    transition: background-color 0.3s ease, transform 0.3s ease; /* Animasi transisi */
}

.badge-minuman {
    background-color:rgb(9, 67, 148); /* Warna hijau cerah untuk minuman */
    color: white;
    box-shadow: 0 2px 6px rgba(76, 175, 80, 0.3); /* Shadow lembut */
    transition: background-color 0.3s ease, transform 0.3s ease; /* Animasi transisi */
}

    
    .badge-secondary {
        background-color: var(--secondary-color);
        color: var(--text-color);
    }
    
    .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }
    
    .filter-container {
        background: linear-gradient(to right, rgba(58, 107, 95, 0.05), rgba(58, 107, 95, 0.1));
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 40px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        border-left: 4px solid var(--primary-color);
    }
    
    .section-title {
        position: relative;
        margin-bottom: 35px;
        padding-bottom: 15px;
        font-family: 'Playfair Display', serif;
        color: var(--primary-dark);
        font-weight: 600;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 60px;
        height: 3px;
        background: var(--secondary-color);
    }
    
    .hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3') no-repeat center center/cover;
        padding: 120px 0;
        margin-bottom: 60px;
    }
    
    .hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
    }
    
    .price {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.1rem;
        margin-bottom: 8px;
    }
    
    .star-icon {
        color: var(--secondary-color);
        font-size: 1rem;
    }
    
    .action-buttons {
    display: flex;
    gap: 6px; /* Jarak antar tombol bisa dikurangi */
    margin-top: auto;
    flex-wrap: wrap; /* Tambahan jika mau responsif */
    justify-content: space-between; /* Atau 'start' jika ingin lebih rapat */
    }
    
    .action-button {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 0;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-add {
        background: linear-gradient(90deg, var(--secondary-color), var(--secondary-dark));
        color: var(--text-color);
        border: none;
    }
    
    .btn-add:hover {
        background: linear-gradient(90deg, var(--secondary-dark), #b47a30);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .btn-detail {
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        color: var(--light-text);
        border: none;
    }
    
    .btn-detail:hover {
        background: linear-gradient(90deg, #345e54, var(--primary-color));
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .kategori-label {
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 15px;
    }
    
    #emptyState {
        padding: 60px 0;
        background-color: rgba(58, 107, 95, 0.05);
        border-radius: 12px;
        border: 1px dashed rgba(58, 107, 95, 0.3);
    }
    
    #emptyState i {
        color: var(--primary-color);
        opacity: 0.5;
    }
    
    #emptyState h3 {
        font-family: 'Playfair Display', serif;
        color: var(--primary-dark);
    }
    
    /* Add animation for products */
    .produk-item {
        transition: all 0.4s ease;
        opacity: 1;
        transform: translateY(0);
    }
    
    .produk-item.hidden {
        opacity: 0;
        transform: translateY(20px);
    }
    
    /* Product card improvements */
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }
    
    .sold-out-overlay {
        background: rgba(0, 0, 0, 0.7);
        font-weight: 600;
        letter-spacing: 2px;
    }
</style>
@endsection

@section('hero')
<div class="hero">
    <div class="container">
        <h1>Nikmati Secangkir Inspirasi</h1>
        <p class="lead text-white mt-3">Tempat yang aman bagi semua orang untuk berkumpul, berkolaborasi, dan mendapatkan inspirasi.</p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="section-title">Menu Kami</h2>
        
        
        <!-- Filter Kategori -->
        <div class="filter-container mb-4">
            <form method="GET" action="/produk" class="d-flex mb-3">
    <input type="text" name="search" class="form-control me-2" placeholder="Cari produk..." value="{{ request()->get('search') }}">
    <button type="submit" class="btn btn-primary">Cari</button>
</form>
            <h5 class="kategori-label text-center">Pilih Kategori</h5>
            <div class="d-flex justify-content-center flex-wrap">
                <button class="btn btn-outline btn-category active" onclick="filterProduk('semua', this)">
                    <i class="bi bi-grid me-2"></i>Semua
                </button>
                <button class="btn btn-outline btn-category" onclick="filterProduk('makanan', this)">
                    <i class="bi bi-egg-fried me-2"></i>Makanan
                </button>
                <button class="btn btn-outline btn-category" onclick="filterProduk('minuman', this)">
                    <i class="bi bi-cup-hot me-2"></i>Minuman
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Produk -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="produkContainer">
    @foreach ($produk as $item)
        @php
            $ulasan = $item->ulasan ?? collect();
            $totalRating = $ulasan->sum('rating');
            $jumlahUlasan = $ulasan->count();
            $averageRating = $jumlahUlasan > 0 ? $totalRating / $jumlahUlasan : 0;
        @endphp

        <div class="col produk-item" data-kategori="{{ $item->kategori }}">
            <div class="card h-100 d-flex flex-column">
                <div class="position-relative overflow-hidden">
                    <img src="{{ $item->gambar_produk ? asset('storage/' . $item->gambar_produk) : 'https://via.placeholder.com/400x300?text=No+Image' }}" class="card-img-top produk-img" alt="{{ $item->nama_produk }}">
                    @if ($item->stok == 'habis')
                        <div class="sold-out-overlay">HABIS</div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $item->nama_produk }}</h5>
                    <p class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    <span class="badge 
                @if($item->kategori == 'makanan') badge-makanan
                @else($item->kategori == 'minuman') badge-minuman
                @endif
                mb-2">
                {{ ucfirst($item->kategori) }}
            </span>
                    <div class="rating-container">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating))
                                <i class="bi bi-star-fill star-icon"></i>
                            @elseif ($i - $averageRating < 1)
                                <i class="bi bi-star-half star-icon"></i>
                            @else
                                <i class="bi bi-star star-icon"></i>
                            @endif
                        @endfor
                        <small class="text-muted ms-2">
                            {{ number_format($averageRating, 1) }} ({{ $jumlahUlasan }} ulasan)
                        </small>
                    </div>
                    <div class="action-buttons mt-auto">
                        @if ($item->stok == 'tersedia')
                            <form action="/keranjang/tambah" method="POST" class="w-100">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">
                                <input type="hidden" name="jumlah" value="1">
                                <button type="submit" class="btn action-button btn-add w-100">
                                    <i class="bi bi-cart-plus"></i> Tambah
                                </button>
                            </form>
                        @else
                            <button disabled class="btn action-button btn-secondary">
                                <i class="bi bi-x-circle"></i> Stok Habis
                            </button>
                        @endif
                        <a href="/produk/{{ $item->id_produk }}" class="btn action-button btn-detail">
                            <i class="bi bi-info-circle"></i> Detail Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Empty state jika tidak ada produk -->
<div id="emptyState" class="text-center py-5 d-none">
    <i class="bi bi-search display-1"></i>
    <h3 class="mt-4">Produk Tidak Ditemukan</h3>
    <p class="text-muted">Maaf, tidak ada produk dalam kategori ini.</p>
    <div class="d-flex justify-content-center mt-3">
        <button class="btn btn-primary" onclick="filterProduk('semua', document.querySelector('.btn-category'))">
            <i class="bi bi-arrow-repeat me-2"></i> Tampilkan Semua Produk
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function filterProduk(kategori, btn) {
        const items = document.querySelectorAll('.produk-item');
        const buttons = document.querySelectorAll('.btn-category');
        const emptyState = document.getElementById('emptyState');

        // Ubah tampilan kategori yang aktif
        buttons.forEach(button => button.classList.remove('active'));
        btn.classList.add('active');

        // Filter produk sesuai kategori
        let visibleCount = 0;
        
        items.forEach(item => {
            const itemKategori = item.getAttribute('data-kategori');
            const shouldShow = (kategori === 'semua' || itemKategori === kategori);
            
            // Add animation effect
            item.classList.add('hidden');
            
            setTimeout(() => {
                if (shouldShow) {
                    item.style.display = 'block';
                    visibleCount++;
                    setTimeout(() => {
                        item.classList.remove('hidden');
                    }, 50);
                } else {
                    item.style.display = 'none';
                }
            }, 300);
        });
        
        // Toggle empty state message with delay to match animation
        setTimeout(() => {
            if (visibleCount === 0) {
                emptyState.classList.remove('d-none');
            } else {
                emptyState.classList.add('d-none');
            }
        }, 350);
    }
    
    // Initial animation for products
    document.addEventListener('DOMContentLoaded', function() {
        const items = document.querySelectorAll('.produk-item');
        items.forEach((item, index) => {
            item.classList.add('hidden');
            setTimeout(() => {
                item.classList.remove('hidden');
            }, 100 + (index * 100));
        });
    });
</script>
@endsection