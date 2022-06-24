<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppTu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DokumenSppTuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DaftarDokumenSppTu::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.dokumenSppTu.index');
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
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_tu')->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'nama.unique' => 'Nama Dokumen sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $daftarDokumenSppTu = new DaftarDokumenSppTu();
        $daftarDokumenSppTu->nama = $request->nama;
        $daftarDokumenSppTu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DaftarDokumenSppTu  $daftarDokumenSppTu
     * @return \Illuminate\Http\Response
     */
    public function show(DaftarDokumenSppTu $daftarDokumenSppTu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DaftarDokumenSppTu  $daftarDokumenSppTu
     * @return \Illuminate\Http\Response
     */
    public function edit(DaftarDokumenSppTu $daftarDokumenSppTu)
    {
        return response()->json($daftarDokumenSppTu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DaftarDokumenSppTu  $daftarDokumenSppTu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DaftarDokumenSppTu $daftarDokumenSppTu)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_tu')->ignore($daftarDokumenSppTu->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'nama.unique' => 'Nama Dokumen sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $daftarDokumenSppTu->nama = $request->nama;
        $daftarDokumenSppTu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DaftarDokumenSppTu  $daftarDokumenSppTu
     * @return \Illuminate\Http\Response
     */
    public function destroy(DaftarDokumenSppTu $daftarDokumenSppTu)
    {
        $daftarDokumenSppTu->delete();
        return response()->json(['status' => 'success']);
    }
}
