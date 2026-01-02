<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Paket;
use App\Models\Product;
use App\Models\JurnalDetail;
use Illuminate\Http\Request;
use App\Exports\NeracaExport;
use App\Exports\ArusKasExport;
use App\Models\TransaksiMasuk;
use App\Exports\LabaRugiExport;
use App\Models\TransaksiKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BukuBesarExport;
use App\Models\LaporanTransaksi;
use App\Exports\JurnalUmumExport;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanStockExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerubahanModalExport;
use App\Exports\TransaksiMasukExport;
use App\Exports\TransaksiKeluarExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporan = $request->laporan;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $datas = null;

        if ($laporan) {
            switch ($laporan) {
                case 'neraca':
                    $datas = $this->buildNeraca($start_date, $end_date);
                    break;
                case 'laba_rugi':
                    $datas = $this->buildLabaRugi($start_date, $end_date);
                    break;
                case 'arus_kas':
                    $datas = $this->buildArusKas($start_date, $end_date);
                    break;
                case 'perubahan_modal':
                    $datas = $this->buildPerubahanModal($start_date, $end_date);
                    break;
                case 'jurnal_umum':
                    $datas = $this->buildJurnalUmum($start_date, $end_date);
                    break;
                case 'buku_besar':
                    $datas = $this->buildBukuBesar($start_date, $end_date);
                    break;
                case 'laporan_penjualan':
                    $datas = $this->buildTransaksiMasuk($start_date, $end_date);
                    break;
                case 'laporan_pembelian':
                    $datas = $this->buildTransaksiKeluar($start_date, $end_date);
                    break;
                case 'laporan_stok':
                    $datas = $this->buildLaporanStock($start_date, $end_date);
                    break;
            }
        }

        return view('laporans.index', compact('datas', 'laporan'));
    }


    public function buildNeraca($start_date, $end_date)
    {

        $asetLancar  = $this->getDataAsetLancar($start_date, $end_date);
        $asetTetap   = $this->getDataAsetTetap($start_date, $end_date);
        $kewajiban   = $this->getDataKewajiban($start_date, $end_date);
        $ekuitas     = $this->getDataEkuitas($start_date, $end_date);

        return compact(
            'asetLancar',
            'asetTetap',
            'kewajiban',
            'ekuitas',
        );
    }
    public function buildLabaRugi($start_date, $end_date)
    {
        $pendapatan  = $this->getDataPendapatan($start_date, $end_date);
        $beban  = $this->getDataBeban($start_date, $end_date);

        return compact(
            'pendapatan',
            'beban',
        );
    }

    public function buildArusKas($start_date, $end_date){
        $arusKas = $this->getDataArusKas($start_date, $end_date);

        return compact(
            'arusKas',
        );
    }
    public function buildTransaksiMasuk($start_date, $end_date){
        $transaksiMasuk = $this->getDataTransaksiMasuk($start_date, $end_date);

        return compact(
            'transaksiMasuk',
        );
    }
    public function buildTransaksiKeluar($start_date, $end_date){
        $transaksiKeluar = $this->getDataTransaksiKeluar($start_date, $end_date);

        return compact(
            'transaksiKeluar',
        );
    }
    public function buildJurnalUmum($start_date, $end_date){
        $jurnalUmum = $this->getDataJurnalUmum($start_date, $end_date);
        $route_excel = 'laporans.export.excel.jurnal_umum';

        return compact(
            'jurnalUmum',
            'route_excel',
        );
    }
    public function buildBukuBesar($start_date, $end_date){
        $bukuBesar = $this->getDataBukuBesar($start_date, $end_date);

        return compact(
            'bukuBesar',
        );
    }

    public function buildPerubahanModal($start_date, $end_date){
        $perubahanModal = $this->getDataPerubahanModal($start_date, $end_date);

        return compact(
            'perubahanModal',
        );
    }

    public function buildLaporanStock($start_date, $end_date){
        $laporanStock = $this->getDataLaporanStock($start_date, $end_date);

        return compact(
            'laporanStock',
        );
    }

    private function getDataJurnalUmum($start_date, $end_date){
        $basequery = JurnalDetail::with('akun', 'jurnal_header')
        ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
        ->join('akuns', 'akuns.id', 'jurnal_details.akun_id');

        if($start_date && $end_date){
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);
        }

        $datas = $basequery->select('jurnal_details.*',)
        ->orderBy('jurnal_headers.tanggal', 'asc')
        ->get();

        $total_debit = $datas->sum('nominal_debit');
        $total_kredit = $datas->sum('nominal_kredit');

        return [
            "data" => $datas,
            "total_debit" => $total_debit,
            "total_kredit" => $total_kredit
        ];
    }

  public function getDataArusKas($start_date, $end_date)
    {
        // 1️⃣ Ambil akun kas
        $akun_kas = Akun::where('nama', 'KAS')->first();

        // 2️⃣ Base jurnal kas + akun lawan
        $baseKas = DB::table('jurnal_details as jd_kas')
            ->join('jurnal_headers as jh', 'jh.id', '=', 'jd_kas.jurnal_header_id')
            ->join('jurnal_details as jd_lawan', function ($join) {
                $join->on('jd_lawan.jurnal_header_id', '=', 'jd_kas.jurnal_header_id')
                    ->whereColumn('jd_lawan.id', '!=', 'jd_kas.id');
            })
            ->join('akuns as akun_lawan', 'akun_lawan.id', '=', 'jd_lawan.akun_id')
            ->where('jd_kas.akun_id', $akun_kas->id)
            ->whereNull('jd_kas.deleted_at')
            ->whereNull('jd_lawan.deleted_at')
            ->whereNull('jh.deleted_at');

        // 3️⃣ Saldo awal kas
        $saldo_awal = Akun::where('nama', 'KAS')->value('saldo_awal');

        // 4️⃣ Filter periode
        if ($start_date && $end_date) {
            $baseKas->whereBetween('jh.tanggal', [$start_date, $end_date]);
        }

        // 5️⃣ Operasional
        $kas_operasional = (clone $baseKas)
            ->where('akun_lawan.aktivitas_kas', 'Operasional')
            ->select(
                'akun_lawan.aktivitas_kas',
                'akun_lawan.nama as akun',
                DB::raw('SUM(jd_kas.nominal_debit - jd_kas.nominal_kredit) as total')
            )
            ->groupBy('akun_lawan.aktivitas_kas', 'akun_lawan.nama')
            ->get();

        // 6️⃣ Investasi
        $kas_investasi = (clone $baseKas)
            ->where('akun_lawan.aktivitas_kas', 'Investasi')
            ->select(
                'akun_lawan.aktivitas_kas',
                'akun_lawan.nama as akun',
                DB::raw('SUM(jd_kas.nominal_debit - jd_kas.nominal_kredit) as total')
            )
            ->groupBy('akun_lawan.aktivitas_kas', 'akun_lawan.nama')
            ->get();
                                                                                    
        // 7️⃣ Pendanaan
        $kas_pendanaan = (clone $baseKas)
            ->where('akun_lawan.aktivitas_kas', 'Pendanaan')
            ->select(
                'akun_lawan.aktivitas_kas',
                'akun_lawan.nama as akun',
                DB::raw('SUM(jd_kas.nominal_debit - jd_kas.nominal_kredit) as total')
            )
            ->groupBy('akun_lawan.aktivitas_kas', 'akun_lawan.nama')
            ->get();

        // 8️⃣ Total & saldo akhir
        $total_kas_operasional = $kas_operasional->sum('total');
        $total_kas_investasi   = $kas_investasi->sum('total');
        $total_kas_pendanaan   = $kas_pendanaan->sum('total');

        $pergerakan_bersih =
            $total_kas_operasional +
            $total_kas_investasi +
            $total_kas_pendanaan;

        $saldo_akhir = $saldo_awal + $pergerakan_bersih;

        // 9️⃣ Return (tetap)
        return [
            "akun_kas" => $akun_kas,
            "saldo_awal" => $saldo_awal,
            "kas_operasional" => $kas_operasional,
            "kas_investasi"   => $kas_investasi,
            "kas_pendanaan"   => $kas_pendanaan,
            "total_kas_operasional" => $total_kas_operasional,
            "total_kas_investasi"   => $total_kas_investasi,
            "total_kas_pendanaan"   => $total_kas_pendanaan,
            "pergerakan_bersih" => $pergerakan_bersih,
            "saldo_akhir"       => $saldo_akhir,
        ];
    }

    private function getDataPerubahanModal($start_date, $end_date)
{
    /**
     * BASE QUERY
     */
    $basequery = DB::table('jurnal_details')
        ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
        ->join('akuns', 'akuns.id', '=', 'jurnal_details.akun_id')
        ->join('kategori_akuns', 'kategori_akuns.id', '=', 'akuns.kategori_akun_id')
        ->whereNull('jurnal_details.deleted_at')
        ->whereNull('jurnal_headers.deleted_at');

    /**
     * ================================
     * 1️⃣ MODAL AWAL
     * Saldo akun modal sebelum periode
     * Normal post: KREDIT
     * ================================
     */
    $modal_awal = (clone $basequery)
        ->where('akuns.nama', 'MODAL')
        ->where('jurnal_headers.tanggal', '<=', $start_date)
        ->select('akuns.saldo_awal')
        ->value('saldo_awal');
        // ->sum(DB::raw('jurnal_details.nominal_kredit - jurnal_details.nominal_debit'));

    /**
     * ================================
     * 2️⃣ PENAMBAHAN MODAL
     * Transaksi modal dalam periode
     * ================================
     */
    $penambahan_modal = (clone $basequery)
        ->where('akuns.nama', 'MODAL')
        ->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date])
        ->sum(DB::raw('jurnal_details.nominal_kredit'));

    /**
     * ================================
     * 3️⃣ PRIVE
     * Mengurangi modal
     * Normal post: DEBIT
     * ================================
     */
    $prive = (clone $basequery)
        ->where('akuns.nama', 'PRIVE')
        ->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date])
        ->sum(DB::raw('jurnal_details.nominal_debit'));

    // $laba_bersih = (clone $basequery)
    //     ->whereIn('akuns.kelompok_id', [4, 5]) // Pendapatan & Beban
    //     ->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date])
    //     ->sum(DB::raw('jurnal_details.nominal_kredit - jurnal_details.nominal_debit'));

    $pendapatan = $this->getDataPendapatan($start_date, $end_date);
    $beban = $this->getDataBeban($start_date, $end_date);
    $laba_bersih = $pendapatan['total'] - ($beban['total'] + $beban['hpp']);

    $modal_akhir = $modal_awal + $penambahan_modal + $laba_bersih - $prive;

    return [
        'modal_awal'        => $modal_awal,
        'penambahan_modal' => $penambahan_modal,
        'laba_bersih'      => $laba_bersih,
        'prive'            => $prive,
        'modal_akhir'      => $modal_akhir,
    ];
}

    private function getDataBukuBesar($start_date, $end_date){
        $data = [];
        $basequery = JurnalDetail::with('akun', 'jurnal_header')
        ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
        ->join('akuns', 'akuns.id', 'jurnal_details.akun_id');

        if($start_date && $end_date){
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);
        }

        $entries = $basequery->orderBy('jurnal_headers.tanggal', 'asc')
        ->get()
        ->groupBy('akun_id');

        foreach($entries as $entry){
            $akun = $entry->first()->akun;
            $total_debit = 0;
            $total_kredit = 0;
            $list_transaksi = [];

            foreach($entry as $item){
                $data_entry = [];
                $data_entry['tanggal'] = $item->jurnal_header->tanggal;
                $data_entry['nominal_debit'] = $item->nominal_debit;
                $data_entry['nominal_kredit'] = $item->nominal_kredit;
                $data_entry['kode'] = $item->akun->kode;
                $data_entry['normal_post'] = $item->akun->normal_post;
                $data_entry['saldo_debit'] = '';
                $data_entry['saldo_kredit'] = '';

                if($akun->normal_post == 'Debit'){
                    $currentSaldo = $total_debit + ($item->nominal_debit - $item->nominal_kredit);
                    $data_entry['saldo_debit'] = $currentSaldo;
                    $total_debit = $currentSaldo;
                }elseif($akun->normal_post == 'Kredit'){
                    $currentSaldo = $total_kredit + ($item->nominal_kredit - $item->nominal_debit);
                    $data_entry['saldo_kredit'] = $currentSaldo;
                    $total_kredit = $currentSaldo;
                }

                $list_transaksi[] = $data_entry;
            }

            $data[$akun->nama] = [
            "transaksi" => $list_transaksi,
            "normal_post" => $akun->normal_post,
            ]; 
        }

        return $data;
    }

private function getDataLaporanStock($start_date, $end_date)
{
    $data = [];

    $products = Product::with('pakets')->get();

    foreach ($products as $product) {

        // ===============================
        // 1️⃣ HITUNG STOK AWAL
        // ===============================
        // STOK MASUK = PEMBELIAN
        $stokMasukSebelum = DB::table('transaksi_keluars')
            ->where('product_id', $product->id)
            ->where('tanggal', '<', $start_date)
            ->sum('qty');

        // STOK KELUAR = PENJUALAN BARANG
        $stokKeluarBarangSebelum = DB::table('transaksi_masuks')
            ->where('product_id', $product->id)
            ->where('tanggal', '<', $start_date)
            ->sum('qty');

        // STOK KELUAR = PENJUALAN PAKET
        $stokKeluarPaketSebelum = DB::table('pakets')
            ->join('transaksi_masuks', 'transaksi_masuks.paket_id', '=', 'pakets.id')
            ->where('pakets.product_id', $product->id)
            ->where('transaksi_masuks.tanggal', '<', $start_date)
            ->count();

        $stokAwal = $stokMasukSebelum
            - ($stokKeluarBarangSebelum + $stokKeluarPaketSebelum);

        // ===============================
        // 2️⃣ TRANSAKSI DALAM RANGE
        // ===============================

        // STOK MASUK → PEMBELIAN
        $masuk = DB::table('transaksi_keluars')
            ->where('product_id', $product->id)
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->select(
                'tanggal',
                'qty',
                DB::raw("'masuk' as tipe")
            );

        // STOK KELUAR → PENJUALAN BARANG
        $keluarBarang = DB::table('transaksi_masuks')
            ->where('product_id', $product->id)
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->select(
                'tanggal',
                'qty',
                DB::raw("'keluar' as tipe")
            );

        // STOK KELUAR → PENJUALAN PAKET
        $keluarPaket = DB::table('pakets')
            ->join('transaksi_masuks', 'transaksi_masuks.paket_id', '=', 'pakets.id')
            ->where('pakets.product_id', $product->id)
            ->whereBetween('transaksi_masuks.tanggal', [$start_date, $end_date])
            ->select(
                'transaksi_masuks.tanggal as tanggal', // ✅ sumber tanggal BENAR
                DB::raw(' 1 as qty'),          // atau 1
                DB::raw("'keluar' as tipe")
            );

        $transaksi = $masuk
            ->unionAll($keluarBarang)
            ->unionAll($keluarPaket)
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        // ===============================
        // 3️⃣ KARTU STOK
        // ===============================
        $stok = $stokAwal;
        $kartuStok = [];

        foreach ($transaksi as $tanggal => $items) {
            $totalMasuk = $items->where('tipe', 'masuk')->sum('qty');
            $totalKeluar = $items->where('tipe', 'keluar')->sum('qty');

            $stokAkhir = $stok + $totalMasuk - $totalKeluar;

            $kartuStok[] = [
                'tanggal' => $tanggal,
                'stok_awal' => $stok,
                'masuk' => $totalMasuk,
                'keluar' => $totalKeluar,
                'stok_akhir' => $stokAkhir,
            ];

            $stok = $stokAkhir;
        }

        // ===============================
        // 4️⃣ SIMPAN PER PRODUK
        // ===============================
        if (!empty($kartuStok)) {
            $data[$product->nama] = [
                'kode' => $product->kode,
                'kartu_stok' => $kartuStok
            ];
        }
    }

    return $data;
}


    private function getDataTransaksiMasuk($start_date, $end_date){

        $basequery = TransaksiMasuk::with('product', 'jasa', 'paket');

        if ($start_date && $end_date) {
            $basequery->whereBetween('tanggal', [$start_date, $end_date]);        
        }

        $data = $basequery->select('transaksi_masuks.*')
        ->orderBy('tanggal', 'asc')
        ->get();
        $total = $data->sum('harga_total');

        return (
            [
            "data" => $data,
            "total" => $total
            ]
        );
    }
    private function getDataTransaksiKeluar($start_date, $end_date){

        $basequery = TransaksiKeluar::with('product', 'supplier');

        if ($start_date && $end_date) {
            $basequery->whereBetween('tanggal', [$start_date, $end_date]);        
        }

        $data = $basequery->select('transaksi_keluars.*')
        ->orderBy('tanggal', 'asc')
        ->get();
        $total = $data->sum('harga_total');


        return (
            [
            "data" => $data,
            "total" => $total
            ]
        );
    }

    private function getDataAsetLancar($start_date, $end_date){
        $data = [];
        $total = 0;
        
        $basequery = JurnalDetail::with(['akun.kategori_akun'])
            ->join('akuns', 'akuns.id', 'jurnal_details.akun_id')
            ->join('kategori_akuns', 'kategori_akuns.id', 'akuns.kategori_akun_id')
            ->join('jurnal_headers', 'jurnal_headers.id', 'jurnal_details.jurnal_header_id');

        if ($start_date && $end_date) {
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);        
        }

        $datas = $basequery->select(
                'jurnal_details.*',
                'akuns.nama as akun_nama',
                'kategori_akuns.nama as kategori_nama', 
                'akuns.id as akun_id',
            )
            ->where('kategori_akuns.nama', 'ASET LANCAR')
            ->orderBy('jurnal_headers.tanggal', 'asc')
            ->get()
            ->groupBy('akun_id');

        
        foreach ($datas as $entry) {
            $akun = $entry->first()->akun;
            $total_debit = $entry->sum('nominal_debit');
            $total_kredit = $entry->sum('nominal_kredit');
            $total_akun = abs($total_debit - $total_kredit);
            $tanggal_list = $entry->pluck('jurnal_header.tanggal')->toArray();
            $data[$akun->nama] = [
                "kode" => $akun->kode,
                "nama" => $akun->nama,
                "total" => $total_akun,
                "tanggal" => $tanggal_list,
            ];
            $total += $total_akun;
        }

        return [
            "data" => $data,
            "total" => $total,
        ];
    }

    private function getDataAsetTetap($start_date, $end_date){
        $data = [];
        $total = 0;
        
        $basequery = JurnalDetail::with(['akun.kategori_akun'])
            ->join('akuns', 'akuns.id', 'jurnal_details.akun_id')
            ->join('kategori_akuns', 'kategori_akuns.id', 'akuns.kategori_akun_id')
            ->join('jurnal_headers', 'jurnal_headers.id', 'jurnal_details.jurnal_header_id');

        if ($start_date && $end_date) {
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);        
        }

        $datas = $basequery->select(
                'jurnal_details.*',
                'akuns.nama as akun_nama',
                'kategori_akuns.nama as kategori_nama', 
                'akuns.id as akun_id',
            )
            ->where('kategori_akuns.nama', 'ASET TETAP')
            ->orderBy('jurnal_headers.tanggal', 'asc')
            ->get()
            ->groupBy('akun_id');

        
        foreach ($datas as $entry) {
            $akun = $entry->first()->akun;
            $total_debit = $entry->sum('nominal_debit');
            $total_kredit = $entry->sum('nominal_kredit');
            $total_akun = abs($total_debit - $total_kredit);
            $tanggal_list = $entry->pluck('jurnal_header.tanggal')->toArray();
            $data[$akun->nama] = [
                "kode" => $akun->kode,
                "nama" => $akun->nama,
                "total" => $total_akun,
                "tanggal" => $tanggal_list,
            ];
            $total += $total_akun;
        }

        return [
            "data" => $data,
            "total" => $total,
        ];
    }
    private function getDataKewajiban($start_date, $end_date){
        $data = [];
        $total = 0;
        
        $basequery = JurnalDetail::with(['akun.kategori_akun'])
            ->join('akuns', 'akuns.id', 'jurnal_details.akun_id')
            ->join('kategori_akuns', 'kategori_akuns.id', 'akuns.kategori_akun_id')
            ->join('jurnal_headers', 'jurnal_headers.id', 'jurnal_details.jurnal_header_id')
            ->join('kelompoks', 'kelompoks.id', 'akuns.kelompok_id');

        if ($start_date && $end_date) {
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);        
        }

        $datas = $basequery->select(
                'jurnal_details.*',
                'akuns.nama as akun_nama',
                'kategori_akuns.nama as kategori_nama', 
                'kelompoks.name as kelompok_name',
                'akuns.id as akun_id',
            )
            ->where('kelompoks.id', 2)
            ->orderBy('jurnal_headers.tanggal', 'asc')
            ->get()
            ->groupBy('akun_id');

        
        foreach ($datas as $entry) {
            $akun = $entry->first()->akun;
            $total_debit = $entry->sum('nominal_debit');
            $total_kredit = $entry->sum('nominal_kredit');
            $total_akun = abs($total_debit - $total_kredit);
            $tanggal_list = $entry->pluck('jurnal_header.tanggal')->toArray();
            $data[$akun->nama] = [
                "kode" => $akun->kode,
                "nama" => $akun->nama,
                "total" => $total_akun,
                "tanggal" => $tanggal_list,
            ];
            $total += $total_akun;
        }

        return [
            "data" => $data,
            "total" => $total,
        ];
    }

   private function getDataEkuitas($start_date, $end_date)
    {
        $data = [];
        $total = 0;

        $basequery = JurnalDetail::with(['akun', 'jurnal_header'])
            ->join('akuns', 'akuns.id', 'jurnal_details.akun_id')
            ->join('kategori_akuns', 'kategori_akuns.id', 'akuns.kategori_akun_id')
            ->join('jurnal_headers', 'jurnal_headers.id', 'jurnal_details.jurnal_header_id')
            ->join('kelompoks', 'kelompoks.id', 'akuns.kelompok_id')
            ->whereNull('jurnal_details.deleted_at')
            ->whereNull('jurnal_headers.deleted_at');

        if ($start_date && $end_date) {
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);
        }

        $datas = $basequery
            ->where('kelompoks.id', 3) // EKUITAS
            ->select(
                'jurnal_details.*',
                'akuns.id as akun_id'
            )
            ->orderBy('jurnal_headers.tanggal', 'asc')
            ->get()
            ->groupBy('akun_id');

        foreach ($datas as $entry) {
            $akun = $entry->first()->akun;

            $total_debit  = $entry->sum('nominal_debit');
            $total_kredit = $entry->sum('nominal_kredit');

            // EKUITAS → saldo normal KREDIT
            $saldo = $total_kredit - $total_debit;

            if ($saldo == 0) continue;

            $tanggal_list = $entry
                ->pluck('jurnal_header.tanggal')
                ->unique()
                ->values()
                ->toArray();

            $data[$akun->nama] = [
                "kode" => $akun->kode,
                "nama" => $akun->nama,
                "total" => abs($saldo), // neraca tidak minus
                "tanggal" => $tanggal_list,
            ];

            $total += $saldo;
        }

        $pendapatan = $this->getDataPendapatan($start_date, $end_date);
        $beban = $this->getDataBeban($start_date, $end_date);
        $laba_ditahan = $pendapatan['total'] - ($beban['total'] + $beban['hpp']);

        return [
            "data" => $data,
            "total" => abs($total),
            "laba_ditahan" => $laba_ditahan,
        ];
    }


    private function getDataPendapatan($start_date, $end_date){
        $data = [];
        $total = 0;
        
        $basequery = JurnalDetail::with(['akun.kategori_akun'])
            ->join('akuns', 'akuns.id', 'jurnal_details.akun_id')
            ->join('kategori_akuns', 'kategori_akuns.id', 'akuns.kategori_akun_id')
            ->join('jurnal_headers', 'jurnal_headers.id', 'jurnal_details.jurnal_header_id')
            ->join('kelompoks', 'kelompoks.id', 'akuns.kelompok_id');

        if ($start_date && $end_date) {
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);        
        }

        $datas = $basequery->select(
                'jurnal_details.*',
                'akuns.nama as akun_nama',
                'kategori_akuns.nama as kategori_nama', 
                'kelompoks.name as kelompok_name',
                'akuns.id as akun_id',
            )
            ->where('kelompoks.id', 4)
            ->where('akuns.nama', '!=','HPP')
            ->orderBy('jurnal_headers.tanggal', 'asc')
            ->get()
            ->groupBy('akun_id');


        foreach ($datas as $entry) {
            $akun = $entry->first()->akun;
            $total_debit = $entry->sum('nominal_debit');
            $total_kredit = $entry->sum('nominal_kredit');
            $total_akun = abs($total_debit - $total_kredit);
            $tanggal_list = $entry->pluck('jurnal_header.tanggal')->toArray();
            $data[$akun->nama] = [
                "kode" => $akun->kode,
                "nama" => $akun->nama,
                "total" => $total_akun,
                "tanggal" => $tanggal_list,
            ];
            $total += $total_akun;
        }

        return [
            "data" => $data,
            "total" => $total,
        ];
    }
    private function getDataBeban($start_date, $end_date){
        $data = [];
        $total = 0;
        
        $basequery = JurnalDetail::with(['akun.kategori_akun'])
            ->join('akuns', 'akuns.id', 'jurnal_details.akun_id')
            ->join('kategori_akuns', 'kategori_akuns.id', 'akuns.kategori_akun_id')
            ->join('jurnal_headers', 'jurnal_headers.id', 'jurnal_details.jurnal_header_id')
            ->join('kelompoks', 'kelompoks.id', 'akuns.kelompok_id');

        if ($start_date && $end_date) {
            $basequery->whereBetween('jurnal_headers.tanggal', [$start_date, $end_date]);        
        }

        $hpp = (clone $basequery)
        ->where('akuns.nama', 'HPP')
        ->select( DB::raw('SUM(nominal_debit - nominal_kredit) as total'))
        ->value('total') ?? 0;

        $datas = $basequery->select(
                'jurnal_details.*',
                'akuns.nama as akun_nama',
                'kategori_akuns.nama as kategori_nama', 
                'kelompoks.name as kelompok_name',
                'akuns.id as akun_id',
            )
            ->where('kelompoks.id', 5)
            ->where('akuns.nama', '!=','HPP')
            ->orderBy('jurnal_headers.tanggal', 'asc')
            ->get()
            ->groupBy('akun_id');


        foreach ($datas as $entry) {
            $akun = $entry->first()->akun;
            $total_debit = $entry->sum('nominal_debit');
            $total_kredit = $entry->sum('nominal_kredit');
            $total_akun = abs($total_debit - $total_kredit);
            $tanggal_list = $entry->pluck('jurnal_header.tanggal')->toArray();
            $data[$akun->nama] = [
                "kode" => $akun->kode,
                "nama" => $akun->nama,
                "total" => $total_akun,
                "tanggal" => $tanggal_list,
            ];
            $total += $total_akun;
        }

        return [
            "data" => $data,
            "total" => $total,
            "hpp" => $hpp,
        ];
    }


    public function arusKas_index(){

        $datas = JurnalDetail::with('akun')
        ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
        ->join('akuns', 'akuns.id', '=', 'jurnal_details.akun_id')
        ->select('jurnal_details.*', 'akuns.nama', 'akuns.kelompok_id', 'akuns.kategori_akun_id');
        
        return $datas;
    }

    public function exportPDFNeraca(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $asetLancar  = $this->getDataAsetLancar($start_date, $end_date);
        $asetTetap   = $this->getDataAsetTetap($start_date, $end_date);
        $kewajiban   = $this->getDataKewajiban($start_date, $end_date);
        $ekuitas     = $this->getDataEkuitas($start_date, $end_date);  
        
        $data = compact('asetLancar', 'asetTetap', 'kewajiban', 'ekuitas', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.neraca', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('neraca.pdf');
    }

    public function exportPDFLabaRugi(Request $request){
        
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $pendapatan  = $this->getDataPendapatan($start_date, $end_date);
        $beban  = $this->getDataBeban($start_date, $end_date);

        $data = compact('pendapatan', 'beban', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.laba_rugi', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('laba_rugi.pdf');
    }

    public function exportPDFArusKas(Request $request){
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $arusKas = $this->getDataArusKas($start_date, $end_date);

        $data = compact('arusKas', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.arus_kas', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('arus_kas.pdf');
    }  
    
    public function exportPDFBukuBesar(Request $request){

        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $bukuBesar = $this->getDataBukuBesar($start_date, $end_date);

        $data = compact('bukuBesar', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.buku_besar', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('buku_besar.pdf');

    }

    public function exportPDFJurnalUmum(Request $request){

        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $jurnalUmum = $this->getDataJurnalUmum($start_date, $end_date);

        $data = compact('jurnalUmum', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.jurnal_umum', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('jurnal_umum.pdf');
    }

    public function exportPDFPerubahanModal(Request $request){
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $perubahanModal = $this->getDataPerubahanModal($start_date, $end_date);

        $data = compact('perubahanModal', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.perubahan_modal', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('perubahan_modal.pdf');
    }

    public function exportPDFTransaksiMasuk(Request $request){
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $transaksiMasuk = $this->getDataTransaksiMasuk($start_date, $end_date);

        $data = compact('transaksiMasuk', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.transaksi_masuk', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('transaksi_masuk.pdf');
    }
    public function exportPDFTransaksiKeluar(Request $request){
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $transaksiKeluar = $this->getDataTransaksiKeluar($start_date, $end_date);

        $data = compact('transaksiKeluar', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.transaksi_keluar', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('transaksi_keluar.pdf');
    }

    public function exportPDFStock(Request $request){
        
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $laporanStock = $this->getDataLaporanStock($start_date, $end_date);

        $data = compact('laporanStock', 'start_date', 'end_date');

        $pdf = Pdf::loadView('pdf.stock', $data)
        ->setPaper('A4', 'portrait');

        return $pdf->download('stock.pdf');
    }

    public function exportExcelJurnalUmum(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new JurnalUmumExport($start_date, $end_date), 'jurnal-umum.xlsx');
    }
    public function exportExcelNeraca(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new NeracaExport($start_date, $end_date), 'neraca.xlsx');
    }
    public function exportExcelLabaRugi(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new LabaRugiExport($start_date, $end_date), 'laba_rugi.xlsx');
    }
    public function exportExcelArusKas(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new ArusKasExport($start_date, $end_date), 'arus_kas.xlsx');
    }
    public function exportExcelBukuBesar(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new BukuBesarExport($start_date, $end_date), 'buku_besar.xlsx');
    }
    public function exportExcelPerubahanModal(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new PerubahanModalExport($start_date, $end_date), 'perubahan_modal.xlsx');
    }
    public function exportExcelTransaksiMasuk(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new TransaksiMasukExport($start_date, $end_date), 'transaksi_masuk.xlsx');
    }
    public function exportExcelTransaksiKeluar(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new TransaksiKeluarExport($start_date, $end_date), 'transaksi_keluar.xlsx');
    }
    public function exportExcelLaporanStock(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        return Excel::download(new LaporanStockExport($start_date, $end_date), 'laporan_stock.xlsx');
    }

}
