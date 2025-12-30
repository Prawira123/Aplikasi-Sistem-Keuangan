<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use App\Models\Paket;
use App\Models\Product;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::with('product', 'jasa')->get();
        return view('pakets.index', compact('pakets'));
    }

    public function show($id){
        $paket = Paket::findOrFail($id);
        return view('pakets.show', compact('paket'));
    }

    public function create(){

        $products = Product::select('id', 'nama')->get();
        $jasas = Jasa::select('id', 'nama')->get();

        return view('pakets.create', compact('products', 'jasas'));
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string|max:255',
            'product_id' => 'required',
            'jasa_id' => 'required',
            'harga' => 'required|numeric',
        ]);

        Paket::create($request->all());

        if ($request->action === 'save_next') {
            return redirect()
            ->route('pakets.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('pakets.index')->with('success', 'Paket created successfully');
    }

    public function edit($id){

        $paket = Paket::findOrFail($id);
        $products = Product::select('id', 'nama')->get();
        $jasas = Jasa::select('id', 'nama')->get();

        return view('pakets.edit', compact('paket', 'products', 'jasas'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'nama' => 'required|string|max:255',
            'product_id' => 'required',
            'jasa_id' => 'required',
            'harga' => 'required|numeric',
        ]);

        $paket = Paket::findOrFail($id);
        $paket->update($request->all());

        return redirect()->route('pakets.index')->with('success', 'Paket updated successfully');
    }

    public function destroy($id){
        $paket = Paket::findOrFail($id);
        $paket->delete();
        return redirect()->route('pakets.index')->with('success', 'Paket deleted successfully');
    }
}
