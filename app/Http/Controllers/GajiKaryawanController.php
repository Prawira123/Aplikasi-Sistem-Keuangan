<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Karyawan;
use App\Models\GajiKaryawan;
use App\Models\JurnalDetail;
use App\Models\JurnalHeader;
use Illuminate\Http\Request;

class GajiKaryawanController extends Controller
{
     public function index(){
        $gaji_karyawans = GajiKaryawan::with('karyawan')->get();
        return view('gaji_karyawans.index',compact('gaji_karyawans'));
    }

    public function show($id){
        $gaji_karyawan = GajiKaryawan::with('karyawan')->findOrFail($id);
        return view('gaji_karyawans.show', compact('gaji_karyawan'));
    }

    public function create(){
        $karyawans = Karyawan::select('id', 'fullname', 'salary')->get();
        return view('gaji_karyawans.create', compact('karyawans'));
    }

    public function store(Request $request){
        $request->validate([
            'karyawan_id' => 'required',
            'kehadiran' => 'required|numeric',
        ]);

        $total_gaji = $this->total_gaji($request->karyawan_id, $request->kehadiran);

        GajiKaryawan::create([
            'karyawan_id' => $request->karyawan_id,
            'kehadiran' => $request->kehadiran,
            'total_gaji' => $total_gaji,
        ]);

        if ($request->action === 'save_next') {
            return redirect()
            ->route('gaji_karyawans.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('gaji_karyawans.index')->with('success', 'karyawan created successfully');
    }

    public function edit($id){
        $gaji_karyawan = GajiKaryawan::with('karyawan')->findOrFail($id);
        $karyawans = Karyawan::select('id', 'fullname','salary')->get();

        return view('gaji_karyawans.edit', compact('gaji_karyawan', 'karyawans'));
    }  

    public function update(Request $request, $id){
        
        $gaji_karyawan = GajiKaryawan::with('karyawan')->findOrFail($id);

        $request->validate([
            'karyawan_id' => 'required',
            'kehadiran' => 'required|numeric',
        ]);

        $total_gaji = $this->total_gaji($request->karyawan_id, $request->kehadiran);

        $gaji_karyawan->update([
            'karyawan_id' => $request->karyawan_id,
            'kehadiran' => $request->kehadiran,
            'total_gaji' => $total_gaji,
            'status' => 'Belum',
        ]);

        return redirect()->route('gaji_karyawans.index')->with('success', 'Employee updated successfully');
    }

    public function destroy($id){
        $gaji_karyawan = GajiKaryawan::findOrFail($id);
        $gaji_karyawan->delete();

        return redirect()->route('gaji_karyawans.index')->with('success', 'karyawan deleted successfully');
    }

    public function total_gaji($karyawan_id, $kehadiran){
        $karyawan = Karyawan::findOrFail($karyawan_id);

        $total_gaji = $kehadiran * $karyawan->salary;
        return $total_gaji;
    }

    public function jurnal_create(){
        $akuns = Akun::select('id', 'nama')->get();
        $gaji_karyawan = GajiKaryawan::with('karyawan')->get();
        $total_gaji = $gaji_karyawan->where('status', 'Belum')->sum('total_gaji');

        return view('gaji_karyawans.jurnal_create', compact('akuns', 'total_gaji'));
    }

    public function jurnal_store(Request $request){
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit' => 'required',
            'akun_kredit' => 'required',
            'total_gaji' => 'required|numeric',
        ]);

        $this->jurnal_entry($request->tanggal, 'Pembayaran Gaji Karyawan tanggal '.\Carbon\Carbon::parse($request->tanggal)->format('d F Y'), [
            [
                'akun_id' => $request->akun_debit,
                'nominal_debit' => $request->total_gaji,
                'nominal_kredit' => 0,
            ],
            [
                'akun_id' => $request->akun_kredit,
                'nominal_debit' => 0,
                'nominal_kredit' => $request->total_gaji,
            ],
        ]);

        $gaji_karyawans = GajiKaryawan::where('status', 'Belum')->get();

        foreach($gaji_karyawans as $gaji_karyawan){
            $gaji_karyawan->update([
                'status' => 'Sudah',
            ]);
        }

        return redirect()->route('gaji_karyawans.index')->with('success', 'Pembayaran Gaji Karyawan successfully');
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
}
