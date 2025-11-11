@extends('pegawai.layout')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <!-- Header with back button -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pembayaran untuk Pesanan ID: {{ $pesanan->id_pesanan }}</h2>
            <div class="text-center pt-4">
            <a href="{{ route('pegawai.pesanan.index') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-xl transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Pesanan
            </a>
        </div>
        </div>

        @if(session('success'))
    <div id="successMessage" class="bg-green-100 text-green-700 p-4 rounded-md mb-6 flex items-center transition-opacity duration-500">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>

    <script>
        // Sembunyikan setelah 4 detik
        setTimeout(() => {
            const msg = document.getElementById('successMessage');
            if (msg) {
                msg.classList.add('opacity-0');
                setTimeout(() => msg.remove(), 500); // hapus dari DOM setelah transisi selesai
            }
        }, 4000);
    </script>
@endif

    <!-- Summary card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 justify-items-center text-center">
            
            <div class="flex flex-col items-center">
                <div class="text-blue-600 text-2xl mb-1">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Jumlah Bayar</span>
                <span class="text-xl font-bold text-gray-800">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>

            <div class="flex flex-col items-center">
                <div class="text-green-600 text-2xl mb-1">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Tanggal Pesanan</span>
                <span class="text-xl font-medium text-gray-800">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d F Y, H:i') }}</span>
            </div>
        </div>
    </div>


        <!-- Table section -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                @if(count($pembayarans) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">ID Pembayaran</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">Tanggal</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">Status</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">Bukti Pembayaran</th>
                                <th class="py-3 px-6 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pembayarans as $pembayaran)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $pembayaran->id_pembayaran }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-700">{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d F Y, H:i') }}</td>
                                    <td class="py-4 px-6 text-sm">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            {{ $pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $pembayaran->status_pembayaran == 'proses' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $pembayaran->status_pembayaran == 'dibayar' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($pembayaran->status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-sm">
                                        @if($pembayaran->bukti_pembayaran_url)
                                            <button type="button" 
                                                    onclick="showBuktiPembayaran('{{ $pembayaran->id_pembayaran }}', '{{ $pembayaran->bukti_pembayaran_url }}')" 
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm transition-colors duration-300 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Bukti
                                            </button>
                                        @else
                                            <span class="text-gray-500 italic">Tidak ada bukti</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm">
                                        @if($pembayaran->status_pembayaran != 'dibayar')
                                            <button type="button" 
                                                    onclick="showKonfirmasiModal('{{ $pembayaran->id_pembayaran }}')"
                                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm transition-colors duration-300 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Konfirmasi
                                            </button>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Terkonfirmasi
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination with better styling -->
                    <div class="px-6 py-4 border-t">
                        {{ $pembayarans->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="inline-flex rounded-full bg-orange-100 p-4 mb-6">
                            <div class="rounded-full stroke-orange-600 bg-orange-200 p-4">
                                <svg class="w-10 h-10" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.0002 9.33337V14M14.0002 18.6667H14.0118M25.6668 14C25.6668 20.4434 20.4435 25.6667 14.0002 25.6667C7.55684 25.6667 2.3335 20.4434 2.3335 14C2.3335 7.55672 7.55684 2.33337 14.0002 2.33337C20.4435 2.33337 25.6668 7.55672 25.6668 14Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800 mb-3">Belum Ada Pembayaran</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">Belum ada catatan pembayaran untuk pesanan ini. Pelanggan belum melakukan pembayaran atau belum mengunggah bukti pembayaran.</p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-lg mx-auto">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Pembayaran akan muncul di sini ketika pelanggan telah melakukan pembayaran dan mengunggah buktinya.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal untuk menampilkan bukti pembayaran -->
    <div id="buktiPembayaranModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4">
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900">Bukti Pembayaran <span id="pembayaranIdText" class="font-bold"></span></h3>
                
            </div>
            <div class="p-6">
                <div id="buktiPembayaranContent" class="flex justify-center bg-gray-100 p-4 rounded-lg">
                    <img id="buktiPembayaranImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-96 object-contain shadow-md">
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeBuktiPembayaranModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors duration-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk konfirmasi pembayaran -->
    <div id="konfirmasiModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi Pembayaran</h3>
                <button type="button" onclick="closeKonfirmasiModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex items-center mb-4 bg-yellow-50 p-4 rounded-md border border-yellow-200">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-gray-700">Apakah Anda yakin ingin mengkonfirmasi pembayaran ini? Status pembayaran akan berubah menjadi <span class="font-bold text-green-700">dibayar</span>.</p>
                    </div>
                </div>

                <form id="konfirmasiForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_pembayaran" value="dibayar">
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeKonfirmasiModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors duration-300">
                            Batal
                        </button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors duration-300 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Ya, Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showBuktiPembayaran(id, url) {
            document.getElementById('pembayaranIdText').textContent = id;
            document.getElementById('buktiPembayaranImage').src = url;
            document.getElementById('buktiPembayaranModal').classList.remove('hidden');
            document.getElementById('buktiPembayaranModal').classList.add('animate-fade-in');
        }

        function closeBuktiPembayaranModal() {
            document.getElementById('buktiPembayaranModal').classList.add('hidden');
        }

        function showKonfirmasiModal(id) {
            const form = document.getElementById('konfirmasiForm');
            form.action = "{{ route('pegawai.pembayaran.update', '') }}/" + id;
            document.getElementById('konfirmasiModal').classList.remove('hidden');
            document.getElementById('konfirmasiModal').classList.add('animate-fade-in');
        }

        function closeKonfirmasiModal() {
            document.getElementById('konfirmasiModal').classList.add('hidden');
        }
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
@endsection