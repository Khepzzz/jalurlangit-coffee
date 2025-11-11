@extends('produk.app')

@section('content')
@php
    $token = session('token');
    $pelanggan = \App\Models\Pelanggan::where('token', $token)->first();
@endphp
@if ($pelanggan)
    <input type="hidden" name="id_pelanggan" id="idPelanggan" value="{{ $pelanggan->id_pelanggan }}">
@endif
<div class="container-fluid px-4 py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="display-6 text-center mb-5 fw-bold">🧾 Riwayat Pesanan Anda</h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="successAlert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
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

    @forelse ($pesanan as $item)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <!-- Header Pesanan -->
                    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Pesanan {{ $item->id_pesanan }}</h4>
                        <span class="badge rounded-pill 
                            {{ $item->status_pesanan == 'pending' ? 'bg-warning' : '' }}
                            {{ $item->status_pesanan == 'diproses' ? 'bg-info' : '' }}
                            {{ $item->status_pesanan == 'selesai' ? 'bg-success' : '' }}
                            {{ $item->status_pesanan == 'dibatalkan' ? 'bg-danger' : '' }}">
                            {{ ucfirst($item->status_pesanan) }}
                        </span>
                    </div>
                    
                <!-- Informasi Pesanan -->
                <div class="card-body p-4" x-data="{ open: false }">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-3 text-white">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <span class="text-muted small">Nama Pelanggan</span>
                                    <h6 class="mb-0">{{ $item->pelanggan->nama_pelanggan }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3 text-white">
                                    <i class="bi bi-ticket-perforated"></i>
                                </div>
                                <div>
                                    <span class="text-muted small">Nomor Kursi</span>
                                    <h6 class="mb-0">{{ $item->pelanggan->nomor_kursi }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3 text-white">
                                    <i class="bi bi-calendar-date"></i>
                                </div>
                                <div>
                                    <span class="text-muted small">Tanggal Pesanan</span>
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($item->tanggal_pesanan)->format('d F Y H:i') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3 text-white">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div>
                                    <span class="text-muted small">Total</span>
                                    <h6 class="mb-0 fw-bold text-primary">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

        <!-- Tombol Toggle dan Konfirmasi Pesanan -->
<div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
    <button class="btn btn-outline-secondary" @click="open = !open">
        <template x-if="!open">
            <span><i class="bi bi-chevron-down me-1"></i> Tampilkan Detail Produk</span>
        </template>
        <template x-if="open">
            <span><i class="bi bi-chevron-up me-1"></i> Sembunyikan Detail Produk</span>
        </template>
    </button>

    @if ($item->status_pesanan == 'diproses')
        @php
            $pembayaran = \App\Models\Pembayaran::where('id_pesanan', $item->id_pesanan)
                            ->where('status_pembayaran', 'dibayar')
                            ->first();
        @endphp
        @if ($pembayaran)
        <div class="d-flex flex-column mt-6">
        <button 
            class="btn btn-success mb-1"
            onclick="konfirmasiTerima({{ $item->id_pesanan }})">
            <i class="bi bi-check-circle me-1"></i> Pesanan Diterima
        </button>
        <small class="text-muted fst-italic ms-1">
            Tekan jika semua pesanan sudah diterima.
        </small>
    </div>
        @else
            <span class="badge bg-info bg-gradient text-white shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center"
                  style="font-size: 0.85rem; font-weight: 500;">
                <i class="bi bi-hourglass-split me-1"></i> Menunggu Konfirmasi Pembayaran
            </span>
            
        @endif
    @endif
</div>


                        
<!-- Daftar Produk -->
<div x-show="open" x-transition>
    <h5 class="fw-bold mb-3">Daftar Produk</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Subtotal</th>
                    @if($item->status_pesanan == 'selesai' || $item->status_pesanan == 'diproses')
                    <th class="text-end"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($item->detailPesanan as $detail)
                    <tr class="align-middle">
                        <td>
                            <div class="d-flex align-items-center flex-column">
                                <img src="{{ asset('storage/' . $detail->produk->gambar_produk) }}" 
                                    class="img-thumbnail mb-2"
                                    style="width: 80px; height: 80px; object-fit: cover;"
                                    alt="{{ $detail->produk->nama_produk }}">
                                <div class="text-center">
                                    <span class="fw-bold d-block">{{ $detail->produk->nama_produk }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            Rp{{ number_format($detail->produk->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark px-3 py-2">{{ $detail->jumlah }}</span>
                        </td>
                        <td class="text-center fw-bold">
                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                        @if ($item->status_pesanan == 'selesai')
                            @php
                                $sudahDiulas = \App\Models\Ulasan::where('id_pesanan', $item->id_pesanan)
                                                ->where('id_produk', $detail->produk->id_produk)
                                                ->exists();
                            @endphp
                            <td class="text-end align-middle">
                            @if (!$sudahDiulas)
                                <button 
                                    class="btn btn-warning btn-sm"
                                    onclick="bukaModalUlasan({{ $detail->id_detail_pesanan }}, {{ $detail->produk->id_produk }}, '{{ $detail->produk->nama_produk }}', '{{ $detail->produk->gambar_produk }}')">
                                    <i class="bi bi-star-fill me-1"></i> Beri Ulasan
                                </button>
                            @else
                                <span 
                                    class="badge bg-success bg-gradient text-white shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center"
                                    style="font-size: 0.85rem; font-weight: 500;">
                                    <i class="bi bi-check2-circle me-1"></i> Sudah Diulas
                                </span>
                            @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notifikasi untuk pesanan yang baru selesai -->
        @if ($item->status_pesanan == 'selesai')
            @php
                $adaProdukBelumDiulas = false;
                foreach ($item->detailPesanan as $detail) {
                    $sudahDiulas = \App\Models\Ulasan::where('id_pesanan', $item->id_pesanan)
                                    ->where('id_produk', $detail->produk->id_produk)
                                    ->exists();
                    if (!$sudahDiulas) {
                        $adaProdukBelumDiulas = true;
                        break;
                    }
                }
            @endphp
            
            @if ($adaProdukBelumDiulas)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm bg-light">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="bi bi-lightbulb-fill text-warning" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-1">Bagikan Pengalaman Anda!</h5>
                                        <p class="mb-0">Sudah mencoba pesanan Anda? Yuk beri ulasan untuk membantu pelanggan lain! <span class="text-primary fw-semibold">Tekan tombol "Beri Ulasan" di produk yang sudah Anda coba.</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @empty
<div class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body text-center p-5">
                <div class="empty-history-icon mb-4">
                    <i class="bi bi-receipt-cutoff text-secondary" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">Belum Ada Riwayat Pesanan</h3>
                <p class="text-muted mb-4">Anda belum memiliki pesanan apapun saat ini</p>
                <a href="/produk" class="btn btn-primary btn-lg px-4 py-2">
                    <i class="bi bi-card-list me-2"></i> Jelajahi Menu
                </a>
            </div>
        </div>
    </div>
</div>
@endforelse
</div>

<!-- Modal Ulasan -->
<div id="modalUlasan" class="fixed inset-0 = z-50 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold" id="modalUlasanLabel">Beri Ulasan Produk</h5>
                <button class="text-gray-500" onclick="closeModalUlasan()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form id="formUlasan" action="{{ route('ulasan.store') }}" method="POST">
                @csrf
                <div class="mt-4">
                    <input type="hidden" name="id_detail_pesanan" id="detailPesananId">
                    <input type="hidden" name="id_produk" id="produkId">
                    <input type="hidden" name="id_pelanggan" id="idPelanggan" value="{{ Auth::user()->id ?? '' }}">
                    
                    <!-- Gambar Produk dan Nama Produk -->
                    <div class="text-center mb-4">
                        <img src="" id="produkGambar" alt="Gambar Produk" class="mb-2 mx-auto" style="max-height: 150px;">
                        <h5 id="produkNama" class="text-lg font-semibold"></h5>
                    </div>
                    
                    <!-- Pesan untuk mencoba produk -->
                    <div class="alert alert-info mb-4 text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Penting:</strong> Pastikan Anda sudah mencoba produk ini sebelum memberikan ulasan.
                    </div>
                    
                    <!-- Rating -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600">Rating</label>
                        <div class="flex justify-center space-x-2 rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star-rating cursor-pointer text-2xl" data-rating="{{ $i }}" onclick="setRating({{ $i }})">
                                    <i class="bi bi-star text-gray-400"></i>
                                </span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0">
                    </div>

                    <!-- Ulasan -->
                    <div class="mb-4">
                        <label for="ulasan" class="block text-sm font-medium text-gray-600">Ulasan Anda</label>
                        <textarea class="form-textarea w-full px-3 py-2 border rounded-md" id="ulasan" name="komentar" rows="4" placeholder="Bagaimana pengalaman Anda dengan produk ini? Ceritakan bagaimana rasa, tampilan, dan kualitas produk."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="btn btn-secondary px-4 py-2 bg-gray-300 rounded-md text-gray-700" onclick="closeModalUlasan()">Batal</button>
                    <button type="button" class="btn btn-primary px-4 py-2 bg-blue-500 text-white rounded-md" onclick="validasiFormUlasan()">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Terima Pesanan -->
<div id="modalKonfirmasiTerima" class="fixed inset-0 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold">Konfirmasi Penerimaan Pesanan</h5>
            </div>
            <div class="mt-4">
                <div class="text-center mb-4">
                    <div class="mx-auto mb-4 text-center">
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-lg font-semibold mb-3">Apakah pesanan Anda sudah diterima?</h5>
                    <p class="text-muted">Pastikan semua pesanan sudah Anda terima. Konfirmasi ini berarti semua pesanan telah sampai dengan baik.</p>
                </div>
            </div>
            <form id="formTerimaPesanan" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_pesanan" id="idPesananTerima">
                <div class="flex justify-center space-x-3 mt-4">
                    <button type="button" class="btn btn-secondary px-4 py-2 bg-gray-300 rounded-md text-gray-700" onclick="tutupModalKonfirmasi()">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success px-4 py-2 bg-green-500 text-white rounded-md">
                        <i class="bi bi-check-circle me-1"></i> Ya, Pesanan Diterima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Variabel untuk menyimpan rating terpilih
let currentRating = 0;

// Fungsi untuk membuka modal ulasan dan mengatur data produk
function bukaModalUlasan(detailPesananId, produkId, produkNama, produkGambar) {
    // Set nilai hidden input
    document.getElementById('detailPesananId').value = detailPesananId;
    document.getElementById('produkId').value = produkId;

    // Update judul modal
    document.getElementById('modalUlasanLabel').textContent = `Beri Ulasan untuk ${produkNama}`;
    
    // Set gambar produk dan nama produk
    let fullImagePath = `/storage/${produkGambar}`;
    document.getElementById('produkGambar').src = fullImagePath;
    document.getElementById('produkNama').textContent = produkNama;

    // Reset rating
    currentRating = 0;
    updateStars();
    
    // Reset form
    document.getElementById('formUlasan').reset();
    
    // Tampilkan modal
    document.getElementById('modalUlasan').classList.remove('hidden');

    // Gulir ke atas halaman
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Menutup modal
function closeModalUlasan() {
    document.getElementById('modalUlasan').classList.add('hidden');
}


// Fungsi untuk mengatur rating
function setRating(rating) {
    currentRating = rating;
    document.getElementById('ratingInput').value = rating;
    updateStars();
    // Menghapus error rating setelah memilih rating
    removeError('ratingInput');
}

// Fungsi untuk memperbarui tampilan bintang
function updateStars() {
    const stars = document.querySelectorAll('.star-rating');
    stars.forEach(star => {
        const starRating = parseInt(star.getAttribute('data-rating'));
        const icon = star.querySelector('i');
        
        if (starRating <= currentRating) {
            icon.classList.remove('bi-star');
            icon.classList.add('bi-star-fill');
            icon.classList.add('text-warning');
        } else {
            icon.classList.add('bi-star');
            icon.classList.remove('bi-star-fill');
            icon.classList.remove('text-warning');
        }
    });
}

// Fungsi untuk validasi form sebelum kirim
function validasiFormUlasan() {
    const rating = document.getElementById('ratingInput').value;
    const komentar = document.getElementById('ulasan').value.trim();

    // Clear error sebelumnya (jika ada)
    document.querySelectorAll('.form-error').forEach(el => el.remove());

    let valid = true;

    // Validasi Rating
    if (rating == 0) {
        const ratingBox = document.querySelector('.rating-stars');
        const error = document.createElement('div');
        error.className = 'form-error text-danger mt-2 text-center small';
        error.innerText = 'Silakan pilih rating terlebih dahulu';
        ratingBox.parentNode.appendChild(error);
        valid = false;
    }

    // Validasi Komentar
    if (komentar === '') {
        const komentarInput = document.getElementById('ulasan');
        const error = document.createElement('div');
        error.className = 'form-error text-danger mt-1 small';
        error.innerText = 'Komentar ulasan tidak boleh kosong';
        komentarInput.parentNode.appendChild(error);
        valid = false;
    }

    // Jika valid, submit form
    if (valid) {
        document.getElementById('formUlasan').submit();
    }
}

// Fungsi untuk menghapus error
function removeError(inputId) {
    const errorDiv = document.querySelector(`#${inputId} + .form-error`);
    if (errorDiv) {
        errorDiv.remove();  // Menghapus pesan error
    }
}
function refreshPage() {
    // Refresh halaman ketika tombol batal ditekan
    window.location.reload();
}


// Menghapus error komentar ketika mulai mengetik
document.getElementById('ulasan').addEventListener('input', function() {
    if (this.value.trim() !== "") {
        removeError('ulasan');
    }
});
// Add this to your existing JavaScript section
// Function to open the confirmation modal
function konfirmasiTerima(id_pesanan) {
    // Set the pesanan ID in the hidden form field
    document.getElementById('idPesananTerima').value = id_pesanan;
    
    // Update form action
    document.getElementById('formTerimaPesanan').action = '/pesanan/terima/' + id_pesanan;
    
    // Show the modal
    document.getElementById('modalKonfirmasiTerima').classList.remove('hidden');
    
    // Scroll to top of page
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Function to close the confirmation modal
function tutupModalKonfirmasi() {
    document.getElementById('modalKonfirmasiTerima').classList.add('hidden');
}

</script>

@endsection