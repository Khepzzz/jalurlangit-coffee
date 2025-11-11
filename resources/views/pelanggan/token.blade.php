<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jalur Langit Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f5f2;
        }
        /* Ensure modal is centered and overlay is full-screen */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            max-width: 300px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-amber-50">
    <div class="flex justify-center items-center min-h-screen p-4">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden w-full max-w-md">
            
            <div class="bg-gradient-to-r from-teal-600 to-teal-800 p-6 text-center">
                <img src="/storage/logo/logo.png" alt="Jalur Langit Coffee Logo" class="mx-auto mb-2" style="height: 100px; width: auto;">
                <h2 class="text-2xl font-bold text-white">Jalur Langit Coffee</h2>
                <p class="text-amber-100 text-sm mt-1">Jalur darat sangat berat kawan, Segeralah ke Jalur Langit</p>
            </div>
            
            <div class="p-8">
                <h3 class="text-center text-xl text-teal-700 font-medium mb-2">Selamat Datang</h3>
                <p class="text-center text-sm text-gray-500 mb-6">Masukkan Token dan Identitas Anda untuk Melanjutkan Pesanan</p>

                <!-- Pesan Error Token -->
                <div id="tokenError" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4 hidden">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">Token sudah aktif atau tidak valid!</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Ambil Token -->
                <button id="ambilToken" class="flex items-center justify-center gap-2 bg-gradient-to-r from-teal-500 to-teal-600 text-white w-full py-3 rounded-lg mt-4 mb-4 hover:from-teal-600 hover:to-teal-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50 shadow-md">
                    <i class="fas fa-key"></i>
                    <span>Ambil Token Baru</span>
                </button>

                <!-- Info Panel untuk Pelanggan Lama -->
                <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-700 p-4 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm"><strong>Sudah pernah ambil token?</strong> Masukkan token Anda di bawah dan klik "Cek Token" untuk mengambil data Anda secara otomatis.</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Token -->
                <div id="tokenContainer" class="bg-blue-50 border border-blue-200 text-center py-4 px-4 mt-4 rounded-lg hidden">
                    <div class="flex items-center justify-center text-blue-700 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="font-medium">Informasi Token</span>
                    </div>
                    <p class="mb-1"><span class="font-medium">Token:</span> <span id="tokenDisplay" class="text-blue-800 bg-blue-100 px-2 py-1 rounded"></span></p>
                    <p class="mb-2"><span class="font-medium">Berlaku hingga:</span> <span id="tokenExpired" class="text-gray-700"></span></p>
                    <p class="text-xs text-gray-600 italic"><i class="fas fa-exclamation-triangle text-amber-500 mr-1"></i> Simpan token ini dengan baik, karena akan digunakan untuk memberikan ulasan setelah pesanan selesai.</p>
                </div>

                <!-- Form Login dengan Token -->
                <form id="loginForm" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="token" class="text-sm text-gray-600 font-medium block mb-1">Token</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-ticket-alt text-gray-400"></i>
                            </div>
                            <input type="text" name="token" id="token" class="pl-10 w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" placeholder="Masukkan Token" required>
                        </div>
                        
                        <!-- Tombol cek token dengan info tambahan -->
                        <div class="mt-2">
                            <button type="button" id="cekToken" class="flex items-center justify-center gap-2 bg-blue-500 text-white w-full py-2 rounded hover:bg-blue-600 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                <i class="fas fa-search"></i>
                                <span>Cek Token & Isi Data Otomatis</span>
                            </button>
                        </div>
                    </div>

                    <!-- Panel status cek token -->
                    <div id="statusPanel" class="bg-green-50 border border-green-200 p-3 rounded-lg hidden">
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            <p class="text-sm" id="statusMessage">Token valid! Data berhasil diambil.</p>
                        </div>
                    </div>

                    <!-- Input Nama -->
                    <div>
                        <label for="nama_pelanggan" class="text-sm text-gray-600 font-medium block mb-1">Nama</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="pl-10 w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" placeholder="Masukkan Nama Anda" required>
                        </div>
                    </div>

                    <!-- Input Nomor Kursi -->
                    <div>
                        <label for="nomor_kursi" class="text-sm text-gray-600 font-medium block mb-1">Nomor Kursi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-chair text-gray-400"></i>
                            </div>
                            <input type="text" name="nomor_kursi" id="nomor_kursi" class="pl-10 w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" placeholder="Contoh: A12" required>
                        </div>
                    </div>

                    <button type="submit" id="loginButton" class="flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white w-full py-3 rounded-lg mt-4 hover:from-amber-600 hover:to-amber-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50 shadow-md">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                </form>
                
                <div class="text-center mt-6">
                    <p class="text-xs text-gray-500">© 2025 Jalur Langit Coffee. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Ambil Token -->
    <div id="tokenModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-center items-center mb-4">
                <i class="fas fa-spinner fa-spin text-teal-600 text-2xl mr-2"></i>
                <span class="text-lg font-medium text-teal-700">Membuat Token...</span>
            </div>
            <p class="text-sm text-gray-600">Mohon tunggu sebentar.</p>
        </div>
    </div>

    <!-- Modal for Login -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-center items-center mb-4">
                <i class="fas fa-spinner fa-spin text-amber-600 text-2xl mr-2"></i>
                <span class="text-lg font-medium text-amber-700">Memproses Login...</span>
            </div>
            <p class="text-sm text-gray-600">Mohon tunggu sebentar.</p>
        </div>
    </div>

    <!-- Toast Error -->
    <div class="fixed top-5 right-5 bg-white border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg hidden flex items-center space-x-2" id="errorToast">
        <i class="fas fa-times-circle"></i>
        <span>Terjadi kesalahan, silakan coba lagi.</span>
    </div>

    <!-- Toast Success -->
    <div class="fixed top-5 right-5 bg-white border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg hidden flex items-center space-x-2" id="successToast">
        <i class="fas fa-check-circle"></i>
        <span>Identitas berhasil disimpan!</span>
    </div>

    <script>
        $(document).ready(function() {
            // Show token modal when Ambil Token is clicked
            $('#ambilToken').click(function() {
                $('#tokenModal').show();
                $.ajax({
                    url: "{{ secure_url('/ambil-token') }}",
                    type: "POST",
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(response) {
                        $('#tokenModal').hide(); // Hide modal on success
                        if (response.error) {
                            $('#tokenError').removeClass('hidden').show();
                            $('#tokenError').find('p').text(response.error);
                        } else {
                            $('#tokenDisplay').text(response.token);
                            $('#tokenExpired').text(response.expired_at);
                            $('#tokenContainer').removeClass('hidden').show();
                            $('#ambilToken').prop('disabled', true).addClass('opacity-50');

                            // Auto-fill token field
                            $('#token').val(response.token);

                            // Set timer untuk memeriksa kadaluarsa token
                            var expiredAt = new Date(response.expired_at);
                            var now = new Date();

                            if (expiredAt <= now) {
                                $('#ambilToken').prop('disabled', false).removeClass('opacity-50');
                                $('#tokenContainer').hide();
                                $('#tokenError').removeClass('hidden').show();
                                $('#tokenError').find('p').text("Token sudah kadaluarsa. Silakan ambil token baru.");
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#tokenModal').hide(); // Hide modal on error
                        $('#tokenError').removeClass('hidden').show();
                        $('#tokenError').find('p').text("Token sudah pernah diambil dan masih aktif.");
                    }
                });
            });

            // Cek Token dan Auto-fill data pelanggan jika sudah ada
            $('#cekToken').click(function() {
                const token = $('#token').val();
                
                if (!token) {
                    $('#errorToast').removeClass('hidden').show();
                    $('#errorToast').find('span').text("Harap masukkan token terlebih dahulu!");
                    setTimeout(function() {
                        $('#errorToast').addClass('hidden').hide();
                    }, 3000);
                    return;
                }
                
                $('#cekToken').html('<i class="fas fa-spinner fa-spin"></i> <span>Memeriksa...</span>');
                $('#cekToken').prop('disabled', true);
                
                $.ajax({
                    url: "{{ secure_url('/get-data-pelanggan') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        token: token
                    },
                    success: function(response) {
                        $('#cekToken').html('<i class="fas fa-search"></i> <span>Cek Token & Isi Data Otomatis</span>');
                        $('#cekToken').prop('disabled', false);
                        
                        if (response.success) {
                            if (response.data_tersimpan) {
                                $('#nama_pelanggan').val(response.nama_pelanggan);
                                $('#nomor_kursi').val(response.nomor_kursi);
                                
                                $('#statusPanel').removeClass('hidden bg-green-50 border-green-200 text-green-700').addClass('bg-green-50 border-green-200 text-green-700').show();
                                $('#statusMessage').html('<strong>Token valid!</strong> Data Anda berhasil diambil secara otomatis.');
                                
                                $('#nama_pelanggan, #nomor_kursi').addClass('bg-green-50 border-green-300');
                                setTimeout(function() {
                                    $('#nama_pelanggan, #nomor_kursi').removeClass('bg-green-50 border-green-300');
                                }, 2000);
                            } else {
                                $('#statusPanel').removeClass('hidden bg-green-50 border-green-200 text-green-700').addClass('bg-blue-50 border-blue-200 text-blue-700').show();
                                $('#statusMessage').html('<strong>Token valid!</strong> Silakan lengkapi data Anda.');
                            }
                        } else {
                            $('#statusPanel').removeClass('hidden bg-green-50 border-green-200 text-green-700').addClass('bg-red-50 border-red-200 text-red-700').show();
                            $('#statusMessage').text(response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#cekToken').html('<i class="fas fa-search"></i> <span>Cek Token & Isi Data Otomatis</span>');
                        $('#cekToken').prop('disabled', false);
                        
                        let errorMessage = "Token tidak valid atau sudah kadaluarsa!";
                        
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        
                        $('#statusPanel').removeClass('hidden bg-green-50 border-green-200 text-green-700').addClass('bg-red-50 border-red-200 text-red-700').show();
                        $('#statusMessage').html('<strong>Error!</strong> ' + errorMessage);
                    }
                });
            });

            // Proses submit form tanpa reload halaman
            $('#loginForm').submit(function(event) {
                event.preventDefault();

                var token = $('#token').val();
                var namaPelanggan = $('#nama_pelanggan').val();
                var nomorKursi = $('#nomor_kursi').val();

                // Show login modal
                $('#loginModal').show();
                $('#loginButton').html('<i class="fas fa-spinner fa-spin"></i> <span>Memproses...</span>').prop('disabled', true);

                $.ajax({
                    url: "{{ secure_url('/store-identitas') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        token: token,
                        nama_pelanggan: namaPelanggan,
                        nomor_kursi: nomorKursi
                    },
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function(response) {
                        $('#loginModal').hide(); // Hide modal on success
                        $('#loginButton').html('<i class="fas fa-sign-in-alt"></i> <span>Masuk</span>').prop('disabled', false);
                        if (response.success) {
                            $('#successToast').removeClass('hidden').show();
                            setTimeout(function() {
                                $('#successToast').addClass('hidden').hide();
                                window.location.href = "{{ secure_url('/produk') }}";
                            }, 1000);
                        } else {
                            if (response.error) {
                                $('#errorToast').removeClass('hidden').show();
                                $('#errorToast').find('span').text(response.error);
                                setTimeout(function() {
                                    $('#errorToast').addClass('hidden').hide();
                                }, 3000);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loginModal').hide(); // Hide modal on error
                        $('#loginButton').html('<i class="fas fa-sign-in-alt"></i> <span>Masuk</span>').prop('disabled', false);
                        let errorMessage = "Terjadi kesalahan saat menyimpan identitas pelanggan.";
                        
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        
                        $('#errorToast').removeClass('hidden').show();
                        $('#errorToast').find('span').text(errorMessage);
                        setTimeout(function() {
                            $('#errorToast').addClass('hidden').hide();
                        }, 3000);
                    }
                });
            });

            // Cek apakah ada token di URL
            const urlParams = new URLSearchParams(window.location.search);
            const tokenFromUrl = urlParams.get('token');
            
            if (tokenFromUrl) {
                $('#token').val(tokenFromUrl);
                $('#cekToken').trigger('click');
            }
            
            // Fade out toast messages after 3 seconds
            setTimeout(function() {
                $('.fixed').fadeOut('slow');
            }, 3000);
        });
    </script>
</body>
</html>