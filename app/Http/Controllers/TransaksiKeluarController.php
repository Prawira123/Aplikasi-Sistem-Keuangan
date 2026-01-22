<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Pelanggan;
use App\Models\JurnalDetail;
use App\Models\JurnalHeader;
use Illuminate\Http\Request;
use App\Models\TransaksiKeluar;
use App\Models\LaporanTransaksi;

class TransaksiKeluarController extends Controller
{
    public function index(Request $request){
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $query = TransaksiKeluar::with(['akun', 'product', 'supplier']);

        if ($tahun && $bulan) {
            $query->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan);
        }

        $transaksi_keluars = $query->orderBy('tanggal', 'asc')->get();
        return view('transaksi_keluars.index',compact('transaksi_keluars'));
    }

    public function show(TransaksiKeluar $transaksi_keluar){
        $transaksi_keluar = TransaksiKeluar::with('akun', 'product', 'supplier')->find($transaksi_keluar->id)->first();
        return view('transaksi_keluars.show', compact('transaksi_keluar'));
    }

    public function create(TransaksiKeluar $transaksi_keluars){
        $products = Product::all();
        $akuns = Akun::all();
        $suppliers = Supplier::all();

        return view('transaksi_keluars.create', compact('transaksi_keluars', 'products', 'akuns', 'suppliers'));
    }

    public function store(Request $request){
        $request->validate([
            'supplier_id' => 'required',
            'product_id' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'qty' => 'required|numeric',
            'harga_satuan' =>  'required|numeric',
            'harga_total' => 'required|numeric',
            'akun_debit_id' => 'required',
            'akun_kredit_id' => 'required',
        ]);

        $latest = TransaksiKeluar::latest()->first();
        $nextId = $latest ? $latest->id + 1 : 1;

        $kode = 'TK'. str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $akunKredit = Akun::findOrFail($request->akun_kredit_id);

        if ($request->harga_total > $akunKredit->saldo_sementara) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Saldo akun '.$akunKredit->nama.' tidak mencukupi');
        }

        $transaksi_keluar = TransaksiKeluar::create([
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'qty' => $request->qty,
            'harga_satuan' => $request->harga_satuan,   
            'harga_total' => $request->harga_total,
            'kode' => $kode,
            'akun_debit_id' => $request->akun_debit_id,
            'akun_kredit_id' => $request->akun_kredit_id,
        ]);

        $jurnal = $this->jurnal_entry($request->tanggal,$request->keterangan,  [
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
        ]);

        $laporan = $this->laporan_transaksi_entry_create($request->product_id, $request->tanggal, $request->supplier_id);

        $transaksi_keluar->update([
            'jurnal_id' => $jurnal->id,
            'laporan_transaksi_id' => $laporan->id
        ]);
        
        $this->penambahanStock($request->product_id, $request->qty); 

        $this->saldo_debit_create($request->akun_debit_id, $request->harga_total);
        $this->saldo_kredit_create($request->akun_kredit_id, $request->harga_total);

        if ($request->action === 'save_next') {
        return redirect()
            ->route('transaksi_keluars.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('transaksi_keluars.index')->with('success', 'transaksi_keluars created successfully');
    }  

    public function edit(TransaksiKeluar $transaksi_keluar){
        $products = Product::all();
        $akuns = Akun::all();
        $suppliers = Supplier::all();

        return view('transaksi_keluars.edit', compact('transaksi_keluar', 'products', 'suppliers', 'akuns'));
    }   


    public function update(Request $request, TransaksiKeluar $transaksi_keluar){
        $request->validate([
            'supplier_id' => 'required',
            'product_id' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'qty' => 'required|numeric',
            'harga_satuan' =>  'required|numeric',
            'harga_total' => 'required|numeric',
            'akun_debit_id' => 'required',
            'akun_kredit_id' => 'required',
        ]);
       
        $this->penguranganStock($request->product_id, $transaksi_keluar->qty);

        $harga_total = $request->qty * $request->harga_satuan;

        $this->updateSaldoDebit($request->akun_debit_id, $harga_total, $transaksi_keluar->id);
        $this->updateSaldoKredit($request->akun_kredit_id, $harga_total, $transaksi_keluar->id);

        $transaksi_keluar->update([
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'qty' => $request->qty,
            'harga_satuan' => $request->harga_satuan,   
            'harga_total' => $harga_total,
            'akun_debit_id' => $request->akun_debit_id,
            'akun_kredit_id' => $request->akun_kredit_id,
        ]);

        $this->penambahanStock($request->product_id, $transaksi_keluar->qty);

        $this->jurnal_entry_edit($transaksi_keluar->jurnal_id, $request->tanggal, "Transaksi Edit ID :$transaksi_keluar->id",  [
            [
            'akun_id' => $request->akun_debit_id,
            'nominal_debit' => $harga_total,
            'nominal_kredit' => 0,
            ],
            [
            'akun_id' => $request->akun_kredit_id,
            'nominal_kredit' => $harga_total,
            'nominal_debit' => 0,
            ],
        ]);

        $this->laporan_transaksi_entry_edit($transaksi_keluar->laporan_transaksi_id, $request->product_id, $request->tanggal, $request->supplier_id);

        return redirect()->route('transaksi_keluars.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(TransaksiKeluar $transaksi_keluar){
        
        $this->saldo_debit_deleted($transaksi_keluar->id, $transaksi_keluar->akun_debit_id);
        $this->saldo_kredit_deleted($transaksi_keluar->id, $transaksi_keluar->akun_kredit_id);

        $transaksi_keluar->delete();
        $this->jurnal_entry_delete($transaksi_keluar->jurnal_id);
        $this->laporan_transaksi_entry_delete($transaksi_keluar->laporan_transaksi_id);

        return redirect()->route('transaksi_keluars.index')->with('success', 'transaksi_keluars deleted successfully');
    }

    public function penguranganStock($product_id, $qty){
        $product = Product::find($product_id);
        $product->update(['stock' => $product->stock-$qty]);
    }  

    public function penambahanStock($product_id, $qty){
        $product = Product::find($product_id);
        $product->update(['stock' => $product->stock+$qty]);
    } 

    public function jurnal_entry($tanggal, $keterangan, $items = []){

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

        return $header;
    }

    public function jurnal_entry_edit($id, $tanggal, $keterangan, $items = [])
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

        return $jurnal_header;
    }

    public function laporan_transaksi_entry_create($product_id, $tanggal, $supplier_id){

        $laporan = LaporanTransaksi::create([
            'product_id' => $product_id,
            'tanggal' => $tanggal,
            'supplier_id' => $supplier_id,
        ]);

        return $laporan;
    }
    public function laporan_transaksi_entry_edit($id, $product_id, $tanggal, $supplier_id){

        $laporan = LaporanTransaksi::find($id);

         $laporan->update([
            'product_id' => $product_id,
            'tanggal' => $tanggal,
            'supplier_id' => $supplier_id,
        ]);

        return $laporan;
    }

    public function laporan_transaksi_entry_delete($id){
        $laporan = LaporanTransaksi::find($id);

        return $laporan->delete();
    }

    public function jurnal_entry_delete($id){

        JurnalDetail::where('jurnal_header_id', $id)->delete();
       
        return JurnalHeader::findOrFail($id)->delete();
    }

     public function saldo_debit_create($id, $saldo_sementara){
        $akun = Akun::find($id); // Ganti 1 dengan ID akun yang sesuai

        if($akun->normal_post == 'Debit'){
            $saldo = $akun->saldo_sementara + $saldo_sementara;
        } else {
            $saldo = $akun->saldo_sementara - $saldo_sementara;
        }

       $akun->update([
        'saldo_sementara' => $saldo,
       ]);
    }

    public function saldo_kredit_create($id, $saldo_sementara){
        $akun = Akun::find($id);

        if($akun->normal_post == 'Kredit'){
            $saldo = $akun->saldo_sementara + $saldo_sementara;
        } else {
            $saldo = $akun->saldo_sementara - $saldo_sementara;
        }

       $akun->update([
        'saldo_sementara' => $saldo,
       ]);
    }

    public function updateSaldoDebit($id, $nominalBaru, $transaksi_id)
    {
        $transaksi = TransaksiKeluar::findOrFail($transaksi_id);
        $akun = Akun::findOrFail($id);

        $akunDebitBaru = $akun->id;

        if ($transaksi->akun_debit_id == $akunDebitBaru) {
            // akun TIDAK berubah → rollback + apply
            $this->rollbackSaldo($akunDebitBaru, $transaksi->harga_total, 'Debit');
            $this->applySaldo($akunDebitBaru, $nominalBaru, 'Debit');
        } else {
            // akun BERUBAH
            $this->rollbackSaldo($transaksi->akun_debit_id, $transaksi->harga_total, 'Debit');
            $this->applySaldo($akunDebitBaru, $nominalBaru, 'Debit');
        }
    }

    public function updateSaldoKredit($id, $nominalBaru, $transaksi_id)
    {
        $transaksi = TransaksiKeluar::findOrFail($transaksi_id);
        $akun = Akun::findOrFail($id);

        $akunKreditBaru = $akun->id;

        if ($transaksi->akun_kredit_id == $akunKreditBaru) {
            $this->rollbackSaldo($transaksi->akun_kredit_id, $transaksi->harga_total, 'Kredit');
            $this->applySaldo($akunKreditBaru, $nominalBaru, 'Kredit');
        } else {
            $this->rollbackSaldo($transaksi->akun_kredit_id, $transaksi->harga_total, 'Kredit');
            $this->applySaldo($akunKreditBaru, $nominalBaru, 'Kredit');
        }
    }
    private function rollbackSaldo($akunId, $nominal, $posisi)
    {
        $akun = Akun::findOrFail($akunId);

        if (
            ($posisi == 'Debit' && $akun->normal_post == 'Debit') ||
            ($posisi == 'Kredit' && $akun->normal_post == 'Kredit')
        ) {
            $akun->saldo_sementara -= $nominal;
        } else {
            $akun->saldo_sementara += $nominal;
        }

        $akun->save();
    }

    private function applySaldo($akunId, $nominal, $posisi)
    {
        $akun = Akun::findOrFail($akunId);

        if (
            ($posisi == 'Debit' && $akun->normal_post == 'Debit') ||
            ($posisi == 'Kredit' && $akun->normal_post == 'Kredit')
        ) {
            $akun->saldo_sementara += $nominal;
        } else {
            $akun->saldo_sementara -= $nominal;
        }

        $akun->save();
    }

    public function saldo_debit_deleted($transaksi_id, $id){
        $transaksi = TransaksiKeluar::findOrFail($transaksi_id);

        $akun = Akun::findOrFail($id);

        if($transaksi->akun_debit_id == $akun->id){
            if($akun->normal_post == 'Debit'){
                $saldo = $akun->saldo_sementara - $transaksi->harga_total;
            } else {
                $saldo = $akun->saldo_sementara + $transaksi->harga_total;
            }

           $akun->update([
            'saldo_sementara' => $saldo,
           ]);
        }

    }

    public function saldo_kredit_deleted($transaksi_id, $id){
        $transaksi = TransaksiKeluar::findOrFail($transaksi_id);

        $akun = Akun::findOrFail($id);

        if($transaksi->akun_kredit_id == $akun->id){
            if($akun->normal_post == 'Kredit'){
                $saldo = $akun->saldo_sementara - $transaksi->harga_total;
            } else {
                $saldo = $akun->saldo_sementara + $transaksi->harga_total;
            }

           $akun->update([
            'saldo_sementara' => $saldo,
           ]);
        }

    }
}
