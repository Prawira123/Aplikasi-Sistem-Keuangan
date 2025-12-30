<?php

namespace App\Exports;

use App\Models\JurnalDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class JurnalUmumExport implements FromCollection, WithEvents
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
        $basequery = JurnalDetail::with('akun','jurnal_header')
            ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
            ->join('akuns', 'akuns.id', '=', 'jurnal_details.akun_id');

        if($this->startDate && $this->endDate){
            $basequery->whereBetween('jurnal_headers.tanggal', [$this->startDate, $this->endDate]);
        }

        $datas = $basequery->select('jurnal_details.*')
            ->orderBy('jurnal_headers.tanggal', 'asc')
            ->get();

        return $datas->map(function($entry){
            return [
                $entry->jurnal_header->tanggal,
                $entry->akun->kode,
                $entry->akun->nama,
                $entry->nominal_debit,
                $entry->nominal_kredit,
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // ambil PhpSpreadsheet worksheet

                // Judul utama di baris 1
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'LAPORAN JURNAL UMUM');

                // Periode di baris 2
                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', 'Periode: ' . $this->startDate . ' s/d ' . $this->endDate);

                // Header kolom di baris 3
                $headers = ['Tanggal', 'Kode', 'Akun', 'Debit', 'Kredit'];
                $col = 'A';
                foreach($headers as $header){
                    $sheet->setCellValue($col.'3', $header);
                    $col++;
                }

                // Style judul & periode
                $sheet->getStyle('A1:E2')->getFont()->setBold(true);
                $sheet->getStyle('A1:E2')->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Style header kolom
                $sheet->getStyle('A3:E3')->getFont()->setBold(true);
                $sheet->getStyle('A3:E3')->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Data mulai dari baris 4
                $sheet->fromArray($this->collection()->toArray(), null, 'A4');
            },
        ];
    }
}
