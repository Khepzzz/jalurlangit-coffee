@extends('pegawai.layout')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-3xl p-6 sm:p-10 space-y-12 border border-gray-100">
        
        <div class="flex items-center justify-center">
            <div class="relative">
                <h2 class="text-3xl font-extrabold text-gray-800 text-center">
                    Detail Pesanan
                </h2>
                <div class="text-3xl font-extrabold text-gray-800 text-center">
                {{ $pesanan->id_pesanan }}
                </div>
            </div>
        </div>

        <!-- Highlight Info Pelanggan -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-8 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="space-y-2 p-4 rounded-xl bg-white bg-opacity-60 shadow-sm border border-blue-50">
                    <div class="text-blue-500 text-xl mb-1">👤</div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Nama Pelanggan</p>
                    <p class="text-xl font-bold text-gray-800">{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</p>
                </div>
                <div class="space-y-2 p-4 rounded-xl bg-white bg-opacity-60 shadow-sm border border-blue-50">
                    <div class="text-blue-500 text-xl mb-1">💺</div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Nomor Kursi</p>
                    <p class="text-xl font-bold text-gray-800">{{ $pesanan->pelanggan->nomor_kursi ?? '-' }}</p>
                </div>
                <div class="space-y-2 p-4 rounded-xl bg-white bg-opacity-60 shadow-sm border border-blue-50">
                    <div class="text-blue-500 text-xl mb-1">📅</div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Tanggal Pesanan</p>
                    <p class="text-xl font-bold text-gray-800">
                        {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabel Produk dengan Scroll Horizontal -->
        <div class="border border-gray-200 rounded-xl shadow-md">
            <!-- Scroll indicator shadows -->
            <div class="relative">
                <!-- Left shadow (appears when scrolling) -->
                <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent pointer-events-none z-10 rounded-l-xl opacity-0 transition-opacity duration-300" id="leftShadow"></div>
                
                <!-- Right shadow (indicates scrollable content) -->
                <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none z-10 rounded-r-xl transition-opacity duration-300" id="rightShadow"></div>
                
                <!-- Scrollable container -->
                <div class="overflow-x-auto" id="tableScroll">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-xs uppercase font-semibold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 text-left whitespace-nowrap">Produk</th>
                                <th class="px-6 py-4 text-center whitespace-nowrap">Gambar</th>
                                <th class="px-6 py-4 text-center whitespace-nowrap">Jumlah</th>
                                <th class="px-6 py-4 text-center whitespace-nowrap">Harga</th>
                                <th class="px-6 py-4 text-center whitespace-nowrap">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($pesanan->detailPesanan as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 font-medium whitespace-nowrap">{{ $item->produk->nama_produk ?? 'Produk dihapus' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="w-20 h-20 mx-auto relative rounded-lg overflow-hidden">
                                        @if($item->produk && $item->produk->gambar_produk)
                                        <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}"
                                            alt="{{ $item->produk->nama_produk }}"
                                            class="w-full h-full object-cover absolute top-0 left-0">
                                        @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                            No Image
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                                        {{ $item->jumlah }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">Rp {{ number_format($item->produk->harga ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-800 whitespace-nowrap">
                                    <span class="text-indigo-600">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Total Pembayaran (Fixed outside the scrollable table) -->
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 flex justify-between items-center">
            <div class="text-indigo-800 font-semibold text-lg">
                Total Pembayaran
            </div>
            <div class="text-2xl font-bold text-indigo-700">
                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
            </div>
        </div>

        <!-- Tombol Kembali -->
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
</div>

<!-- JavaScript for table scroll shadows -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableScroll = document.getElementById('tableScroll');
        const leftShadow = document.getElementById('leftShadow');
        const rightShadow = document.getElementById('rightShadow');
        
        // Check if scrolling is needed
        function checkScroll() {
            // Show right shadow only if content is scrollable
            if (tableScroll.scrollWidth > tableScroll.clientWidth) {
                rightShadow.style.opacity = '1';
            } else {
                rightShadow.style.opacity = '0';
            }
            
            // Show left shadow if scrolled
            if (tableScroll.scrollLeft > 0) {
                leftShadow.style.opacity = '1';
            } else {
                leftShadow.style.opacity = '0';
            }
            
            // Hide right shadow when scrolled to the end
            if (tableScroll.scrollLeft + tableScroll.clientWidth >= tableScroll.scrollWidth - 2) {
                rightShadow.style.opacity = '0';
            } else if (tableScroll.scrollWidth > tableScroll.clientWidth) {
                rightShadow.style.opacity = '1';
            }
        }
        
        // Initial check
        checkScroll();
        
        // Add scroll event listener
        tableScroll.addEventListener('scroll', checkScroll);
        
        // Add resize event listener
        window.addEventListener('resize', checkScroll);
    });
</script>
@endsection