@extends('produk.app')

@section('title', $produk->nama_produk . ' - Detail Produk')

@section('content')

<style>
    /* HERO SECTION */
    .hero {
        position: relative;
        background: url("{{ $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : 'https://via.placeholder.com/1200x500?text=No+Image' }}") no-repeat center;
        background-size: cover;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.6));
    }
    .hero-content {
        position: relative;
        z-index: 2;
        animation: fadeIn 1.2s ease-in-out;
        max-width: 80%;
    }
    .hero h1 {
        font-size: 44px;
        font-weight: 800;
        margin-bottom: 15px;
    }
    .hero .badge-kategori {
        font-size: 16px;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        color: white;
        border-radius: 30px;
        margin-bottom: 10px;
        display: inline-block;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* CONTAINER DETAIL PRODUK */
    .detail-produk {
        max-width: 1100px;
        margin: auto;
        padding: 35px 25px;
        background: white;
        border-radius: 16px;
        box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.08);
    }
    
    /* INFO PRODUK */
    .product-info {
        padding: 0 10px;
        width: 100%;
    }
    .product-info h2 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #2d3748;
    }
    .price-tag {
        font-size: 28px;
        font-weight: 700;
        color: #10b981;
        margin: 15px 0;
    }

    /* BADGE KATEGORI */
    .badge {
        font-size: 14px;
        padding: 6px 16px;
        background: #343a40;
        color: white;
        border-radius: 25px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* RATING STARS */
    .rating-stars {
        font-size: 18px;
        margin: 15px 0;
        display: flex;
        align-items: center;
    }
    .rating-count {
        color: #64748b;
        margin-left: 8px;
    }

    /* DESKRIPSI */
    .product-description {
        margin: 25px 0;
        color: #4b5563;
        line-height: 1.7;
        background: #f9fafb;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid #60a5fa;
        width: 100%;
        min-height: 120px; /* Ensure minimum height for description box */
    }
    .description-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #374151;
        display: block;
    }

    /* TOMBOL */
    .btn-primary, .btn-outline-secondary {
        padding: 12px 20px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-primary {
        background-color: #d4a373;
        border-color: #d4a373;
        color: #fff;
    }
    
    .btn-primary:hover {
        background-color: #c89b69;
        border-color: #c89b69;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    
    .btn-outline-secondary {
        border: 2px solid #6b7280;
        color: #4b5563;
        background-color: transparent;
    }
    .btn-outline-secondary:hover {
        background: #f3f4f6;
        color: #1f2937;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* BUTTON CONTAINER */
    .button-container {
        margin-top: 25px;
    }
    
    /* JUMLAH INPUT */
    .quantity-control {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .jumlah-input {
        max-width: 120px;
        text-align: center;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        padding: 12px;
        font-weight: 600;
    }

    /* STOCK STATUS */
    .stock-status {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .in-stock {
        background: #dcfce7;
        color: #166534;
    }
    .out-of-stock {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* PRODUCT INFO CONTAINER */
    .product-info-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* TOP INFO SECTION - fixed height */
    .product-top-info {
        margin-bottom: 20px;
    }

    /* CONTENT SECTION - flexible height */
    .product-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    /* BUTTON GROUP - fixed position at bottom */
    .button-group {
        margin-top: auto;
    }

/* Ulasan Produk */
.reviews-section {
    margin-top: 50px;
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.reviews-section h4 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #2d3748;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

/* Membuat ulasan menjadi scrollable vertikal */
.ulasan-container-wrapper {
    max-height: 400px;  /* Tentukan tinggi maksimum yang Anda inginkan */
    overflow-y: auto;  /* Scroll hanya vertikal */
    padding-right: 10px;  /* Agar tidak terhalang oleh scrollbar */
    display: flex;
    flex-direction: column; /* Menjaga ulasan tampil vertikal */
}

.ulasan-container {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
    transition: transform 0.2s ease;
    border: 1px solid #f1f5f9;
    margin-bottom: 15px; /* Memberi jarak antar ulasan */
}

.ulasan-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
}

.reviewer-name {
    font-size: 16px;
    font-weight: 700;
    color: #334155;
}

.review-content {
    font-size: 15px;
    color: #64748b;
    margin-top: 10px;
    line-height: 1.6;
}

.review-date {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 8px;
    display: block;
}

.no-reviews {
    background: #f8fafc;
    padding: 30px;
    text-align: center;
    border-radius: 12px;
    color: #64748b;
    font-style: italic;
}

/* KATEGORI BADGE */
.kategori-badge {
    display: inline-block;
    background-color: #343a40;
    color: white;
    padding: 6px 16px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Star rating */
.star-rating {
    display: inline-flex;
    color: #FFD700;
}

/* Empty description placeholder */
.empty-description {
    color: #6c757d;
    font-style: italic;
}
</style>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <span class="badge-kategori">{{ ucfirst($produk->kategori) }}</span>
        <h1>{{ $produk->nama_produk }}</h1>
        
        <!-- Rating Preview in Hero -->
        @php
            $ulasan = $produk->ulasan ?? collect();
            $totalRating = $ulasan->sum('rating');
            $jumlahUlasan = $ulasan->count();
            $averageRating = $jumlahUlasan > 0 ? $totalRating / $jumlahUlasan : 0;
        @endphp
        <div>
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= floor($averageRating))
                    <i class="bi bi-star-fill text-warning"></i>
                @elseif ($i - $averageRating < 1)
                    <i class="bi bi-star-half text-warning"></i>
                @else
                    <i class="bi bi-star text-warning"></i>
                @endif
            @endfor
            <span class="ms-2 text-white">{{ number_format($averageRating, 1) }} ({{ $jumlahUlasan }} ulasan)</span>
        </div>
    </div>
</div>

<div class="container detail-produk">
    <div class="row">
        <!-- Informasi Produk - Using a single column for better layout on smaller screens -->
        <div class="col-lg-8 offset-lg-2">
            <div class="product-info-container">
                <!-- Top Product Info (Fixed Height) -->
                <div class="product-top-info">
                    <h2>{{ $produk->nama_produk }}</h2>
                    
                    <div class="d-flex align-items-center flex-wrap">
                        <span class="kategori-badge me-3">{{ ucfirst($produk->kategori) }}</span>
                        
                        <!-- Status Stok -->
                        @if ($produk->stok == 'tersedia')
                            <div class="stock-status in-stock">
                                <i class="bi bi-check-circle"></i> Stok Tersedia
                            </div>
                        @else
                            <div class="stock-status out-of-stock">
                                <i class="bi bi-x-circle"></i> Stok Habis
                            </div>
                        @endif
                    </div>
                    
                    <div class="price-tag mt-3">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>

                    <!-- Rating -->
                    <div class="rating-stars">
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <i class="bi bi-star-fill"></i>
                                @elseif ($i - $averageRating < 1)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-count">({{ $jumlahUlasan }} ulasan)</span>
                    </div>
                </div>

                <!-- Product Content (Flexible Height) -->
                <div class="product-content">
                    <!-- Deskripsi -->
                    <div class="product-description">
                        <span class="description-label">Deskripsi Produk:</span>
                        @if($produk->deskripsi)
                            {{ $produk->deskripsi }}
                        @else
                            <p class="empty-description">Tidak ada deskripsi tersedia untuk produk ini.</p>
                        @endif
                    </div>

                    <!-- Button Group (Fixed at Bottom) -->
                    <div class="button-group">
                        <!-- Form Tambah ke Keranjang -->
                        @if ($produk->stok == 'tersedia')
                            <form action="/keranjang/tambah" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                                </button>
                            </form>
                        @endif

                        <a href="/produk" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ulasan Produk -->
    <div class="reviews-section mt-5">
        <h4><i class="bi bi-chat-quote me-2"></i>Ulasan Produk</h4>

        @if ($jumlahUlasan > 0)
            <div class="ulasan-container-wrapper">
                <div class="row">
                    @foreach ($ulasan as $review)
                        <div class="col-md-12">
                            <div class="ulasan-container rounded p-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">{{ $review->pelanggan->nama ?? 'Anonim' }}</span>
                                    <div>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mb-2 text-muted" style="white-space: pre-line;">{{ $review->komentar }}</p>
                                <small class="text-muted d-block mt-auto">{{ \Carbon\Carbon::parse($review->tanggal_ulasan)->format('d F Y') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center my-4">
                <i class="bi bi-chat-left text-muted fs-1 d-block mb-3"></i>
                <p class="mb-0">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
            </div>
        @endif
    </div>
</div>

@endsection