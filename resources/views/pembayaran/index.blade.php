<link href="https://cdn.tailwindcss.com" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">

<div class="bg-gray-50 min-h-screen py-8">
  <div class="container mx-auto p-0 md:p-6 max-w-4xl">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white">
        <h1 class="text-2xl md:text-3xl font-bold mb-2">Pembayaran Pesanan</h1>
        <p class="text-blue-100">Selesaikan pembayaran untuk melanjutkan pesanan Anda</p>
      </div>
      
      <!-- Success Message -->
      @if(session('success'))
      <div class="mx-6 mt-6">
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          {{ session('success') }}
        </div>
      </div>
      @endif
      
      <!-- Payment Form -->
      <form action="{{ route('pembayaran.buat', ['id_pesanan' => $pesanan->id_pesanan]) }}" method="POST" enctype="multipart/form-data" id="payment-form" class="p-6">
        @csrf
        
        <div class="flex flex-col md:flex-row gap-6">
          <!-- Left Column: Order Summary -->
          <div class="w-full md:w-1/2">
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Ringkasan Pesanan</h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">#{{ $pesanan->id_pesanan }}</span>
              </div>
              
              <div class="space-y-4 max-h-80 overflow-auto pr-2 mb-4">
                @foreach($detailPesanan as $detail)
                <div class="flex items-center space-x-4 bg-white p-3 rounded-lg border border-gray-100">
                  <div class="flex-shrink-0 w-16 h-16">
                    <img src="{{ asset('storage/' . $detail->produk->gambar_produk) }}" 
                         class="w-full h-full object-cover rounded-md" alt="{{ $detail->produk->nama_produk }}">
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 truncate">{{ $detail->produk->nama_produk }}</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($detail->produk->harga, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Jumlah: {{ $detail->jumlah }}</p>
                  </div>
                  <div class="text-right">
                    <p class="font-medium text-gray-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                  </div>
                </div>
                @endforeach
              </div>
              
              <div class="border-t border-gray-200 pt-4 space-y-2">
                <div class="flex justify-between font-semibold text-lg text-gray-800 pt-2 border-t border-gray-200 mt-2">
                  <span>Total Pembayaran</span>
                  <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
              </div>
                <div class="mt-6 bg-white border border-blue-200 rounded-2xl shadow p-6">
                    <div class="flex flex-col items-center text-center space-y-3">
                        <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-blue-700">Selesaikan pembayaran sebelum waktu habis:</p>
                        <p class="text-4xl font-bold text-blue-600 tabular-nums tracking-widest" id="countdown">--:--</p>
                        <p class="text-xs text-blue-500">Pesanan akan dibatalkan otomatis jika waktu habis.</p>
                    </div>
                </div>
            </div>
          </div>
          
          <!-- Right Column: Payment Methods -->
          <div class="w-full md:w-1/2">
            <div class="mb-6">
              <h3 class="text-lg font-semibold mb-3 text-gray-800">Pilih Metode Pembayaran</h3>
              <div class="grid grid-cols-3 gap-3">
                <div class="payment-option border border-gray-200 p-3 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-method="VA">
                  <img src="{{ asset('storage/logo/BNI.png') }}" alt="Virtual Account" class="h-8 mb-2 mx-auto">
                  <p class="text-center text-sm font-medium text-gray-700">BANK</p>
                </div>
                <div class="payment-option border border-gray-200 p-3 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-method="QR">
                  <img src="{{ asset('storage/logo/qris.png') }}" alt="QR" class="h-8 mb-2 mx-auto">
                  <p class="text-center text-sm font-medium text-gray-700">QRIS</p>
                </div>
              </div>
              <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" value="">
            </div>
            
            <!-- Payment Details -->
            <div id="payment_details" class="mb-6">
              <!-- VA Info -->
              <div id="va_info" class="payment-info bg-gray-50 p-4 rounded-xl border border-gray-100 hidden">
                <h3 class="text-base font-semibold mb-3 text-gray-800">Transfer Via Virtual Account</h3>
                <div class="space-y-3">
                  <div class="bg-white p-3 rounded-lg border border-gray-100">
                    <div class="flex items-center mb-2">
                      <img src="{{ asset('storage/logo/BNI.png') }}" alt="Logo BCA" class="w-8 h-8 mr-3">
                      <div>
                        <p class="font-medium text-gray-800">BANK BNI</p>
                        <p class="text-xs text-gray-500">a.n. AHMAD ASRUL EFANDI</p>
                      </div>
                    </div>
                    <div class="flex justify-between items-center mt-2 p-2 bg-gray-50 rounded">
                      <p class="font-mono text-base font-semibold text-gray-800">1847307374</p>
                      <button type="button" class="copy-btn flex items-center text-blue-600 hover:text-blue-800 text-sm" data-text="1847307374">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Salin
                      </button>
                    </div>
                  </div>
                  
                  <div class="bg-blue-50 p-3 rounded-lg">
                  <h4 class="text-sm font-medium text-blue-800 mb-2">Petunjuk Pembayaran via Bank:</h4>
                  <ol class="list-decimal pl-5 text-xs text-blue-700 space-y-1">
                    <li>Buka aplikasi atau website perbankan yang Anda gunakan</li>
                    <li>Pilih opsi pembayaran melalui Bank pada halaman pembayaran</li>
                    <li>Pilih sesuai dengan pilihan Anda</li>
                    <li>Masukkan nomor rekening tujuan yang tertera pada halaman pembayaran</li>
                    <li>Masukkan jumlah pembayaran yang sesuai dengan total yang tertera di halaman pembayaran</li>
                    <li>Periksa kembali nomor rekening dan jumlah transfer sebelum melakukan konfirmasi</li>
                    <li>Setelah transfer selesai, pastikan Anda mendapatkan konfirmasi dari bank</li>
                    <li>Ambil screenshot atau unduh bukti pembayaran dari aplikasi perbankan Anda</li>
                    <li>Unggah bukti pembayaran melalui halaman konfirmasi untuk menyelesaikan proses pembayaran</li>
                  </ol>
                  </div>
                </div>
              </div>
              
              <!-- QRIS Info -->
              <div id="qr_info" class="payment-info bg-gray-50 p-4 rounded-xl border border-gray-100 hidden">
                <h3 class="text-base font-semibold mb-3 text-gray-800">QR Code Pembayaran</h3>
                <div class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-100">
                  <img id="qrImage" src="{{ asset('storage/logo/qrcode.png') }}" alt="QR" class="w-auto h-auto mb-4">
                  <p class="text-sm text-gray-600 mb-3 text-center">Scan QR code ini menggunakan aplikasi e-wallet atau mobile banking Anda</p>
                  <button id="downloadBtn" type="button" class="flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh QR Code
                  </button>
                </div>
                  <div class="bg-blue-50 p-3 rounded-lg">
                  <h4 class="text-sm font-medium text-blue-800 mb-2">Petunjuk Pembayaran via QRIS:</h4>
                  <ol class="list-decimal pl-5 text-xs text-blue-700 space-y-1">
                    <li>Buka aplikasi pembayaran digital Anda (seperti DANA, OVO, Gopay, ShopeePay, dll.)</li>
                    <li>Pilih opsi pembayaran menggunakan QRIS pada halaman pembayaran</li>
                    <li>Scan QR Code yang tertera di halaman pembayaran menggunakan aplikasi pembayaran Anda</li>
                    <li>Masukkan jumlah pembayaran yang ditampilkan pada aplikasi Anda dan pastikan sesuai dengan total pembayaran</li>
                    <li>Periksa kembali detail transaksi sebelum melanjutkan</li>
                    <li>Konfirmasi pembayaran di aplikasi Anda untuk menyelesaikan transaksi</li>
                    <li>Setelah pembayaran berhasil, ambil screenshot atau simpan bukti pembayaran dari aplikasi Anda</li>
                    <li>Unggah bukti pembayaran melalui halaman konfirmasi untuk menyelesaikan proses pembayaran</li>
                  </ol>
                  </div>
              </div>
            </div>
            
            <!-- Upload Bukti Pembayaran -->
            <div class="mb-6">
              <h3 class="text-base font-semibold mb-3 text-gray-800">Upload Bukti Pembayaran</h3>
              <div class="flex items-center justify-center w-full">
                <label for="bukti_pembayaran" class="flex flex-col items-center justify-center w-full h-40 border-2 border-blue-200 border-dashed rounded-xl cursor-pointer bg-blue-50 hover:bg-blue-100 transition-colors">
                  <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                    <svg class="w-8 h-8 mb-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="mb-2 text-sm text-blue-600"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                    <p class="text-xs text-blue-500">PNG, JPG atau JPEG (Maks. 5MB)</p>
                  </div>
                  <div id="image-preview" class="hidden w-full h-full relative">
                    <img id="preview-img" src="#" alt="Preview" class="w-full h-full object-contain rounded-lg">
                    <button type="button" id="remove-image" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors shadow-md">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="hidden" accept="image/*" />
                </label>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Instructions and Submit Button -->
        <div class="mt-6">
          <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-6">
            <div class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <h3 class="text-blue-800 font-medium mb-2">Petunjuk Pembayaran</h3>
                <ol class="list-decimal pl-5 text-sm text-blue-700">
                  <li class="mb-1">Pilih metode pembayaran yang tersedia (BANK atau QRIS)</li>
                  <li class="mb-1">Lakukan pembayaran sesuai dengan petunjuk untuk metode yang dipilih</li>
                  <li class="mb-1">Simpan atau screenshot bukti transaksi pembayaran Anda</li>
                  <li class="mb-1">Upload bukti pembayaran pada tempat yang sudah disediakan</li>
                  <li>Klik "Konfirmasi Pembayaran" untuk menyelesaikan proses</li>
                </ol>
                <p class="text-xs text-blue-600 mt-2">Catatan: Pembayaran akan diverifikasi secepat mungkin.</p>
              </div>
            </div>
          </div>
          
          <button type="submit" id="submit-button" class="w-full py-4 px-6 bg-gray-300 text-gray-500 rounded-xl transition-all duration-300 flex items-center justify-center cursor-not-allowed font-medium" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Konfirmasi Pembayaran
          </button>
        </div>
      </form>
    </div>
    
    <div class="text-center mt-6 text-sm text-gray-500">
      © 2025 Jalur Langit Coffe. Semua hak dilindungi.
    </div>
  </div>
</div>

<script>
  // Saat memilih metode pembayaran
  document.querySelectorAll('.payment-option').forEach(function(option) {
    option.addEventListener('click', function() {
      // Remove active class from all options
      document.querySelectorAll('.payment-option').forEach(function(el) {
        el.classList.remove('border-blue-500', 'bg-blue-50');
        el.classList.add('border-gray-200');
      });
      
      // Add active class to selected option
      this.classList.remove('border-gray-200');
      this.classList.add('border-blue-500', 'bg-blue-50');
      
      // Update hidden input value
      const method = this.getAttribute('data-method');
      document.getElementById('metode_pembayaran').value = method;
      
      // Hide all payment info
      document.querySelectorAll('.payment-info').forEach(function(info) {
        info.classList.add('hidden');
      });
      
      // Show selected payment info
      document.getElementById(method.toLowerCase() + '_info').classList.remove('hidden');
      
      // Validate form
      validateForm();
    });
  });

  // Upload bukti pembayaran preview
  document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('image-preview').classList.remove('hidden');
      }
      reader.readAsDataURL(file);
      validateForm();
    }
  });

  // Remove image preview
  document.getElementById('remove-image').addEventListener('click', function() {
    document.getElementById('bukti_pembayaran').value = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('image-preview').classList.add('hidden');
    validateForm();
  });

  // Copy to clipboard functionality
  document.querySelectorAll('.copy-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const text = this.getAttribute('data-text');
      navigator.clipboard.writeText(text).then(() => {
        const originalText = this.innerHTML;
        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Tersalin!';
        setTimeout(() => {
          this.innerHTML = originalText;
        }, 2000);
      });
    });
  });

  // Form validation
  function validateForm() {
    const metodePembayaran = document.getElementById('metode_pembayaran').value;
    const buktiPembayaran = document.getElementById('bukti_pembayaran').files.length > 0;
    const submitBtn = document.getElementById('submit-button');

    if (metodePembayaran && buktiPembayaran) {
      submitBtn.disabled = false;
      submitBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
      submitBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'cursor-pointer', 'shadow-md');
    } else {
      submitBtn.disabled = true;
      submitBtn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
      submitBtn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'cursor-pointer', 'shadow-md');
    }
  }

  document.getElementById('downloadBtn').addEventListener('click', function () {
    const image = document.getElementById('qrImage');
    const imageUrl = image.src;

    // Buat elemen <a> untuk trigger download
    const link = document.createElement('a');
    link.href = imageUrl;
    link.download = 'qr-code.png'; // Nama file saat diunduh
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
  
     // Ambil tanggal_pesanan dari Laravel, ubah ke format JS
     const tanggalPesanan = "{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('Y-m-d H:i:s') }}";
    const countdownEnd = new Date(new Date(tanggalPesanan).getTime() + 30 * 60000); // +30 menit

    const countdownElement = document.getElementById("countdown");

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = countdownEnd - now;

        if (distance < 0) {
            countdownElement.innerHTML = "00:00";
            clearInterval(interval);
            // Redirect kalau waktu habis
            window.location.href = "/produk";
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownElement.innerHTML = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    updateCountdown(); // pertama kali jalan
    const interval = setInterval(updateCountdown, 1000); // update per detik
</script>