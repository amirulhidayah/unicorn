<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\Tahun;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TahunController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.masterData.tahun.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => ['required', Rule::unique('tahun')->withoutTrashed()],
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'tahun.unique' => 'Tahun sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request) {
                $tahun = new Tahun();
                $tahun->tahun = $request->tahun;
                $tahun->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }


        return response()->json(['status' => 'success']);
    }

    public function edit(Tahun $tahun)
    {
        return response()->json($tahun);
    }

    public function update(Request $request, Tahun $tahun)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => ['required', Rule::unique('tahun')->ignore($tahun->id)->withoutTrashed()],
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'tahun.unique' => 'Tahun sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($tahun, $request) {
                $tahun->tahun = $request->tahun;
                $tahun->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(Tahun $tahun)
    {
        try {
            DB::transaction(function () use ($tahun) {
                $tahun->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
