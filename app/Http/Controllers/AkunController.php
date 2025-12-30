<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Role;
use App\Models\JurnalHeader;
use App\Models\KategoriAkun;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\JurnalDetail;

class AkunController extends Controller
{
        public function index()
    {
        $akuns = Akun::with('kategori_akun')->get();
        return view('akuns.index', compact('akuns'));
    }

    public function show($id){
        $akun = Akun::findOrFail($id);
        return view('akuns.show', compact('akun'));
    }

    public function create(){
        $kategories = KategoriAkun::all();
        $akuns = Akun::all();
        return view('akuns.create', compact('kategories', 'akuns'));
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string|max:255',
            'normal_post' => 'required|string',
            'lawan_post' => $request->lawan_post ? 'required' : 'nullable',
            'kategori_akun_id' => 'required',
            'aktivitas_kas' => $request->aktivitas_kas ? 'required' : 'nullable',
            'saldo_awal' =>'nullable|numeric',
        ]);

        $kode = $this->kodeAkun($request->kategori_akun_id);
        $kelompok_id = KategoriAkun::where('id', $request->kategori_akun_id)->value('kelompok_id');
        
        $akun = Akun::create([
            'kode' => $kode,
            'nama' => strtoupper($request->nama),
            'kelompok_id' => $kelompok_id,
            'normal_post' => $request->normal_post,
            'lawan_post' => $request->lawan_post,
            'kategori_akun_id' => $request->kategori_akun_id,
            'aktivitas_kas' => $request->aktivitas_kas,
            'saldo_awal' => $request->saldo_awal ? $request->saldo_awal : 0,
        ]);

        if($akun->saldo_awal > 0 && $akun->normal_post == 'Debit'){
            $jurnal = $this->jurnal_entry($akun->created_at, 'Pengisian Saldo Awal '. $akun->nama,  [
                [
                    'akun_id' => $akun->id,
                    'nominal_debit' => $request->saldo_awal,
                    'nominal_kredit' => 0,
                ],
                [
                    'akun_id' => $request->lawan_post,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->saldo_awal,
                ]
            ]);
        }elseif($akun->saldo_awal > 0 && $akun->normal_post == 'Kredit'){
             $jurnal = $this->jurnal_entry($akun->created_at, 'Pengisian Saldo Awal '. $akun->nama,  [
                [
                    'akun_id' => $akun->lawan_post,
                    'nominal_debit' => $request->saldo_awal,
                    'nominal_kredit' => 0,
                ],
                [
                    'akun_id' => $request->id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->saldo_awal,
                ]
            ]);
        }

        if($akun->saldo_awal > 0){
            $akun->update([
                'jurnal_id' => $jurnal->id,
            ]);
        }

        if ($request->action === 'save_next') {
            return redirect()
            ->route('akuns.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('akuns.index')->with('success', 'Akun created successfully');
    }

    public function edit(Akun $akun){
        $kategories = KategoriAkun::all();
        $akuns = Akun::all();
        return view('akuns.edit', compact('akun', 'kategories','akuns'));
    }

    public function update(Request $request, $id){
        
        $akun = Akun::find($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'normal_post' => 'required|string',
            'lawan_post' => $request->lawan_post ? 'required' : 'nullable',
            'kategori_akun_id' => 'required', 
            'aktivitas_kas' => $request->aktivitas_kas ? 'required' : 'nullable',
            'saldo_awal' =>'nullable|numeric',
        ]);
        
        $kode = $this->kodeAkun($request->kategori_akun_id);
        $kelompok_id = KategoriAkun::where('id', $request->kategori_akun_id)->value('kelompok_id');

        $akun->update([
            'kode' => $kode,
            'nama' => strtoupper($request->nama),
            'kelompok_id' => $kelompok_id,
            'normal_post' => $request->normal_post,
            'lawan_post' => $request->lawan_post,
            'aktivitas_kas' => $request->aktivitas_kas,
            'kategori_akun_id' => $request->kategori_akun_id,
            'saldo_awal' => $request->saldo_awal,
        ]);


    if($akun->normal_post == 'Debit'){
        if($akun->saldo_awal > 0 && $akun->jurnal_id){
            $this->jurnal_entry_edit($akun->jurnal_id, $akun->updated_at, 'Update Saldo Awal '. $akun->nama, [
                [
                    'akun_id' => $akun->id,
                    'nominal_debit' => $request->saldo_awal,
                    'nominal_kredit' => 0,
                ],
                [
                    'akun_id' => $request->lawan_post,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->saldo_awal,
                ]
            ]);
        }elseif($akun->saldo_awal > 0 && !$akun->jurnal_id){
            $this->jurnal_entry($akun->created_at, 'Pengisian Saldo Awal '. $akun->nama,  [
                [
                    'akun_id' => $akun->id,
                    'nominal_debit' => $request->saldo_awal,
                    'nominal_kredit' => 0,
                ],
                [
                    'akun_id' => $request->lawan_post,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->saldo_awal,
                ]
            ]);
        }
    }elseif($akun->normal_post == 'Kredit'){
        if($akun->saldo_awal > 0 && $akun->jurnal_id){
            $this->jurnal_entry_edit($akun->jurnal_id, $akun->updated_at, 'Update Saldo Awal '. $akun->nama, [
                [
                    'akun_id' => $request->lawan_post,
                    'nominal_debit' => $request->saldo_awal,
                    'nominal_kredit' => 0,
                ],
                [
                    'akun_id' => $akun->id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->saldo_awal,
                ]
            ]);
        }elseif($akun->saldo_awal > 0 && !$akun->jurnal_id){
            $this->jurnal_entry($akun->created_at, 'Pengisian Saldo Awal '. $akun->nama,  [
                [
                    'akun_id' => $request->lawan_post,
                    'nominal_debit' => $request->saldo_awal,
                    'nominal_kredit' => 0,
                ],
                [
                    'akun_id' => $akun->id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->saldo_awal,
                ]
            ]);
        }
    }

        return redirect()->route('akuns.index')->with('success', 'Akun updated successfully');
    }

    public function destroy($id){
        $akun = Akun::findOrFail($id);
        $akun->delete();
        $this->jurnal_entry_delete($id);
        return redirect()->route('akuns.index')->with('success', 'Akun deleted successfully');
    }

    public function kodeAkun($kategori_akun_id){
        $kategori = KategoriAkun::findOrFail($kategori_akun_id);
        $kodeKategori = $kategori->kode;

        $latest = Akun::where('kategori_akun_id', $kategori_akun_id)
                    ->orderBy('kode', 'desc')
                    ->first();

        // Tentukan nomor urut akun berikutnya
        $nextNumber = $latest
            ? intval(substr($latest->kode, strlen($kodeKategori))) + 1
            : 1;

        // Hasil akhirnya: contoh 1011, 1012, 1013
        $kode = $kodeKategori . $nextNumber;

        return $kode;
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
        $header = JurnalHeader::findOrFail($id);
        $header->update([
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
        ]);

        // hapus semua detail lama
        JurnalDetail::where('jurnal_header_id', $id)->delete();

        // insert ulang detail baru

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

    public function jurnal_entry_delete($akun_id)
    {
        // Ambil semua detail yang memakai akun ini
        $details = JurnalDetail::where('akun_id', $akun_id)->get();

        // Ambil semua header_id unik milik detail tersebut
        $header_ids = $details->pluck('jurnal_header_id')->unique();

        // Hapus semua detail
        JurnalDetail::where('akun_id', $akun_id)->delete();

        // Cek setiap header, apakah masih punya detail lain?
        foreach ($header_ids as $header_id) {
            $remaining = JurnalDetail::where('jurnal_header_id', $header_id)->count();

            // Jika tidak ada detail tersisa → hapus header
            if ($remaining == 0) {
                JurnalHeader::where('id', $header_id)->delete();
            }
        }
    }

}
