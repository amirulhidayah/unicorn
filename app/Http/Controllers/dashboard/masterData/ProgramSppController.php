<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\KegiatanSpp;
use App\Models\ProgramSpp;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramSppController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.masterData.programSpp.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', $request->no_rek ? Rule::unique('program_spp')->withoutTrashed() : ''],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'no_rek.required' => 'Nomor Rekening tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request) {
                $programSpp = new ProgramSpp();
                $programSpp->nama = $request->nama;
                $programSpp->no_rek = $request->no_rek;
                $programSpp->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function edit(ProgramSpp $programSpp)
    {
        return response()->json($programSpp);
    }

    public function update(Request $request, ProgramSpp $programSpp)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('program_spp')->ignore($programSpp->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'no_rek.required' => 'Nomor Rekening tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request, $programSpp) {
                $programSpp->nama = $request->nama;
                $programSpp->no_rek = $request->no_rek;
                $programSpp->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(ProgramSpp $programSpp)
    {
        try {
            DB::transaction(function () use ($programSpp) {
                $programSpp->delete();
                KegiatanSpp::where('program_spp_id', $programSpp->id)->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
