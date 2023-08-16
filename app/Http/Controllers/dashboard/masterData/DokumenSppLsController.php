<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppLs;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DokumenSppLsController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.masterData.dokumenSppLs.index');
    }

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

        try {
            DB::transaction(function () use ($request) {
                $daftarDokumenSppLs = new DaftarDokumenSppLs();
                $daftarDokumenSppLs->nama = $request->nama;
                $daftarDokumenSppLs->kategori = $request->kategori;
                $daftarDokumenSppLs->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function edit(DaftarDokumenSppLs $daftarDokumenSppLs, Request $request)
    {
        return response()->json($daftarDokumenSppLs);
    }

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

        try {
            DB::transaction(function () use ($request, $daftarDokumenSppLs) {
                $daftarDokumenSppLs->nama = $request->nama;
                $daftarDokumenSppLs->kategori = $request->kategori;
                $daftarDokumenSppLs->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(DaftarDokumenSppLs $daftarDokumenSppLs)
    {
        try {
            DB::transaction(function () use ($daftarDokumenSppLs) {
                $daftarDokumenSppLs->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
