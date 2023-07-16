<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppGu;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $data = DaftarDokumenSppGu::orderBy('created_at', 'desc')->get();
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

    public function create()
    {
        //
    }

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

        try {
            DB::transaction(function () use ($request) {
                $daftarDokumenSppGu = new DaftarDokumenSppGu();
                $daftarDokumenSppGu->nama = $request->nama;
                $daftarDokumenSppGu->kategori = $request->kategori;
                $daftarDokumenSppGu->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(DaftarDokumenSppGu $daftarDokumenSppGu)
    {
        //
    }

    public function edit(DaftarDokumenSppGu $daftarDokumenSppGu)
    {
        return response()->json($daftarDokumenSppGu);
    }

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

        try {
            DB::transaction(function () use ($request, $daftarDokumenSppGu) {
                $daftarDokumenSppGu->nama = $request->nama;
                $daftarDokumenSppGu->kategori = $request->kategori;
                $daftarDokumenSppGu->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(DaftarDokumenSppGu $daftarDokumenSppGu)
    {
        try {
            DB::transaction(function () use ($daftarDokumenSppGu) {
                $daftarDokumenSppGu->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
