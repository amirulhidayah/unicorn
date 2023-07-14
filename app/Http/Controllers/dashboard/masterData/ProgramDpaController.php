<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\ProgramDpa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramDpaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProgramDpa::orderBy('no_rek', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . url('master-data/kegiatan-dpa/' . $row->id) . '" class="btn btn-primary btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-eye"></i> Lihat Kegiatan</a><button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.programDpa.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('program')->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'no_rek.required' => 'Nomor Rekening tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $programDpa = new ProgramDpa();
        $programDpa->nama = $request->nama;
        $programDpa->no_rek = $request->no_rek;
        $programDpa->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramDpa  $programDpa
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramDpa $programDpa)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramDpa  $programDpa
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramDpa $programDpa)
    {
        return response()->json($programDpa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramDpa  $programDpa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProgramDpa $programDpa)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('program')->ignore($programDpa->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'no_rek.required' => 'Nomor Rekening tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $programDpa->nama = $request->nama;
        $programDpa->no_rek = $request->no_rek;
        $programDpa->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramDpa  $programDpa
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramDpa $programDpa)
    {
        $programDpa->delete();

        Kegiatan::where('program_id', $programDpa->id)->delete();

        return response()->json(['status' => 'success']);
    }
}
