<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function show($id){
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create(){
        $akuns = Akun::join('kategori_akuns', 'kategori_akuns.id', '=', 'akuns.kategori_akun_id')
        ->where('kategori_akuns.nama', 'PERSEDIAAN')
        ->select('akuns.nama', 'akuns.id')
        ->get();
        return view('products.create', compact('akuns'));
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga_beli' => 'required|numeric',
            'harga' => 'required|numeric',
            'akun_persediaan' => $request->akun_persediaan ? 'required' : 'nullable',
        ]);

        Product::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga_beli' => $request->harga_beli,
            'harga' => $request->harga,
            'akun_persediaan' => $request->akun_persediaan
        ]);

        if ($request->action === 'save_next') {
        return redirect()
            ->route('products.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product){
        $akuns = Akun::join('kategori_akuns', 'kategori_akuns.id', '=', 'akuns.kategori_akun_id')
        ->where('kategori_akuns.nama', 'PERSEDIAAN')
        ->select('akuns.nama', 'akuns.id')
        ->get();
        return view('products.edit', compact('akuns', 'product'));
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga_beli' => 'required|numeric',
            'harga' => 'required|numeric',
            'stock' => 'required|numeric',
            'akun_persediaan' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product){
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
