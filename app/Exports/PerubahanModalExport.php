<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PerubahanModalExport implements FromCollection, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate){
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection(){
        // ===============================
        // BASE QUERY (SAMA DENGAN FUNCTION)
        // ===============================
        $basequery = DB::table('jurnal_details')
            ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
            ->join('akuns', 'akuns.id', 'jurnal_details.akun_id')
            ->join('kategori_akuns', 'kategori_akuns.id', 'akuns.kategori_akun_id')
            ->select(
                'jurnal_details.id',
                'jurnal_details.nominal_debit',
                'jurnal_details.nominal_kredit',
                'jurnal_headers.tanggal',
                'akuns.nama',
                'akuns.kelompok_id',
                'kategori_akuns.nama as kategori'
            );

        if($this->startDate && $this->endDate){
            $basequery->whereBetween('jurnal_headers.tanggal', [$this->startDate, $this->endDate]);
        }

        // ===============================
        // HITUNG PERUBAHAN MODAL
        // ===============================
        $modalAwal = (clone $basequery)
            ->whereIn('kategori_akuns.nama', ['modal', 'Modal'])
            ->first();

        $penambahanModal = (clone $basequery)
            ->whereIn('kategori_akuns.nama', ['modal', 'Modal'])
            ->where('jurnal_details.id', '!=', $modalAwal->id ?? 0)
            ->sum(DB::raw('jurnal_details.nominal_debit - jurnal_details.nominal_kredit'));

        $prive = (clone $basequery)
            ->whereIn('akuns.nama', ['prive', 'Prive'])
            ->sum(DB::raw('jurnal_details.nominal_debit - jurnal_details.nominal_kredit'));

        $labaBersih = (clone $basequery)
            ->whereIn('akuns.kelompok_id', [4,5])
            ->sum(DB::raw('jurnal_details.nominal_debit - jurnal_details.nominal_kredit'));

        $modalAwalValue   = ($modalAwal->nominal_debit ?? 0) - ($modalAwal->nominal_kredit ?? 0);
        $labaBersihValue  = $labaBersih ?? 0;

        $modalAkhir = abs($modalAwalValue)
            + abs($penambahanModal)
            + abs($labaBersihValue)
            - abs($prive);

        // ===============================
        // FORMAT DATA UNTUK EXCEL
        // ===============================
        return collect([
            ['Deskripsi' => 'Modal Awal',        'Jumlah' => abs($modalAwalValue)],
            ['Deskripsi' => 'Laba Bersih',       'Jumlah' => abs($labaBersihValue)],
            ['Deskripsi' => 'Penambahan Modal',  'Jumlah' => abs($penambahanModal)],
            ['Deskripsi' => 'Prive',             'Jumlah' => abs($prive)],
            ['Deskripsi' => 'Modal Akhir',       'Jumlah' => abs($modalAkhir)],
        ]);
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:B1')->setCellValue('A1', 'LAPORAN PERUBAHAN MODAL');
                $sheet->mergeCells('A2:B2')->setCellValue(
                    'A2',
                    'Periode: '.$this->startDate.' s/d '.$this->endDate
                );

                // Header kolom
                $sheet->setCellValue('A3', 'Deskripsi');
                $sheet->setCellValue('B3', 'Jumlah');

                // Styling header
                $sheet->getStyle('A1:B3')->getFont()->setBold(true);
                $sheet->getStyle('A1:B3')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Isi data
                $sheet->fromArray($this->collection()->toArray(), null, 'A4');

                $lastRow = 3 + $this->collection()->count();

                // Border
                $sheet->getStyle('A3:B'.$lastRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Format angka
                $sheet->getStyle('B4:B'.$lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
            }
        ];
    }
}
