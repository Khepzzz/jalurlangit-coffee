@extends('pegawai.layout')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Penjualan</h1>

    {{-- Form Filter Tanggal --}}
    <form action="{{ route('pegawai.laporan.index') }}" method="GET" class="flex items-center gap-4 mb-6">
        <div>
            <label for="tanggal_awal" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300">
        </div>
        <div>
            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300">
        </div>
        <div class="pt-6">
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Filter</button>
        </div>
    </form>

    {{-- Tombol Export --}}
    <div class="mb-6">
        <a href="{{ route('pegawai.laporan.export.excel', request()->query()) }}" 
           class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Download Excel</a>
        <a href="{{ route('pegawai.laporan.export.pdf', request()->query()) }}" 
           class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Download PDF</a>
    </div>

    {{-- Tabel Laporan --}}
    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-3 px-4 border-b">No</th>
                <th class="py-3 px-4 border-b">Tanggal Pesanan</th>
                <th class="py-3 px-4 border-b">Nama Pelanggan</th>
                <th class="py-3 px-4 border-b">Produk</th>
                <th class="py-3 px-4 border-b">Jumlah</th>
                <th class="py-3 px-4 border-b">Subtotal</th>
                <th class="py-3 px-4 border-b">Total Pesanan</th>
                <th class="py-3 px-4 border-b">Status Pesanan</th>
                <th class="py-3 px-4 border-b">Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $index => $item)
            <tr class="text-center">
                <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pesanan)->format('d F Y h:i') }}</td>
                <td class="py-2 px-4 border-b">{{ $item->pelanggan->nama_pelanggan ?? 'Tidak Diketahui' }}</td>
                <td class="py-2 px-4 border-b">
                    <ul class="list-disc list-inside text-left">
                        @foreach ($item->detailPesanan ?? [] as $detail)
                        <li>{{ $detail->produk->nama_produk }}</li>
                        @endforeach
                    </ul>
                </td>
                <td class="py-2 px-4 border-b">
                    <ul class="list-disc list-inside text-left">
                        @foreach ($item->detailPesanan ?? [] as $detail)
                        <li>{{ $detail->jumlah }}</li>
                        @endforeach
                    </ul>
                </td>
                <td class="py-2 px-4 border-b">
                    <ul class="list-disc list-inside text-left">
                        @foreach ($item->detailPesanan ?? [] as $detail)
                        <li>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</li>
                        @endforeach
                    </ul>
                </td>
                <td class="py-2 px-4 border-b font-semibold">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 rounded bg-gray-200">{{ $item->status_pesanan }}</span>
                </td>
                <td class="py-2 px-4 border-b">
                    {{ $item->pembayaran->status_pembayaran ?? 'Belum Bayar' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="py-4 text-center">
                    @if ($tanggal_awal && $tanggal_akhir)
                        Tidak ada data penjualan untuk rentang tanggal {{ \Carbon\Carbon::parse($tanggal_awal)->format('d F Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d F Y') }}.
                    @else
                        Silakan pilih rentang tanggal untuk menampilkan laporan.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
