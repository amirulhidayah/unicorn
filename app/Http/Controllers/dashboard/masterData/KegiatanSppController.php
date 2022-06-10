<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\KegiatanSpp;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KegiatanSppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $idProgram = $request->programSpp;
        if ($request->ajax()) {
            $data = KegiatanSpp::where('program_spp_id', $idProgram)->orderBy('no_rek', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.kegiatanSpp.index', compact('idProgram'));
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
                'no_rek' => [$request->no_rek ? Rule::unique('kegiatan_spp')->withoutTrashed() : ''],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                // 'no_rek.required' => 'Nomor Rekening Kegiatan SKPD tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening Kegiatan SKPD sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $kegiatanSpp = new KegiatanSpp();
        $kegiatanSpp->program_spp_id = $request->programSpp;
        $kegiatanSpp->nama = $request->nama;
        $kegiatanSpp->no_rek = $request->no_rek;
        $kegiatanSpp->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KegiatanSpp  $kegiatanSpp
     * @return \Illuminate\Http\Response
     */
    public function show(KegiatanSpp $kegiatanSpp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KegiatanSpp  $kegiatanSpp
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $kegiatanSpp = KegiatanSpp::find($request->kegiatanSpp);
        return response()->json($kegiatanSpp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KegiatanSpp  $kegiatanSpp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $kegiatanSpp = KegiatanSpp::find($request->kegiatanSpp);
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('kegiatan_spp')->ignore($kegiatanSpp->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'no_rek.required' => 'Nomor Rekening Kegiatan SKPD tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening Kegiatan SKPD sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $kegiatanSpp->program_spp_id = $request->programSpp;
        $kegiatanSpp->nama = $request->nama;
        $kegiatanSpp->no_rek = $request->no_rek;
        $kegiatanSpp->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KegiatanSpp  $kegiatanSpp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        KegiatanSpp::where('id', $request->kegiatanSpp)->delete();
        return response()->json(['status' => 'success']);
    }
}
