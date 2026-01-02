<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\GajiKaryawan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index(){
        $karyawans = Karyawan::all();
        return view('karyawans.index',compact('karyawans'));
    }

    public function show(Karyawan $karyawan){
        return view('karyawans.show', compact('karyawan'));
    }

    public function create(Karyawan $karyawan){
        return view('karyawans.create', compact('karyawan'));
    }

    public function store(Request $request, Karyawan $karyawan){
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:karyawans',
            'phone_number' => 'required|max:11',
            'address' => 'required|max:255',
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
        ]);
        $formatted = implode('-', str_split($request->phone_number, 3));
        $phone_number = '+62 '.$formatted;

        $karyawan::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone_number' => $phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
        ]);

        if ($request->action === 'save_next') {
            return redirect()
            ->route('karyawans.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('karyawans.index')->with('success', 'karyawan created successfully');
    }

    public function edit(Karyawan $karyawan){
        return view('karyawans.edit', compact('karyawan'));
    }   

    public function update(Request $request, Karyawan $karyawan){
        $validator = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(karyawan::class)->ignore($karyawan->id),
            ],
            'phone_number' => 'required|max:16',
            'address' => 'required|max:255',
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
        ]);

        $karyawan->update($validator);

        $gaji_karyawan = GajiKaryawan::where('karyawan_id', $karyawan->id)->first();
        
        if($gaji_karyawan && $gaji_karyawan->kehadiran > 0){
            $this->update_total_gaji($karyawan->id);  
        }

        return redirect()->route('karyawans.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(Karyawan $karyawan){
        $karyawan->delete();

        return redirect()->route('karyawans.index')->with('success', 'karyawan deleted successfully');
    }

    public function karyawanStatus($id){

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update(['status' => $karyawan->status == 'Active' ? 'Innactive' : 'Active']);

        return redirect()->route('karyawans.index')->with('success', 'Karyawan status updated successfully');
    }

    public function update_total_gaji($karyawan_id){
        $gaji_karyawan = GajiKaryawan::where('karyawan_id', $karyawan_id)->first();
        $karyawan = Karyawan::findorfail($karyawan_id);
        $total_gaji = $karyawan->salary * $gaji_karyawan->kehadiran;

        $gaji_karyawan->update([
            'total_gaji' => $total_gaji,
        ]);

    }
}
