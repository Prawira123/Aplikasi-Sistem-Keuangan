<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Kelompok;
use App\Models\KategoriAkun;
use Illuminate\Http\Request;

class KategoriAkunController extends Controller
{
    public function index()
    {
        $kategories = KategoriAkun::with('kelompok')->get();
        return view('kategories.index', compact('kategories'));
    }

    public function show($id){
        $kategori = KategoriAkun::findOrFail($id);
        return view('kategories.show', compact('kategori'));
    }

    public function create(){
        $kelompoks = Kelompok::all();
        return view('kategories.create', compact('kelompoks'));
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelompok_id' => 'required',
        ]);

        $nama = strtoupper($request->nama);

        $latest = KategoriAkun::where('kelompok_id', $request->kelompok_id)
                ->orderBy('kode', 'desc')
                ->first();

        $nextNumber = $latest ? intval(substr($latest->kode, 1)) + 1 : 1;

        $kode = $request->kelompok_id . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        KategoriAkun::create([
            'nama' => $nama,
            'kelompok_id' => $request->kelompok_id,
            'kode' => $kode
        ]);

        if ($request->action === 'save_next') {
            return redirect()
            ->route('kategories.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('kategories.index')->with('success', 'kategori created successfully');
    }

    public function edit($id){
        $kategori = KategoriAkun::findOrFail($id); 
        $kelompoks = Kelompok::all();
        return view('kategories.edit', compact('kategori', 'kelompoks'));
    }

    public function update(Request $request, $id){

        $request->validate([
            'nama' => 'required|string|max:255',
            'kelompok_id' => 'required',
        ]);

        $nama = strtoupper($request->nama);

        $kode = $this->kodeKategori($request->kelompok_id);

        $kategori = KategoriAkun::findOrFail($id);
        $kategori->update([
            'nama' => $nama,
            'kelompok_id' => $request->kelompok_id,
            'kode' => $kode
        ]);

        return redirect()->route('kategories.index')->with('success', 'kategori updated successfully');
    }

    public function kodeKategori($kelompok_id){
        $latest = KategoriAkun::where('kelompok_id', $kelompok_id)
                ->orderBy('kode', 'desc')
                ->first();

        $nextNumber = $latest ? intval(substr($latest->kode, 1)) + 1 : 1;

        $kode = $kelompok_id . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        return $kode;
    }

    public function destroy($id){
        $kategori = KategoriAkun::findOrFail($id);   
        $kategori->delete();

        $this->delete_akun($kategori->id);
        return redirect()->route('kategories.index')->with('success', 'kategori deleted successfully');
    }

    public function delete_akun($id){
        $akun = Akun::where('kategori_akun_id', $id);   
        $akun->delete();
    }
}
