<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppUp;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DokumenSppUpController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.masterData.dokumenSppUp.index');
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
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_up')->withoutTrashed()],
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
                $daftarDokumenSppUp = new DaftarDokumenSppUp();
                $daftarDokumenSppUp->nama = $request->nama;
                $daftarDokumenSppUp->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }


        return response()->json(['status' => 'success']);
    }

    public function show(DaftarDokumenSppUp $daftarDokumenSppUp)
    {
        //
    }

    public function edit(DaftarDokumenSppUp $daftarDokumenSppUp)
    {
        return response()->json($daftarDokumenSppUp);
    }

    public function update(Request $request, DaftarDokumenSppUp $daftarDokumenSppUp)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('daftar_dokumen_spp_up')->ignore($daftarDokumenSppUp->id)->withoutTrashed()],
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
            DB::transaction(function () use ($request, $daftarDokumenSppUp) {
                $daftarDokumenSppUp->nama = $request->nama;
                $daftarDokumenSppUp->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }


        return response()->json(['status' => 'success']);
    }

    public function destroy(DaftarDokumenSppUp $daftarDokumenSppUp)
    {
        try {
            DB::transaction(function () use ($daftarDokumenSppUp) {
                $daftarDokumenSppUp->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
