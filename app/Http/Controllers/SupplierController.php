<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
     public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function show($id){
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    public function create(){
        return view('suppliers.create');
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string',
            'no_tlp' => 'required|numeric',
            'alamat' => 'required|string|max:255',
        ]);

        supplier::create($request->all());

        if ($request->action === 'save_next') {
            return redirect()
            ->route('suppliers.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully');
    }

    public function edit(Supplier $supplier){
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier){
        $request->validate([
            'nama' => 'required|string',
            'no_tlp' => 'required|numeric',
            'alamat' => 'required|string|max:255',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'supplier updated successfully');
    }

    public function destroy(Supplier $supplier){
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'supplier deleted successfully');
    }
}
