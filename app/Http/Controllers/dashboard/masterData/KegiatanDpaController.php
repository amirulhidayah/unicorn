<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\KegiatanDpa;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KegiatanDpaController extends Controller
{
    public function index(Request $request)
    {
        $idProgram = $request->program;
        return view('dashboard.pages.masterData.kegiatanDpa.index', compact('idProgram'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('kegiatan_dpa')->withoutTrashed()],
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
                $kegiatanDpa = new KegiatanDpa();
                $kegiatanDpa->program_dpa_id = $request->program;
                $kegiatanDpa->nama = $request->nama;
                $kegiatanDpa->no_rek = $request->no_rek;
                $kegiatanDpa->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function edit(Request $request)
    {
        $kegiatanDpa = KegiatanDpa::find($request->kegiatan_dpa);
        return response()->json($kegiatanDpa);
    }

    public function update(Request $request)
    {
        $kegiatanDpa = KegiatanDpa::find($request->kegiatan_dpa);
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('kegiatan_dpa')->ignore($kegiatanDpa->id)->withoutTrashed()],
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
            DB::transaction(function () use ($request, $kegiatanDpa) {
                $kegiatanDpa->program_dpa_id = $request->program;
                $kegiatanDpa->nama = $request->nama;
                $kegiatanDpa->no_rek = $request->no_rek;
                $kegiatanDpa->save();
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
                KegiatanDpa::where('id', $request->kegiatan_dpa)->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
