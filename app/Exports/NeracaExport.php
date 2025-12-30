<?php

namespace App\Exports;

use App\Http\Controllers\LaporanController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;

class NeracaExport implements FromCollection, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        $controller = new LaporanController();
        $neraca = $controller->buildNeraca($this->startDate, $this->endDate);

        $data = [];
        foreach(['asetLancar','asetTetap','kewajiban','ekuitas'] as $section){
            if(isset($neraca[$section]['data'])){
                foreach($neraca[$section]['data'] as $akun){
                    $data[] = [
                        'Kode'  => $akun['kode'],
                        'Akun'  => $akun['nama'],
                        'Total' => $akun['total'],
                    ];
                }
                $data[] = ['Kode'=>'','Akun'=>$section.' Total','Total'=>$neraca[$section]['total']];
                $data[] = [];
            }
        }

        return collect($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1','NERACA');
                $sheet->mergeCells('A2:C2');
                $sheet->setCellValue('A2','Periode: '.$this->startDate.' s/d '.$this->endDate);

                $headers = ['Kode','Akun','Total'];
                $col = 'A';
                foreach($headers as $header){
                    $sheet->setCellValue($col.'3',$header);
                    $col++;
                }

                $sheet->getStyle('A1:C3')->getFont()->setBold(true);
                $sheet->getStyle('A1:C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->fromArray($this->collection()->toArray(), null, 'A4');

                // Border seluruh tabel
                $lastRow = 3 + count($this->collection());
                $sheet->getStyle('A3:C'.$lastRow)
                      ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Format angka
                $sheet->getStyle('C4:C'.$lastRow)->getNumberFormat()->setFormatCode('#,##0');
            }
        ];
    }
}
