<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanExport implements FromArray, WithStyles, WithEvents
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];

        // Header atas
        $rows[] = ['Laporan Penjualan'];
        $rows[] = ['Tanggal Cetak: ' . Carbon::now()->format('d F Y H:i')];
        $rows[] = []; // Spasi

        // Header kolom (baris ke-4)
        $rows[] = ['No', 'Tanggal', 'Pelanggan', 'Produk', 'Subtotal Produk', 'Total Pesanan'];

        $no = 1;
        $totalKeseluruhan = 0;

        foreach ($this->data as $item) {
            $produkList = [];
            $subtotalList = [];

            foreach ($item->detailPesanan as $detail) {
                $nama = $detail->produk->nama_produk ?? 'Produk Tidak Diketahui';
                $jumlah = $detail->jumlah ?? 0;
                $harga = $detail->produk->harga ?? 0;
                $subtotal = $jumlah * $harga;
                $produkList[] = '• ' . $nama . " (x{$jumlah})";
                $subtotalList[] = '• Rp ' . number_format($subtotal, 0, ',', '.');
            }

            $rows[] = [
                $no++,
                Carbon::parse($item->tanggal_pesanan)->format('d F Y H:i'),
                $item->pelanggan->nama_pelanggan ?? 'Tidak Diketahui',
                implode("\n", $produkList),
                implode("\n", $subtotalList),
                'Rp ' . number_format($item->total_harga, 0, ',', '.'),
            ];

            $totalKeseluruhan += $item->total_harga;
        }

        // Baris kosong dan total keseluruhan
        $rows[] = [];
        $rows[] = ['Total Keseluruhan', '', '', '', '', 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.')];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]], // Judul
            2 => ['font' => ['italic' => true]],             // Tanggal cetak
            3 => [ // Header kolom
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Wrap kolom produk dan subtotal
                $sheet->getStyle("D5:E" . ($highestRow - 2))
                      ->getAlignment()->setWrapText(true);

                // Border tabel (data dari baris 5 sampai sebelum total keseluruhan)
                $sheet->getStyle("A3:F" . ($highestRow - 2))->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Border atas untuk total keseluruhan
                $sheet->getStyle("A{$highestRow}:F{$highestRow}")->applyFromArray([
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THICK],
                    ],
                    'font' => ['bold' => true],
                ]);

                // Lebar kolom
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(40);
                $sheet->getColumnDimension('E')->setWidth(30);
                $sheet->getColumnDimension('F')->setWidth(20);

                // Background header kolom
                $sheet->getStyle("A3:F3")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);
            },
        ];
    }
}
