<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppTu;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DokumenSppTuController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.masterData.dokumenSppTu.index');
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

        try {
            DB::transaction(function () use ($request) {
                $daftarDokumenSppTu = new DaftarDokumenSppTu();
                $daftarDokumenSppTu->nama = $request->nama;
                $daftarDokumenSppTu->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(DaftarDokumenSppTu $daftarDokumenSppTu)
    {
        //
    }

    public function edit(DaftarDokumenSppTu $daftarDokumenSppTu)
    {
        return response()->json($daftarDokumenSppTu);
    }

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

        try {
            DB::transaction(function () use ($request, $daftarDokumenSppTu) {
                $daftarDokumenSppTu->nama = $request->nama;
                $daftarDokumenSppTu->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(DaftarDokumenSppTu $daftarDokumenSppTu)
    {
        try {
            DB::transaction(function () use ($daftarDokumenSppTu) {
                $daftarDokumenSppTu->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
