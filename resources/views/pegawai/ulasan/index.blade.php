@extends('pegawai.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-indigo-700 flex items-center">
                Data Ulasan Produk
            </h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column - Filter and Statistics -->
        <div class="lg:col-span-4">
            <!-- Filter Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="border-b px-6 py-4">
                    <h5 class="font-bold text-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Data
                    </h5>
                </div>
                <div class="p-6">
                    <form method="GET">
                        <div class="mb-4">
                            <label for="produk_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Produk</label>
                            <select name="produk_id" id="produk_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3" onchange="this.form.submit()">
                                <option value="">-- Semua Produk --</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id_produk }}" {{ $produkId == $produk->id_produk ? 'selected' : '' }}>
                                        {{ $produk->nama_produk }} ({{ $produk->ulasan_count }} ulasan)
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                    </form>
                </div>
            </div>

            <!-- Statistik -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h5 class="font-bold text-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistik Kepuasan
                    </h5>
                    <p class="text-xs text-gray-500 mt-1">
                        Dihitung menggunakan pendekatan metode 
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded">CAST</span>
                    </p>
                </div>
                <div class="p-6">
                    <!-- Nilai Persepsi -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">Nilai Persepsi</span>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $nilaiPersepsi }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <!-- Menyesuaikan batas maksimal progress bar untuk nilai persepsi -->
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($nilaiPersepsi / 100) * 100, 100) }}%"></div> <!-- Menggunakan 10 sebagai nilai maksimal -->
                        </div>
                    </div>
                    
                    <!-- Nilai Ekspektasi -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">Nilai Ekspektasi</span>
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $nilaiEkspektasi }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <!-- Menyesuaikan batas maksimal progress bar untuk nilai ekspektasi -->
                            <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ min(($nilaiEkspektasi / 100) * 100, 100) }}%"></div> <!-- Menggunakan 10 sebagai nilai maksimal -->
                        </div>
                    </div>
                    
                    <!-- Nilai Kepuasan -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">Nilai Kepuasan</span>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ number_format($nilaiKepuasan, 2) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ min($nilaiKepuasan, 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Statistik Ulasan -->
                    <div class="">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">Jumlah Ulasan</span>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $ulasanAll->count() }} Ulasan
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <!-- Menyesuaikan persentase dengan jumlah ulasan yang lebih realistis -->
                            <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ min((count($ulasan) / 100) * 100, 100) }}%"></div> 
                        </div>
                    </div>
                </div>
                <!-- Overall satisfaction indicator -->
                <div class="text-center mb-6">
                    <div class="text-5xl font-semibold 
                        @if($nilaiKepuasan >= 85) text-green-600
                        @elseif($nilaiKepuasan >= 70) text-blue-600
                        @elseif($nilaiKepuasan >= 50) text-yellow-500
                        @else text-red-600
                        @endif">
                        {{ number_format($nilaiKepuasan, 1) }}%
                    </div>
                    <div class="mt-4">
                        @if($nilaiKepuasan >= 85)
                            <span class="bg-gradient-to-r from-green-400 to-green-600 text-white text-xs font-medium px-3 py-1 rounded-full shadow-md transform hover:scale-105 transition duration-300">
                                Sangat Baik
                            </span>
                        @elseif($nilaiKepuasan >= 70)
                            <span class="bg-gradient-to-r from-blue-400 to-blue-600 text-white text-xs font-medium px-3 py-1 rounded-full shadow-md transform hover:scale-105 transition duration-300">
                                Bagus
                            </span>
                        @elseif($nilaiKepuasan >= 50)
                            <span class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-xs font-medium px-3 py-1 rounded-full shadow-md transform hover:scale-105 transition duration-300">
                                Kurang
                            </span>
                        @else
                            <span class="bg-gradient-to-r from-red-400 to-red-600 text-white text-xs font-medium px-3 py-1 rounded-full shadow-md transform hover:scale-105 transition duration-300">
                                Perlu Perbaikan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    

        <!-- TABEL ULASAN -->
<div class="lg:col-span-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b px-6 py-4 flex justify-between items-center">
            <h5 class="font-bold text-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                Daftar Ulasan
            </h5>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($ulasan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-md flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}" alt="{{ $item->produk->nama_produk }}" class="h-8 w-8 object-cover rounded-md">
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $item->produk->nama_produk ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm text-gray-900">{{ $item->pelanggan->nama ?? 'Anonim' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $item->rating)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $item->komentar }}">
                                    {{ $item->komentar }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded-lg">
                                    {{ \Carbon\Carbon::parse($item->tanggal_ulasan)->format('d F Y') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <p class="mt-2 text-gray-500">Belum ada ulasan untuk produk ini.</p>
                                    @if($produkId)
                                        <a href="{{ route('pegawai.ulasan.index') }}" class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Tampilkan Semua Produk
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $ulasan->count() }} dari {{ $ulasanAll->count() }} ulasan
            </div>
            <!-- Paginasi -->
            {{ $ulasan->appends(['produk_id' => $produkId])->links() }}
        </div>
    </div>
</div>
        
</div>

@push('scripts')
<script>
    // Initialize tooltips (if you have a tooltip library)
    // You might need to add a tooltip library like tippy.js or implement your own
    document.addEventListener('DOMContentLoaded', function() {
        // Add tooltip functionality here if needed
    });
</script>
@endpush
@endsection