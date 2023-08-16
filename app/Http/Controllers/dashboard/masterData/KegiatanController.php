<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $idProgram = $request->program;
        return view('dashboard.pages.masterData.kegiatan.index', compact('idProgram'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('kegiatan')->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'no_rek.required' => 'Nomor Rekening Kegiatan SKPD tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening Kegiatan SKPD sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request) {
                $kegiatan = new Kegiatan();
                $kegiatan->program_id = $request->program;
                $kegiatan->nama = $request->nama;
                $kegiatan->no_rek = $request->no_rek;
                $kegiatan->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function edit(Request $request)
    {
        $kegiatan = Kegiatan::find($request->kegiatan);
        return response()->json($kegiatan);
    }

    public function update(Request $request)
    {
        $kegiatan = Kegiatan::find($request->kegiatan);
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('kegiatan')->ignore($kegiatan->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Dokumen tidak boleh kosong',
                'no_rek.required' => 'Nomor Rekening Kegiatan SKPD tidak boleh kosong',
                'no_rek.unique' => 'Nomor Rekening Kegiatan SKPD sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request, $kegiatan) {
                $kegiatan->program_id = $request->program;
                $kegiatan->nama = $request->nama;
                $kegiatan->no_rek = $request->no_rek;
                $kegiatan->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                Kegiatan::where('id', $request->kegiatan)->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
