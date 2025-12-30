<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanStockExport implements FromCollection, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate){
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(){
        $data = [];
        $products = Product::with('pakets')->get();

        foreach ($products as $product) {
            // 1️⃣ HITUNG STOK AWAL
            $stokMasukSebelum = DB::table('transaksi_keluars')
                ->where('product_id', $product->id)
                ->where('tanggal', '<', $this->startDate)
                ->sum('qty');

            $stokKeluarBarangSebelum = DB::table('transaksi_masuks')
                ->where('product_id', $product->id)
                ->where('tanggal', '<', $this->startDate)
                ->sum('qty');

            $stokKeluarPaketSebelum = DB::table('pakets')
                ->join('transaksi_masuks', 'transaksi_masuks.paket_id', '=', 'pakets.id')
                ->where('pakets.product_id', $product->id)
                ->where('transaksi_masuks.tanggal', '<', $this->startDate)
                ->count();

            $stokAwal = $stokMasukSebelum - ($stokKeluarBarangSebelum + $stokKeluarPaketSebelum);

            // 2️⃣ TRANSAKSI DALAM RANGE
            $masuk = DB::table('transaksi_keluars')
                ->where('product_id', $product->id)
                ->whereBetween('tanggal', [$this->startDate, $this->endDate])
                ->select('tanggal', 'qty', DB::raw("'masuk' as tipe"));

            $keluarBarang = DB::table('transaksi_masuks')
                ->where('product_id', $product->id)
                ->whereBetween('tanggal', [$this->startDate, $this->endDate])
                ->select('tanggal', 'qty', DB::raw("'keluar' as tipe"));

            $keluarPaket = DB::table('pakets')
                ->join('transaksi_masuks', 'transaksi_masuks.paket_id', '=', 'pakets.id')
                ->where('pakets.product_id', $product->id)
                ->whereBetween('transaksi_masuks.tanggal', [$this->startDate, $this->endDate])
                ->select('transaksi_masuks.tanggal as tanggal', DB::raw('1 as qty'), DB::raw("'keluar' as tipe"));

            $transaksi = $masuk->unionAll($keluarBarang)->unionAll($keluarPaket)
                ->orderBy('tanggal')
                ->get()
                ->groupBy('tanggal');

            // 3️⃣ KARTU STOK
            $stok = $stokAwal;
            $kartuStok = [];

            foreach ($transaksi as $tanggal => $items) {
                $totalMasuk = $items->where('tipe', 'masuk')->sum('qty');
                $totalKeluar = $items->where('tipe', 'keluar')->sum('qty');

                $stokAkhir = $stok + $totalMasuk - $totalKeluar;

                $kartuStok[] = [
                    'Tanggal' => $tanggal,
                    'Nama Produk' => $product->nama,
                    'Kategori' => $product->kategori,
                    'Stok Awal' => $stok,
                    'Masuk' => $totalMasuk,
                    'Keluar' => $totalKeluar,
                    'Stok Akhir' => $stokAkhir,
                ];

                $stok = $stokAkhir;
            }

            // simpan ke data
            $data = array_merge($data, $kartuStok);
        }

        return collect($data);
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:G1')->setCellValue('A1','LAPORAN STOCK PRODUK');
                $sheet->mergeCells('A2:G2')->setCellValue('A2','Periode: '.$this->startDate.' s/d '.$this->endDate);

                $headers = ['Tanggal','Nama Produk','Kategori','Stok Awal','Masuk','Keluar','Stok Akhir'];
                $col = 'A';
                foreach($headers as $header){
                    $sheet->setCellValue($col.'3',$header);
                    $col++;
                }

                $sheet->getStyle('A1:H3')->getFont()->setBold(true);
                $sheet->getStyle('A1:H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->fromArray($this->collection()->toArray(), null, 'A4');

                $lastRow = 3 + count($this->collection());
                $sheet->getStyle('A3:H'.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('E4:H'.$lastRow)->getNumberFormat()->setFormatCode('#,##0');
            }
        ];
    }
}
