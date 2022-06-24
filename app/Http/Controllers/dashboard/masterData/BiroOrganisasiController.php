<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\BiroOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class BiroOrganisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BiroOrganisasi::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.biroOrganisasi.index');
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
                'nama' => ['required', Rule::unique('biro_organisasi')->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Biro Organisasi tidak boleh kosong',
                'nama.unique' => 'Nama Biro Organisasi sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $biroOrganisasi = new BiroOrganisasi();
        $biroOrganisasi->nama = $request->nama;
        $biroOrganisasi->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BiroOrganisasi  $biroOrganisasi
     * @return \Illuminate\Http\Response
     */
    public function show(BiroOrganisasi $biroOrganisasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BiroOrganisasi  $biroOrganisasi
     * @return \Illuminate\Http\Response
     */
    public function edit(BiroOrganisasi $biroOrganisasi)
    {
        return response()->json($biroOrganisasi);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BiroOrganisasi  $biroOrganisasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BiroOrganisasi $biroOrganisasi)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('biro_organisasi')->ignore($biroOrganisasi->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Biro Organisasi tidak boleh kosong',
                'nama.unique' => 'Nama Biro Organisasi sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $biroOrganisasi->nama = $request->nama;
        $biroOrganisasi->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BiroOrganisasi  $biroOrganisasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(BiroOrganisasi $biroOrganisasi)
    {
        $biroOrganisasi->delete();
        return response()->json(['status' => 'success']);
    }
}
