<?php

namespace App\Exports;

use App\Models\TransaksiKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransaksiKeluarExport implements FromCollection, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate){
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection(){
        // ===============================
        // QUERY SAMA PERSIS DENGAN FUNCTION
        // ===============================
        $basequery = TransaksiKeluar::with('product', 'supplier');

        if ($this->startDate && $this->endDate) {
            $basequery->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        $data = $basequery
            ->orderBy('tanggal', 'asc')
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'Tanggal'  => $row->tanggal,
                'Produk'   => $row->product->nama ?? '-',
                'Supplier' => $row->supplier->nama ?? '-',
                'Jumlah'   => $row->qty ?? 1,
                'Harga'    => $row->harga_satuan ?? 0,
                'Total'    => $row->harga_total,
            ];
        }

        // ===============================
        // TOTAL (SAMA DENGAN FUNCTION)
        // ===============================
        $total = $data->sum('harga_total');

        $result[] = [
            'Tanggal'  => '',
            'Produk'   => 'TOTAL',
            'Supplier' => '',
            'Jumlah'   => '',
            'Harga'    => '',
            'Total'    => $total,
        ];

        return collect($result);
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:F1')->setCellValue('A1','TRANSAKSI KELUAR');
                $sheet->mergeCells('A2:F2')->setCellValue(
                    'A2',
                    'Periode: '.$this->startDate.' s/d '.$this->endDate
                );

                // Header
                $headers = ['Tanggal','Produk','Supplier','Jumlah','Harga','Total'];
                $col = 'A';
                foreach($headers as $header){
                    $sheet->setCellValue($col.'3', $header);
                    $col++;
                }

                // Style header
                $sheet->getStyle('A1:F3')->getFont()->setBold(true);
                $sheet->getStyle('A1:F3')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Data
                $sheet->fromArray($this->collection()->toArray(), null, 'A4');

                $lastRow = 3 + $this->collection()->count();

                // Border
                $sheet->getStyle('A3:F'.$lastRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Format angka
                $sheet->getStyle('D4:F'.$lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Bold TOTAL
                $sheet->getStyle('A'.$lastRow.':F'.$lastRow)
                    ->getFont()
                    ->setBold(true);
            }
        ];
    }
}
