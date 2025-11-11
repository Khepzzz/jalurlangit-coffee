<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 20px 40px;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 70px;
            margin-bottom: 5px;
        }

        h2 {
            margin: 5px 0;
            font-size: 18px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .product-list ul, .subtotal-list ul {
            padding-left: 15px;
            margin: 0;
        }

        .total-row {
            background-color: #e3f2fd;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 50px;
            font-size: 11px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature p {
            margin-bottom: 60px;
        }

        .text-center {
            text-align: center;
        }

    </style>
</head>
<body>

    <header>
        {{-- Tambahkan logo di sini jika punya --}}
        {{-- <img src="{{ public_path('logo.png') }}" alt="Logo" class="logo"> --}}
        <h2>Laporan Penjualan</h2>
        <div>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</div>
    </header>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Harga Satuan</th>
                <th>Subtotal Produk</th>
                <th>Total Pesanan</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($laporan as $index => $pesanan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d F Y ') }}</td>
                    <td>{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td class="product-list">
                        <ul>
                            @foreach($pesanan->detailPesanan as $detail)
                                <li>{{ $detail->produk->nama_produk }} (x{{ $detail->jumlah }})</li>
                            @endforeach
                        </ul>
                    </td><td class="product-list">
                        <ul>
                            @foreach($pesanan->detailPesanan as $detail)
                                <li>{{ $detail->produk->harga }} </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="subtotal-list text-right">
                        <ul>
                            @foreach($pesanan->detailPesanan as $detail)
                                @php
                                    $subtotal = $detail->jumlah * $detail->produk->harga;
                                @endphp
                                <li>Rp {{ number_format($subtotal, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                </tr>
                @php $grandTotal += $pesanan->total_harga; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-right">Total Keseluruhan</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <p>Jepara, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Admin,</p>
        <strong>( ______________________ )</strong>
    </div>

</body>
</html>
