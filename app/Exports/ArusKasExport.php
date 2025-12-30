<?php

namespace App\Exports;

use App\Http\Controllers\LaporanController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ArusKasExport implements FromCollection, WithEvents
{
    protected $startDate;
    protected $endDate;

    protected $rows = [];
    protected $rowCount = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        $controller = new LaporanController();
        $arusKas = $controller->getDataArusKas($this->startDate, $this->endDate);

        $rows = [];

        /* ===== SALDO AWAL ===== */
        $rows[] = ['SALDO AWAL KAS', $arusKas['saldo_awal']];

        /* ===== OPERASIONAL ===== */
        $rows[] = ['A. Arus Kas Dari Kegiatan Operasional', ''];

        foreach ($arusKas['kas_operasional'] as $row) {
            $rows[] = [$row->akun, $row->total];
        }

        $rows[] = [
            'Jumlah Kas Tersedia Dari Kegiatan Operasional',
            $arusKas['total_kas_operasional']
        ];

        /* ===== INVESTASI ===== */
        $rows[] = ['B. Arus Kas Dari Kegiatan Investasi', ''];

        foreach ($arusKas['kas_investasi'] as $row) {
            $rows[] = [$row->akun, $row->total];
        }

        $rows[] = [
            'Jumlah Kas Tersedia Dari Kegiatan Investasi',
            $arusKas['total_kas_investasi']
        ];

        /* ===== PENDANAAN ===== */
        $rows[] = ['C. Arus Kas Dari Kegiatan Pendanaan', ''];

        foreach ($arusKas['kas_pendanaan'] as $row) {
            $rows[] = [$row->akun, $row->total];
        }

        $rows[] = [
            'Jumlah Kas Tersedia Dari Kegiatan Pendanaan',
            $arusKas['total_kas_pendanaan']
        ];

        /* ===== TOTAL ===== */
        $rows[] = [
            'PERGERAKAN BERSIH ATAS KAS (A+B+C)',
            $arusKas['pergerakan_bersih']
        ];

        $rows[] = [
            'SALDO AKHIR KAS',
            $arusKas['saldo_akhir']
        ];

        $this->rows = $rows;
        $this->rowCount = count($rows);

        return collect($rows);
    }

   public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            $sheet = $event->sheet->getDelegate();

            /* ===== BERSIHKAN SHEET ===== */
            $sheet->removeRow(1, 1000);

            /* ===== JUDUL ===== */
            $sheet->mergeCells('A1:B1')->setCellValue('A1', 'LAPORAN ARUS KAS');
            $sheet->mergeCells('A2:B2')->setCellValue(
                'A2',
                'Periode: ' . $this->startDate . ' s/d ' . $this->endDate
            );

            /* ===== HEADER ===== */
            $sheet->setCellValue('A4', 'AKUN');
            $sheet->setCellValue('B4', 'TOTAL');

            $sheet->getStyle('A1:B4')->getFont()->setBold(true);
            $sheet->getStyle('A4:B4')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            /* ===== DATA ===== */
            $sheet->fromArray($this->rows, null, 'A5');

            $lastRow = 4 + count($this->rows);

            /* ===== BORDER ===== */
            $sheet->getStyle("A4:B{$lastRow}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);

            /* ===== FORMAT ANGKA ===== */
            $sheet->getStyle("B5:B{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            $sheet->getStyle("B5:B{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            /* ===== BOLD SECTION ===== */
            for ($i = 5; $i <= $lastRow; $i++) {
                $val = (string) $sheet->getCell("A{$i}")->getValue();

                if (
                    str_starts_with($val, 'A.') ||
                    str_starts_with($val, 'B.') ||
                    str_starts_with($val, 'C.') ||
                    str_contains($val, 'Jumlah Kas') ||
                    str_contains($val, 'SALDO') ||
                    str_contains($val, 'PERGERAKAN')
                ) {
                    $sheet->getStyle("A{$i}:B{$i}")
                        ->getFont()->setBold(true);
                }
            }
        }
    ];
}

}
