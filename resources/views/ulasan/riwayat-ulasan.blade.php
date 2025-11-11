@extends('produk.app')

@section('content')

<div class="container mt-5">
    <div class="col-12">
        <div class="text-center mb-5">
            <div class="d-flex justify-content-center align-items-center mb-2">
                <i class="bi bi-star-fill text-warning me-2" style="font-size: 1.5rem;"></i>
                <h2 class="display-6 fw-bold mb-0">Riwayat Ulasan Anda</h2>
                <i class="bi bi-star-fill text-warning ms-2" style="font-size: 1.5rem;"></i>
            </div>
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

    @if ($ulasan->isEmpty())
        <div class="row justify-content-center my-4">
            <div class="col-md-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="empty-reviews-icon mb-4">
                            <i class="bi bi-star text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3">Belum Ada Ulasan</h4>
                        <p class="text-muted">Anda belum memberikan ulasan untuk produk apapun.</p>
                        <a href="/produk" class="btn btn-primary mt-3">
                            <i class="bi bi-bag me-1"></i> Lihat Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Nama Produk</th>
                                <th class="px-4 py-3">Rating</th>
                                <th class="px-4 py-3">Ulasan</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ulasan as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . ($item->produk->gambar_produk ?? 'default.jpg')) }}" 
                                                 alt="{{ $item->produk->nama_produk ?? 'Produk' }}" 
                                                 class="rounded me-3" 
                                                 width="50" height="50" 
                                                 style="object-fit: cover;">
                                            <span class="fw-medium">{{ $item->produk->nama_produk ?? 'Tidak tersedia' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $item->rating ? '-fill' : '' }} text-warning"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ Str::limit($item->komentar, 50) }}</td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal_ulasan)->format('d F Y') }}</td>
                                    <td class="px-4 py-3">
                                    <button class="btn btn-sm btn-warning" onclick="openModal({{ $item->id_ulasan }})">
    <i class="bi bi-pencil me-1"></i> Edit
</button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Edit Ulasan -->
<div id="modalEditUlasan" class="fixed inset-0 z-50 hidden transition-all duration-300 ease-in-out">
    <div class="flex justify-center items-center min-h-screen pt-10">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 transform transition-transform duration-300 ease-in-out scale-95 opacity-0">
            <!-- Card Header -->
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-xl font-semibold" id="modalEditUlasanLabel">Edit Ulasan <span id="productNameEdit" class="font-bold text-yellow-500"></span></h5>
                
            </div>

            <!-- Form to edit review -->
            <form id="formEditUlasan" method="POST" action="" onsubmit="return validateFormEditUlasan()">
                @csrf
                @method('PUT')
                <input type="hidden" id="ulasanId" name="ulasanId">

                <!-- Rating Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Rating Anda</label>
                    <div class="flex justify-center space-x-2 mt-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star-rating cursor-pointer text-2xl" data-rating="{{ $i }}" onclick="setRatingEdit({{ $i }})">
                                <i class="bi bi-star text-gray-400"></i>
                            </span>
                        @endfor
                    </div>
                    <input type="hidden" id="ratingInputEdit" name="rating" value="0">
                </div>
                <div class="mb-6">
                    <label for="komentarEdit" class="block text-sm font-medium text-gray-700">Komentar Anda</label>
                    <textarea class="form-textarea mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500" id="komentarEdit" name="komentar" rows="4" placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                </div>

                <!-- Error Message -->
                <div id="errorTextEdit" class="text-red-500 text-sm hidden flex items-center space-x-1">
                    <i class="bi bi-exclamation-triangle"></i> <span>Rating dan komentar harus diisi.</span>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="btn btn-secondary px-4 py-2 bg-gray-300 rounded-md text-gray-700" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 bg-blue-500 text-white rounded-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
function openModal(id) {
    fetch(`/ulasan/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Isi modal dengan data ulasan
            document.getElementById('productNameEdit').innerText = data.nama_produk;
            document.getElementById('komentarEdit').value = data.komentar;
            document.getElementById('ratingInputEdit').value = data.rating;

            // Set rating yang sudah diberikan
            setRatingEdit(data.rating);

            // Isi id ulasan pada form
            document.getElementById('ulasanId').value = data.id;

            // Update action form untuk rute PUT
            document.getElementById('formEditUlasan').action = `/ulasan/${data.id}`;

            // Tampilkan modal dengan animasi
            const modal = document.getElementById('modalEditUlasan');
            modal.classList.remove('hidden'); // Unhide modal
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
                modal.querySelector('.bg-white').classList.add('scale-100', 'opacity-100');
            }, 10); // Menambahkan sedikit delay agar animasi terjadi
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data ulasan');
        });
}

function closeModal() {
    // Ambil elemen modal
    const modal = document.getElementById('modalEditUlasan');
    
    // Mulai animasi dengan menambahkan kelas untuk mengecilkan dan memudarkan modal
    modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-0');
    
    // Tunggu animasi selesai (300ms), kemudian sembunyikan modal
    setTimeout(() => {
        modal.classList.add('hidden'); // Menyembunyikan modal setelah animasi selesai
    }, 300); // Delay sesuai durasi animasi (300ms)
}



function setRatingEdit(rating) {
    document.getElementById('ratingInputEdit').value = rating;
    updateStarsEdit(rating);
}

function updateStarsEdit(rating) {
    const stars = document.querySelectorAll('.star-rating');
    stars.forEach(star => {
        const starRating = parseInt(star.getAttribute('data-rating'));
        const icon = star.querySelector('i');
        
        // Reset semua kelas terlebih dahulu
        icon.classList.remove('bi-star-fill', 'text-yellow-500', 'bi-star');
        
        if (starRating <= rating) {
            icon.classList.add('bi-star-fill', 'text-yellow-500');
        } else {
            icon.classList.add('bi-star');
        }
    });
}

function validateFormEditUlasan() {
    const rating = document.getElementById('ratingInputEdit').value;
    const komentar = document.getElementById('komentarEdit').value.trim();
    const errorText = document.getElementById('errorTextEdit');

    if (rating == 0 || komentar === '') {
        errorText.classList.remove('hidden');
        return false;
    }
    
    errorText.classList.add('hidden');
    return true;
}

</script>
@endsection