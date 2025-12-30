<?php

namespace App\Exports;

use App\Models\JurnalDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BukuBesarExport implements FromCollection, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate){
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(){
        $basequery = JurnalDetail::with('akun', 'jurnal_header')
            ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
            ->join('akuns', 'akuns.id', '=', 'jurnal_details.akun_id')
            ->orderBy('akuns.id','asc')
            ->orderBy('jurnal_headers.tanggal','asc');

        if($this->startDate && $this->endDate){
            $basequery->whereBetween('jurnal_headers.tanggal', [$this->startDate, $this->endDate]);
        }

        $entries = $basequery->get()->groupBy('akun_id');

        $data = [];
        foreach($entries as $entry){
            $akun = $entry->first()->akun;
            $total_debit = 0;
            $total_kredit = 0;

            foreach($entry as $item){
                $saldo_debit = '';
                $saldo_kredit = '';

                if($akun->normal_post == 'Debit'){
                    $total_debit += ($item->nominal_debit - $item->nominal_kredit);
                    $saldo_debit = $total_debit;
                } else {
                    $total_kredit += ($item->nominal_kredit - $item->nominal_debit);
                    $saldo_kredit = $total_kredit;
                }

                $data[] = [
                    'Tanggal' => $item->jurnal_header->tanggal,
                    'Nama Akun' => $akun->nama,
                    'Kode' => $akun->kode,
                    'Debit' => $item->nominal_debit,
                    'Kredit' => $item->nominal_kredit,
                    'Saldo Debit' => $saldo_debit,
                    'Saldo Kredit' => $saldo_kredit,
                    'Normal Post' => $akun->normal_post,
                ];
            }
        }

        return collect($data);
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:H1')->setCellValue('A1','BUKU BESAR');
                $sheet->mergeCells('A2:H2')->setCellValue('A2','Periode: '.$this->startDate.' s/d '.$this->endDate);

                $headers = ['Tanggal','Nama Akun','Kode','Debit','Kredit','Saldo Debit','Saldo Kredit','Normal Post'];
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
                $sheet->getStyle('D4:G'.$lastRow)->getNumberFormat()->setFormatCode('#,##0');
            }
        ];
    }
}
