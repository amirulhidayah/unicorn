<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\KategoriSppLs;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KategoriSppLsController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.masterData.kategoriSppLs.index');
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
                'nama' => ['required', Rule::unique('kategori_spp_ls')->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama kategori tidak boleh kosong',
                'nama.unique' => 'Nama kategori sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request) {
                $kategoriSppLs = new KategoriSppLs();
                $kategoriSppLs->nama = $request->nama;
                $kategoriSppLs->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(KategoriSppLs $kategoriSppLs)
    {
        //
    }

    public function edit(KategoriSppLs $kategoriSppLs)
    {
        return response()->json($kategoriSppLs);
    }

    public function update(Request $request, KategoriSppLs $kategoriSppLs)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('kategori_spp_ls')->ignore($kategoriSppLs->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama kategori tidak boleh kosong',
                'nama.unique' => 'Nama kategori sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request, $kategoriSppLs) {
                $kategoriSppLs->nama = $request->nama;
                $kategoriSppLs->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(KategoriSppLs $kategoriSppLs)
    {
        try {
            DB::transaction(function () use ($kategoriSppLs) {
                $kategoriSppLs->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
