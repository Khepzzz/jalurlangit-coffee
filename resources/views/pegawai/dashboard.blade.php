@extends('pegawai.layout')

@section('title', 'Jalur Langit Coffee')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-xl p-6 mb-8 text-white">
            <h3 class="text-3xl font-bold text-white mb-2">Selamat Datang, {{ Auth::user()->nama_pegawai }}!</h3>
            <p class="text-lg opacity-90">Dashboard ini membantu Anda memantau produk, pesanan, dan informasi penting lainnya.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Penghasilan Card -->
<div class="bg-white rounded-xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <h4 class="text-xl font-semibold text-gray-800">Total Penghasilan</h4>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fa-solid fa-money-bill-wave text-green-600 text-xl"></i>
            </div>
        </div>
        <!-- Dropdown Filter Bulan -->
        <form method="GET" action="{{ url()->current() }}" class="mt-4">
            <select name="bulan" onchange="this.form.submit()" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Pilih Bulan</option>
                <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
            </select>
        </form>
        <p class="text-3xl font-bold text-green-600 mt-4">{{ 'Rp ' . number_format($totalUangPenjualan, 0, ',', '.') }}</p>
        <div class="text-center mt-6">
            <a href="laporan" class="text-green-600 hover:text-green-500 text-sm font-medium flex items-center justify-center">
                Lihat Detail Penjualan
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

            <!-- Total Produk Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xl font-semibold text-gray-800">Total Produk</h4>
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <i class="fa-solid fa-box-open text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-indigo-600 mt-4">{{ $totalProduk }}</p>
                    <div class="text-center mt-6">
                        <a href="produk" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium flex items-center justify-center">
                            Lihat Semua Produk
                            <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pesanan Belum Selesai Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xl font-semibold text-gray-800">Pesanan Belum Selesai</h4>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fa-solid fa-receipt text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-yellow-600 mt-4">{{ $pesananBelumSelesai }}</p>
                    <div class="text-center mt-6">
                        <a href="{{ route('pegawai.pesanan.index') }}" class="text-yellow-600 hover:text-yellow-500 text-sm font-medium flex items-center justify-center">
                            Lihat Semua Pesanan
                            <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

@endsection
