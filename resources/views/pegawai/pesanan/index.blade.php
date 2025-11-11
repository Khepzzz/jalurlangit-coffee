@extends('pegawai.layout')
@section('title', 'Daftar Pesanan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </span>
            Daftar Pesanan
        </h2>
    </div>

    <!-- Dropdown Filter Pesanan -->
    <div class="mb-6">
        <div class="flex items-center gap-2">
            <label for="statusFilter" class="text-sm font-medium text-gray-700">
                🧾 Filter Pesanan:
            </label>
            <form id="statusForm" method="GET" action="{{ route('pegawai.pesanan.index') }}">
                <select name="status" id="statusFilter"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <option value="" {{ request('status') == null ? 'selected' : '' }}>📋 Semua</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>⚙️ Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                    <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
                </select>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('statusFilter').addEventListener('change', function () {
            const selected = this.value;
            const form = document.getElementById('statusForm');

            if (!selected) {
                // Redirect tanpa parameter jika pilih "Semua"
                window.location.href = "{{ route('pegawai.pesanan.index') }}";
            } else {
                form.submit();
            }
        });
    </script>

    @if(session('success'))
        <div id="successAlert" class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md transition-all duration-300 relative">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('successAlert');
                if (alert) {
                    alert.classList.add('opacity-0');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 3000);
        </script>
    @endif

    <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-800">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50 to-blue-100 text-xs uppercase text-blue-900 tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold">No</th>
                        <th class="px-6 py-4 text-left font-semibold">id pesanan</th>
                        <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-left font-semibold">Nama</th>
                        <th class="px-6 py-4 text-left font-semibold">No. Kursi</th>
                        <th class="px-6 py-4 text-left font-semibold">Total</th>
                        <th class="px-6 py-4 text-left font-semibold">Status Pesanan</th>
                        <th class="px-6 py-4 text-left font-semibold">Status Pembayaran</th>
                        <th class="px-6 py-4 text-left font-semibold">Tampilan Detail</th>
                        <th class="px-6 py-4 text-left font-semibold">Perbarui Status Pesanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($daftar_pesanan as $pesanan)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-medium">{{ $pesanan->id_pesanan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d F Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($pesanan->pelanggan->nomor_kursi ?? false)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 rounded-lg text-gray-800">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                        {{ $pesanan->pelanggan->nomor_kursi }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($pesanan->status_pesanan == 'Pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pending
                                    </span>
                                @elseif($pesanan->status_pesanan == 'Diproses')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Diproses
                                    </span>
                                @elseif($pesanan->status_pesanan == 'Selesai')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai
                                    </span>
                                @elseif($pesanan->status_pesanan == 'Dibatalkan')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-300">
                                        {{ $pesanan->status_pesanan }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusPembayaran = $pesanan->pembayaran->status_pembayaran ?? null;
                                @endphp

                                @if($statusPembayaran == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pending
                                    </span>
                                @elseif($statusPembayaran == 'proses')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Diproses
                                    </span>
                                @elseif($statusPembayaran == 'dibayar')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Dibayar
                                    </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-300">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9l10 5-10 5V9z" />
                                    </svg>
                                    Belum Bayar
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-4">
                                    <!-- Tombol Pesanan -->
                                    <a href="{{ route('pegawai.pesanan.show', $pesanan->id_pesanan) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Pesanan
                                    </a>
                                    <!-- Tombol Pembayaran -->
                                    <a href="{{ route('pegawai.pembayaran.daftar', ['id_pesanan' => $pesanan->id_pesanan]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Pembayaran
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('pegawai.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
                                    @csrf
                                    <select name="status_pesanan" class="block w-full text-sm border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-1.5"
                                        onchange="this.form.submit()">
                                        <option disabled selected>Ubah Status</option>
                                        <option value="Pending" {{ $pesanan->status_pesanan == 'Pending' ? 'selected' : 'bg-yellow-100 text-yellow-800' }}>Pending</option>
                                        <option value="Diproses" {{ $pesanan->status_pesanan == 'Diproses' ? 'selected' : 'bg-blue-100 text-blue-800' }}>Diproses</option>
                                        <option value="Selesai" {{ $pesanan->status_pesanan == 'Selesai' ? 'selected' : 'bg-green-100 text-green-800' }}>Selesai</option>
                                        <option value="Dibatalkan" {{ $pesanan->status_pesanan == 'Dibatalkan' ? 'selected' : 'bg-red-100 text-red-800' }}>Dibatalkan</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada pesanan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $daftar_pesanan->links() }}
    </div>
</div>
@endsection