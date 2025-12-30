<?php

namespace App\Exports;

use App\Models\TransaksiMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransaksiMasukExport implements FromCollection, WithEvents
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
        $basequery = TransaksiMasuk::with('product', 'jasa', 'paket');

        if ($this->startDate && $this->endDate) {
            $basequery->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        $data = $basequery
            ->orderBy('tanggal', 'asc')
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'Tanggal' => $row->tanggal,
                'Item'    => $row->product->nama
                              ?? $row->jasa->nama
                              ?? $row->paket->nama
                              ?? '-',
                'Jumlah'  => $row->qty ?? 1,
                'Total'   => $row->harga_total,
            ];
        }

        // ===============================
        // TOTAL (SAMA DENGAN FUNCTION)
        // ===============================
        $total = $data->sum('harga_total');

        // baris total
        $result[] = [
            'Tanggal' => '',
            'Item'    => 'TOTAL',
            'Jumlah'  => '',
            'Total'   => $total,
        ];

        return collect($result);
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:D1')->setCellValue('A1', 'TRANSAKSI MASUK');
                $sheet->mergeCells('A2:D2')->setCellValue(
                    'A2',
                    'Periode: '.$this->startDate.' s/d '.$this->endDate
                );

                // Header
                $headers = ['Tanggal','Item','Jumlah','Total'];
                $col = 'A';
                foreach($headers as $header){
                    $sheet->setCellValue($col.'3', $header);
                    $col++;
                }

                // Style header
                $sheet->getStyle('A1:D3')->getFont()->setBold(true);
                $sheet->getStyle('A1:D3')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Data
                $sheet->fromArray($this->collection()->toArray(), null, 'A4');

                $lastRow = 3 + $this->collection()->count();

                // Border
                $sheet->getStyle('A3:D'.$lastRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Format angka
                $sheet->getStyle('C4:D'.$lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Bold TOTAL
                $sheet->getStyle('A'.$lastRow.':D'.$lastRow)
                    ->getFont()
                    ->setBold(true);
            }
        ];
    }
}
