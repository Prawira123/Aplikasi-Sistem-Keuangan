<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\JurnalDetail;
use App\Models\JurnalHeader;
use Illuminate\Http\Request;

class JurnalEntryController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = JurnalDetail::with(['jurnal_header', 'akun'])
            ->join('jurnal_headers', 'jurnal_headers.id', '=', 'jurnal_details.jurnal_header_id')
            ->select('jurnal_details.*'); // penting agar tidak terjadi konflik kolom

        if ($start && $end) {
            $query->whereBetween('jurnal_headers.tanggal', [$start, $end]);
        }

        $jurnal_datas = $query->orderBy('jurnal_headers.tanggal', 'asc')->get();

        return view('jurnal_entries.index', compact('jurnal_datas'));
    }

    public function show($id){
        $jurnal_datas = JurnalDetail::with('jurnal_header', 'akun')->where( 'jurnal_header_id',$id)->get();
        return view('jurnal_entries.show', compact('jurnal_datas'));
    }

    public function create(){
        $akuns = Akun::select('id', 'nama')->get();
        return view('jurnal_entries.create', compact('akuns'));
    }

    public function store(Request $request){
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit_id' => $request->akun_debit_id ? 'required' : 'nullable',
            'akun_kredit_id' => $request->akun_kredit_id ? 'required' : 'nullable',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
        ]);

        $jurnal_header = JurnalHeader::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        if($request->akun_debit_id && !$request->akun_kredit_id){
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal_header->id,
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->nominal,
                'nominal_kredit' => 0,
            ]);
        }elseif($request->akun_kredit_id && !$request->akun_debit_id){
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal_header->id,
                'akun_id' => $request->akun_kredit_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request->nominal,
            ]);
        }elseif($request->akun_debit_id && $request->akun_kredit_id){
            $items = [
                [
                    'jurnal_header_id' => $jurnal_header->id,
                    'akun_id' => $request->akun_debit_id,
                    'nominal_debit' => $request->nominal,
                    'nominal_kredit' => 0,
                ],
                [
                    'jurnal_header_id' => $jurnal_header->id,
                    'akun_id' => $request->akun_kredit_id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->nominal,
                ],
            ];
            foreach($items as $item){
                JurnalDetail::create([
                    'jurnal_header_id' => $item['jurnal_header_id'],
                    'akun_id' => $item['akun_id'],
                    'nominal_debit' => $item['nominal_debit'],
                    'nominal_kredit' => $item['nominal_kredit'],
                ]);
            }   
        }

        if ($request->action === 'save_next') {
            return redirect()
            ->route('jurnal_entries.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('jurnal_entries.index')->with('success', 'jurnal entry created successfully');
    }

    public function edit($id){
        $jurnal_data = JurnalHeader::with('jurnal_details')->findOrFail($id);
        // $jurnal_header = JurnalHeader::with('jurnal_details')->findOrFail($jurnal_data->jurnal_header_id);
        $akuns = Akun::select('id', 'nama')->get();

        return view('jurnal_entries.edit', compact('jurnal_data', 'akuns'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit_id' => $request->akun_debit_id ? 'required' : 'nullable',
            'akun_kredit_id' => $request->akun_kredit_id ? 'required' : 'nullable',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
        ]);

        $jurnal_header = JurnalHeader::findOrFail($id);
        $jurnal_header->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        JurnalDetail::where('jurnal_header_id', $jurnal_header->id)->delete();

        if($request->akun_debit_id && $request->akun_kredit_id){
            $items = [
                [
                    'jurnal_header_id' => $jurnal_header->id,
                    'akun_id' => $request->akun_debit_id,
                    'nominal_debit' => $request->nominal,
                    'nominal_kredit' => 0,
                ],
                [
                    'jurnal_header_id' => $jurnal_header->id,
                    'akun_id' => $request->akun_kredit_id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->nominal,
                ],
            ];


            foreach($items as $item){
                JurnalDetail::create([
                    'jurnal_header_id' => $item['jurnal_header_id'],
                    'akun_id' => $item['akun_id'],
                    'nominal_debit' => $item['nominal_debit'],
                    'nominal_kredit' => $item['nominal_kredit'],
                ]);
            }   
        }elseif($request->akun_debit_id && !$request->akun_kredit_id){
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal_header->id,
                'akun_id' => $request->akun_debit_id,
                'nominal_debit' => $request->nominal,
                'nominal_kredit' => 0,
            ]);
        }elseif($request->akun_kredit_id && !$request->akun_debit_id){
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal_header->id,
                'akun_id' => $request->akun_kredit_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request->nominal,
            ]);
        }
        return redirect()->route('jurnal_entries.index')->with('success', 'jurnal entry updated successfully');
    }

    public function destroy($id){

        $jurnal_header = JurnalHeader::findOrFail($id);
        JurnalDetail::where('jurnal_header_id', $jurnal_header->id)->delete();
        $jurnal_header->delete();

        return redirect()->route('jurnal_entries.index')->with('success', 'jurnal entry deleted successfully');
    }


}


