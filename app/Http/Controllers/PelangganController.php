<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(){
        $pelanggans = Pelanggan::with('transaksi')->get();
        return view('pelanggans.index', compact('pelanggans'));
    }

    public function show($id){
        $pelanggan = Pelanggan::with('transaksi')->findOrFail($id);
        return view('pelanggans.show', compact('pelanggan'));
    }

    public function create(){
        return view('pelanggans.create');
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string',
            'no_tlp' => 'required|numeric',
            'alamat' => 'required|string',
            'nik' => 'required|numeric',
            'jenis_kelamin' => 'required|string',
        ]);

        Pelanggan::create($request->all());

        if ($request->action === 'save_next') {
            return redirect()
            ->route('pelanggans.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('pelanggans.index')->with('success', 'Supplier created successfully');
    }

    public function edit($id){
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggans.edit', compact('pelanggan'));
    }

    public function update(Request $request,  $id){
       
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string',
            'no_tlp' => 'required|numeric',
            'alamat' => 'required|string',
            'nik' => 'required|numeric',
            'jenis_kelamin' => 'required|string',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggans.index')->with('success', 'pelanggan updated successfully');
    }

    public function destroy($id){
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggans.index')->with('success', 'pelanggan deleted successfully');
    }
}
