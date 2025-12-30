<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Jasa;
use App\Models\Paket;
use App\Models\Product;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\JurnalDetail;
use App\Models\JurnalHeader;
use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use Illuminate\Validation\Rule;
use App\Models\LaporanTransaksi;

class TransaksiMasukController extends Controller
{
    public function index(Request $request){

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $query = TransaksiMasuk::with(['karyawan', 'product', 'jasa', 'paket']);

        if($bulan && $tahun){
            $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);
        }

        $transaksi_masuks = $query->orderBy('tanggal', 'asc')->get();

        return view('transaksi_masuks.index',compact('transaksi_masuks'));
    }

    public function show($id){
        $transaksi_masuk = TransaksiMasuk::with('karyawan', 'product', 'jasa', 'paket')->findOrFail($id);
        return view('transaksi_masuks.show', compact('transaksi_masuk'));
    }

    public function create(){
        $products = Product::select('id', 'nama', 'harga')->get();
        $jasas = Jasa::select('id', 'nama', 'harga')->get();
        $karyawans = Karyawan::select('id', 'fullname')->get();
        $akuns = Akun::select('id', 'nama')->get();
        $pelanggans = Pelanggan::select('id', 'nama')->get();
        $pakets = Paket::select('id', 'nama', 'harga')->get();

        return view('transaksi_masuks.create', compact( 'products', 'jasas', 'karyawans', 'akuns', 'pelanggans', 'pakets'));
    }

    public function store(Request $request, TransaksiMasuk $transaksi_masuk){
        $request->validate([
            'product_id' => $request->tipe == 'barang' ? 'required' : 'nullable',
            'karyawan_id' => 'required',
            'pelanggan_id' => 'required',
            'jasa_id' => $request->tipe == 'jasa' ? 'required' : 'nullable',
            'paket_id' => $request->tipe == 'Paket' ? 'required' : 'nullable',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'tipe' => 'required',
            'qty' => $request->tipe == 'barang' ? 'required|numeric' : 'nullable',
            'harga_satuan' => $request->tipe == 'barang' ? 'required|numeric' : 'nullable',
            'harga_total' => 'required|numeric',
            'akun_debit_id' => 'required',
            'akun_kredit_id' => 'required',
        ]);

        $latest = TransaksiMasuk::latest()->first();
        $nextId = $latest ? $latest->id + 1 : 1;

        $kode = 'TM'. str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $transaksi_masuk = TransaksiMasuk::create([
            'product_id' => $request->product_id,
            'karyawan_id' => $request->karyawan_id,
            'pelanggan_id' => $request->pelanggan_id,
            'jasa_id' => $request->jasa_id,
            'paket_id' => $request->paket_id,
            'kode' => $kode,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'qty' => $request->qty,
            'harga_satuan' => $request->harga_satuan,
            'harga_total' => $request->harga_total,  
            'akun_debit_id' => $request->akun_debit_id,
            'akun_kredit_id' => $request->akun_kredit_id, 
        ]);

        if($request->tipe == 'Barang' || $request->tipe == 'Paket'){
            $this->penguranganStock($request->product_id, $request->qty, $request->tipe, $request->paket_id);
        }

        if($request->tipe == 'Barang'){
        $jurnal = $this->jurnal_entry($request->tanggal, "Transaksi Masuk ID : $transaksi_masuk->id",  [
                [
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->harga_total,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $request->akun_kredit_id,
                'nominal_kredit' => $request->harga_total,
                'nominal_debit' => 0,
                ],
            ],
            [
                [
                'akun_id' => $this->HPP()->id ,
                'nominal_debit' => $transaksi_masuk->product->harga_beli * $request->qty,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $transaksi_masuk->product->akun_persediaan,
                'nominal_kredit' => $transaksi_masuk->product->harga_beli * $request->qty,
                'nominal_debit' => 0,
                ],
            ]);
        }elseif( $request->tipe == 'Paket') {
            $jurnal = $this->jurnal_entry($request->tanggal, "Transaksi Masuk ID : $transaksi_masuk->id",  [
                [
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->harga_total,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $request->akun_kredit_id,
                'nominal_kredit' => $request->harga_total,
                'nominal_debit' => 0,
                ],
            ],
            [
                [
                'akun_id' => $this->HPP()->id ,
                'nominal_debit' => $transaksi_masuk->paket->product->harga_beli,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $transaksi_masuk->paket->product->akun_persediaan,
                'nominal_kredit' => $transaksi_masuk->paket->product->harga_beli,
                'nominal_debit' => 0,
                ],
            ]);
        }else{
            $jurnal = $this->jurnal_entry($request->tanggal, "Transaksi Masuk ID : $transaksi_masuk->id",  [
                [
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->harga_total,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $request->akun_kredit_id,
                'nominal_kredit' => $request->harga_total,
                'nominal_debit' => 0,
                ],
            ], []);
        }

        $laporan = $this->laporan_transaksi_entry_create($request->product_id, $request->tanggal, $request->jasa_id, $request->paket_id);

        $transaksi_masuk->update([
            'jurnal_id' => $jurnal->id,
            'laporan_transaksi_id' => $laporan->id
        ]);

        if ($request->action === 'save_next') {
        return redirect()
            ->route('transaksi_masuks.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('transaksi_masuks.index')->with('success', 'transaksi_masuks created successfully');
    }  

    public function edit($id){

        $transaksi_masuk = TransaksiMasuk::findOrFail($id);
        $products = Product::select('id', 'nama', 'harga')->get();
        $jasas = Jasa::select('id', 'nama', 'harga')->get();
        $karyawans = Karyawan::select('id', 'fullname')->get();
        $akuns = Akun::select('id', 'nama')->get();
        $pelanggans = Pelanggan::select('id', 'nama')->get();
        $pakets = Paket::with('product', 'jasa')->get();

        return view('transaksi_masuks.edit', compact('transaksi_masuk', 'products', 'jasas', 'karyawans', 'akuns', 'pelanggans', 'pakets'));
    }   

    public function update(Request $request, $id){
        
        $transaksi_masuk = TransaksiMasuk::findOrFail($id);
        $request->validate([
            'product_id' => $request->tipe == 'barang' ? 'required' : 'nullable',
            'karyawan_id' => 'required',
            'pelanggan_id' => 'required',
            'jasa_id' => $request->tipe == 'jasa' ? 'required' : 'nullable',
            'paket_id' => $request->tipe == 'Paket' ? 'required' : 'nullable',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'tipe' => 'required',
            'qty' => $request->tipe == 'barang' ? 'required|numeric' : 'nullable',
            'harga_satuan' => $request->tipe == 'barang' ? 'required|numeric' : 'nullable',
            'akun_debit_id' => 'required',
            'akun_kredit_id' => 'required',
            'harga_total' => 'required|numeric',  
        ]);

        if($request->tipe == 'Barang' || $request->tipe == 'Paket'){
            $this->restoreStock($transaksi_masuk);
        }

        if($request->tipe == 'Barang'){
            $harga_total = $request->qty * $request->harga_satuan;
        } else {
            $harga_total = $request->harga_total;
        }

        $transaksi_masuk->update([
            'product_id' => $request->product_id,
            'karyawan_id' => $request->karyawan_id,
            'pelanggan_id' => $request->pelanggan_id,
            'jasa_id' => $request->jasa_id,
            'paket_id' => $request->paket_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'qty' => $request->qty,
            'harga_satuan' => $request->harga_satuan,
            'harga_total' => $harga_total,  
            'akun_debit_id' => $request->akun_debit_id,
            'akun_kredit_id' => $request->akun_kredit_id,
        ]);

        if($request->tipe == 'Barang' || $request->tipe == 'Paket'){
            $this->penguranganStock($request->product_id, $request->qty, $request->tipe, $request->paket_id);
        }

        if($request->tipe == 'Barang'){
            $this->jurnal_entry_edit($transaksi_masuk->jurnal_id, $request->tanggal, "Transaksi Masuk ID : $transaksi_masuk->id",  [
                [
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->harga_total,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $request->akun_kredit_id,
                'nominal_kredit' => $request->harga_total,
                'nominal_debit' => 0,
                ],
            ],
            [
                [
                'akun_id' => $this->HPP()->id ,
                'nominal_debit' => $transaksi_masuk->product->harga_beli ,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $transaksi_masuk->product->akun_persediaan,
                'nominal_kredit' => $transaksi_masuk->product->harga_beli ,
                'nominal_debit' => 0,
                ],
            ]);
        }elseif( $request->tipe == 'Paket') {
            $this->jurnal_entry_edit($transaksi_masuk->jurnal_id, $request->tanggal, "Transaksi Masuk ID : $transaksi_masuk->id",  [
                [
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->harga_total,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $request->akun_kredit_id,
                'nominal_kredit' => $request->harga_total,
                'nominal_debit' => 0,
                ],
            ],
            [
                [
                'akun_id' => $this->HPP()->id ,
                'nominal_debit' => $transaksi_masuk->paket->product->harga_beli * $request->qty,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $transaksi_masuk->paket->product->akun_persediaan,
                'nominal_kredit' => $transaksi_masuk->paket->product->harga_beli * $request->qty,
                'nominal_debit' => 0,
                ],
            ]);
        }else{
            $this->jurnal_entry_edit($transaksi_masuk->jurnal_id, $request->tanggal, "Transaksi Masuk ID : $transaksi_masuk->id",  [
                [
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->harga_total,
                'nominal_kredit' => 0,
                ],
                [
                'akun_id' => $request->akun_kredit_id,
                'nominal_kredit' => $request->harga_total,
                'nominal_debit' => 0,
                ],
            ], []);
        }

        $this->laporan_transaksi_entry_edit($transaksi_masuk->laporan_transaksi_id, $request->product_id, $request->tanggal, $request->jasa_id, $request->paket_id);

        return redirect()->route('transaksi_masuks.index')->with('success', 'Transaksi Masuk updated successfully');
    }

    public function destroy($id){

        $transaksi_masuk = TransaksiMasuk::findOrFail($id);
        $transaksi_masuk->delete();
        if($transaksi_masuk->jurnal_id){
            $this->jurnal_entry_delete($transaksi_masuk->jurnal_id);            
        }
        if($transaksi_masuk->laporan_transaksi_id){
            $this->laporan_transaksi_entry_delete($transaksi_masuk->laporan_transaksi_id);
        }
        $this->restoreStock($transaksi_masuk);

        return redirect()->route('transaksi_masuks.index')->with('success', 'transaksi_masuks deleted successfully');
    }

    public function jurnal_entry($tanggal, $keterangan, $items = [], $items2 = []){

        $header = JurnalHeader::create([
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
        ]);

        foreach($items as $item){
            JurnalDetail::create([
            'jurnal_header_id' => $header->id,
            'akun_id' => $item['akun_id'],
            'nominal_debit' => $item['nominal_debit'],
            'nominal_kredit' => $item['nominal_kredit'],
            ]);
        }

        foreach ($items2 as $item) {
            JurnalDetail::create([
                'jurnal_header_id' => $header->id,
                'akun_id' => $item['akun_id'],
                'nominal_debit' => $item['nominal_debit'],
                'nominal_kredit' => $item['nominal_kredit'],
            ]);
        }

        return $header;
    }
    public function jurnal_entry_edit($id, $tanggal, $keterangan, $items = [], $items2 = [])
    {
        // update header
        $jurnal_header = JurnalHeader::findOrFail($id);
        $jurnal_header->update([
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
        ]);

        // hapus semua detail lama
        JurnalDetail::where('jurnal_header_id', $id)->delete();

        // insert ulang detail baru
        foreach ($items as $item) {
            JurnalDetail::create([
                'jurnal_header_id' => $id,
                'akun_id' => $item['akun_id'],
                'nominal_debit' => $item['nominal_debit'],
                'nominal_kredit' => $item['nominal_kredit'],
            ]);
        }

        foreach ($items2 as $item) {
            JurnalDetail::create([
                'jurnal_header_id' => $id,
                'akun_id' => $item['akun_id'],
                'nominal_debit' => $item['nominal_debit'],
                'nominal_kredit' => $item['nominal_kredit'],
            ]);
        }

        return $jurnal_header;
    }

    public function jurnal_entry_delete($id){

        JurnalDetail::where('jurnal_header_id', $id)->delete();
       
        return JurnalHeader::findOrFail($id)->delete();
    }

    public function laporan_transaksi_entry_create($product_id, $tanggal, $jasa_id, $paket_id){

        $laporan = LaporanTransaksi::create([
            'product_id' => $product_id ?? null,
            'tanggal' => $tanggal,
            'jasa_id' => $jasa_id ?? null,
            'paket_id' => $paket_id ?? null,
        ]);

        return $laporan;
    }
    public function laporan_transaksi_entry_edit($id, $product_id, $tanggal, $jasa_id, $paket_id){

        $laporan = LaporanTransaksi::find($id);

         $laporan->update([
            'product_id' => $product_id ?? null,
            'tanggal' => $tanggal,
            'jasa_id' => $jasa_id ?? null,
            'paket_id' => $paket_id ?? null,
        ]);

        return $laporan;
    }

    public function laporan_transaksi_entry_delete($id){
        $laporan = LaporanTransaksi::find($id);

        return $laporan->delete();
    }

    public function penguranganStock($product_id, $qty, $tipe, $paket_id = null)
    {
        if ($tipe === 'Barang') {

            $product = Product::find($product_id);

            if (!$product) {
                return; 
            }

            $product->update([
                'stock' => max(0, $product->stock - $qty)
            ]);

            return;
        }


        if ($tipe === 'Paket') {

            $paket = Paket::find($paket_id);
            if (!$paket) return;

            
            $product = Product::find($paket->product_id);
            if (!$product) return;

            $product->update([
                'stock' => max(0, $product->stock - 1)
            ]);

            return;
        }
    }

    public function restoreStock($transaksi)
    {
        // Jika transaksi lama adalah Barang
        if ($transaksi->tipe === 'Barang' && $transaksi->product_id) {

            $product = Product::find($transaksi->product_id);
            if ($product) {
                $product->update([
                    'stock' => $product->stock + $transaksi->qty
                ]);
            }
        }

        // Jika transaksi lama adalah Paket
        if ($transaksi->tipe === 'Paket' && $transaksi->paket_id) {

            $paket = Paket::find($transaksi->paket_id);
            if ($paket) {
                $product = Product::find($paket->product_id);
                if ($product) {
                    $product->update([
                        'stock' => $product->stock + 1 // paket selalu 1 product
                    ]);
                }
            }
        }
    }

    private function HPP(){
        $hpp = Akun::where('nama', 'HPP')->first();
        return $hpp;
    }

}
