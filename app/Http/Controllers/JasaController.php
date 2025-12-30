<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;

class JasaController extends Controller
{
     public function index()
    {
        $jasas = Jasa::all();
        return view('jasas.index', compact('jasas'));
    }

    public function show($id){
        $jasa = Jasa::findOrFail($id);
        return view('jasas.show', compact('jasa'));
    }

    public function create(){
        return view('jasas.create');
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
        ]);

        Jasa::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
        ]);

        if ($request->action === 'save_next') {
            return redirect()
            ->route('jasas.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('jasas.index')->with('success', 'jasa created successfully');
    }

    public function edit(Jasa $jasa){
        return view('jasas.edit', compact('jasa'));
    }

    public function update(Request $request, jasa $jasa){
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
        ]);

        $jasa->update($request->all());

        return redirect()->route('jasas.index')->with('success', 'jasa updated successfully');
    }

    public function destroy(Jasa $jasa){
        $jasa->delete();
        return redirect()->route('jasas.index')->with('success', 'jasa deleted successfully');
    }
}
