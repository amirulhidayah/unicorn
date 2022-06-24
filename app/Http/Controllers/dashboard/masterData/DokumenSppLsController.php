<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppLs;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DokumenSppLsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DaftarDokumenSppLs::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.dokumenSppLs.index');
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
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_ls')->withoutTrashed()],
                'kategori' => 'required',
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'nama.unique' => 'Nama Dokumen sudah ada',
                'kategori.required' => 'Kategori Dokumen tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $daftarDokumenSppLs = new DaftarDokumenSppLs();
        $daftarDokumenSppLs->nama = $request->nama;
        $daftarDokumenSppLs->kategori = $request->kategori;
        $daftarDokumenSppLs->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DaftarDokumenSppLs  $daftarDokumenSppLs
     * @return \Illuminate\Http\Response
     */
    public function show(DaftarDokumenSppLs $daftarDokumenSppLs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DaftarDokumenSppLs  $daftarDokumenSppLs
     * @return \Illuminate\Http\Response
     */
    public function edit(DaftarDokumenSppLs $daftarDokumenSppLs, Request $request)
    {
        return response()->json($daftarDokumenSppLs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DaftarDokumenSppLs  $daftarDokumenSppLs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DaftarDokumenSppLs $daftarDokumenSppLs)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_ls')->ignore($daftarDokumenSppLs->id)->withoutTrashed()],
                'kategori' => 'required',
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'nama.unique' => 'Nama Dokumen sudah ada',
                'kategori.required' => 'Kategori Dokumen tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $daftarDokumenSppLs->nama = $request->nama;
        $daftarDokumenSppLs->kategori = $request->kategori;
        $daftarDokumenSppLs->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DaftarDokumenSppLs  $daftarDokumenSppLs
     * @return \Illuminate\Http\Response
     */
    public function destroy(DaftarDokumenSppLs $daftarDokumenSppLs)
    {
        $daftarDokumenSppLs->delete();
        return response()->json(['status' => 'success']);
    }
}
