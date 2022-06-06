<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppGu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DokumenSppGuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DaftarDokumenSppGu::orderBy('created_at', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.dokumenSppGu.index');
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
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_gu')->withoutTrashed()],
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

        $daftarDokumenSppGu = new DaftarDokumenSppGu();
        $daftarDokumenSppGu->nama = $request->nama;
        $daftarDokumenSppGu->kategori = $request->kategori;
        $daftarDokumenSppGu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DaftarDokumenSppGu  $daftarDokumenSppGu
     * @return \Illuminate\Http\Response
     */
    public function show(DaftarDokumenSppGu $daftarDokumenSppGu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DaftarDokumenSppGu  $daftarDokumenSppGu
     * @return \Illuminate\Http\Response
     */
    public function edit(DaftarDokumenSppGu $daftarDokumenSppGu)
    {
        return response()->json($daftarDokumenSppGu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DaftarDokumenSppGu  $daftarDokumenSppGu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DaftarDokumenSppGu $daftarDokumenSppGu)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_gu')->ignore($daftarDokumenSppGu->id)->withoutTrashed()],
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

        $daftarDokumenSppGu->nama = $request->nama;
        $daftarDokumenSppGu->kategori = $request->kategori;
        $daftarDokumenSppGu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DaftarDokumenSppGu  $daftarDokumenSppGu
     * @return \Illuminate\Http\Response
     */
    public function destroy(DaftarDokumenSppGu $daftarDokumenSppGu)
    {
        $daftarDokumenSppGu->delete();
        return response()->json(['status' => 'success']);
    }
}
