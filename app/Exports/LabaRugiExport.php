<?php

namespace App\Exports;

use App\Http\Controllers\LaporanController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LabaRugiExport implements FromCollection, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $labaRugi;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;

        $controller = new LaporanController();
        $this->labaRugi = $controller->buildLabaRugi($startDate, $endDate);
    }

    public function collection()
    {
        $data = [];

        /** =======================
         *  PENDAPATAN
         *  ======================= */
        $data[] = ['Kode' => 'PENDAPATAN', 'Akun' => '', 'Total' => ''];

        foreach ($this->labaRugi['pendapatan']['data'] ?? [] as $akun) {
            $data[] = [
                'Kode'  => $akun['kode'],
                'Akun'  => $akun['nama'],
                'Total' => $akun['total'],
            ];
        }

        $data[] = [
            'Kode'  => '',
            'Akun'  => 'Total Pendapatan',
            'Total' => $this->labaRugi['pendapatan']['total'] ?? 0
        ];

        $data[] = [];

        /** =======================
         *  BEBAN
         *  ======================= */
        $data[] = ['Kode' => 'BEBAN', 'Akun' => '', 'Total' => ''];

        // HPP
        if (!empty($this->labaRugi['beban']['hpp'])) {
            $data[] = [
                'Kode'  => '',
                'Akun'  => 'Harga Pokok Penjualan (HPP)',
                'Total' => $this->labaRugi['beban']['hpp']
            ];
        }

        foreach ($this->labaRugi['beban']['data'] ?? [] as $akun) {
            $data[] = [
                'Kode'  => $akun['kode'],
                'Akun'  => $akun['nama'],
                'Total' => $akun['total'],
            ];
        }

        /** === TOTAL BEBAN (POSISI BENAR) === */
        $totalBeban =
            ($this->labaRugi['beban']['total'] ?? 0)
            + ($this->labaRugi['beban']['hpp'] ?? 0);

        $data[] = [
            'Kode'  => '',
            'Akun'  => 'Total Beban',
            'Total' => $totalBeban
        ];

        $data[] = [];

        /** =======================
         *  LABA / RUGI BERSIH
         *  ======================= */
        $labaBersih =
            ($this->labaRugi['pendapatan']['total'] ?? 0)
            - $totalBeban;

        $data[] = [
            'Kode'  => 'LABA / RUGI BERSIH',
            'Akun'  => '',
            'Total' => $labaBersih
        ];

        return collect($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:C1')->setCellValue('A1', 'LAPORAN LABA RUGI');
                $sheet->mergeCells('A2:C2')->setCellValue(
                    'A2',
                    'Periode: ' . $this->startDate . ' s/d ' . $this->endDate
                );

                // Header
                $sheet->fromArray(['Kode', 'Akun', 'Total'], null, 'A3');

                $sheet->getStyle('A1:C3')->getFont()->setBold(true);
                $sheet->getStyle('A1:C3')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Data
                $sheet->fromArray($this->collection()->toArray(), null, 'A4');

                $lastRow = 3 + $this->collection()->count();

                // Border
                $sheet->getStyle("A3:C{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Format angka
                $sheet->getStyle("C4:C{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Lebar kolom
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(40);
                $sheet->getColumnDimension('C')->setWidth(20);
            }
        ];
    }
}
