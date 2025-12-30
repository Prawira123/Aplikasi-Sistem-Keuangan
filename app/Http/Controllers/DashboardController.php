<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use App\Models\User;
use App\Models\Paket;
use App\Models\Product;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\JurnalDetail;
use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $transaksi_masuks = TransaksiMasuk::sum('harga_total');
        $transaksi_keluars = TransaksiKeluar::sum('harga_total');
        $likuiditas = $this->getDataLikuiditas();
        $pembelian = $this->getDataPembelian();
        $user = Auth::user();
        $products = Product::count();
        $jasas = Jasa::count();
        $pakets = Paket::count();
        $pelanggans = $this->getDataPelanggan();
        return view('dashboard.index', compact('transaksi_masuks', 'transaksi_keluars', 'products', 'jasas', 'pakets', 'pelanggans', 'likuiditas', 'user', 'pembelian'));
    }



    public function penjualan_perbulan()
    {
        $data = TransaksiMasuk::selectRaw('
                YEAR(tanggal) as year,
                MONTH(tanggal) as month,
                SUM(harga_total) as total_pendapatan
            ')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $totals = [];

        foreach ($data as $item) {
            $labels[] = date('F Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            $totals[] = $item->total_pendapatan;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $totals
        ]);
    }

    public function penjualan_perbulan_detail()
    {
        $rows = TransaksiMasuk::selectRaw('
                YEAR(tanggal) as year,
                MONTH(tanggal) as month,
                tipe,
                SUM(harga_total) as total_pendapatan
            ')
            ->groupBy('year', 'month', 'tipe')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $labels = $rows
        ->map(fn ($r) => sprintf('%02d-%d', $r->month, $r->year))
        ->unique()
        ->values();

        $totals = [
            'Barang' => [],
            'Jasa' => [],
            'Paket' => []
        ];

        foreach($labels as $label){
            [$month, $year] = explode('-', $label);

            foreach(['Barang', 'Jasa', 'Paket'] as $tipe){
                $total = $rows
                ->where('month', $month)
                ->where('year', $year)
                ->where('tipe', $tipe)
                ->sum('total_pendapatan');

                $totals[$tipe][] = $total ?? 0;
            }
        }


        return response()->json([
            'labels' => $labels,
            'data' => $totals
        ]);
    }

    private function getDataLikuiditas(){
        $data = JurnalDetail::join('akuns', 'akuns.id', '=', 'jurnal_details.akun_id')
        ->where('akuns.kelompok_id', 1)
        ->selectRaw('SUM(nominal_debit - nominal_kredit) as total')
        ->get();

        $data = $data->sum('total') ?? 0;

        return $data;
    }

    private function getDataPembelian(){
        $data = TransaksiKeluar::with('product')
        ->orderBy('tanggal', 'desc')
        ->paginate(5);

        return $data;
    }

    private function getDataPelanggan(){
         return Pelanggan::withCount('transaksi')
        ->orderByDesc('transaksi_count')
        ->paginate(5);
    }

}
