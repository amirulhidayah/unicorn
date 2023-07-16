<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\KegiatanSpp;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KegiatanSppController extends Controller
{
    public function index(Request $request)
    {
        $idProgram = $request->programSpp;
        if ($request->ajax()) {
            $data = KegiatanSpp::where('program_spp_id', $idProgram)->orderBy('no_rek', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.kegiatanSpp.index', compact('idProgram'));
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
                'nama' => 'required',
                'no_rek' => ['required', $request->no_rek ? Rule::unique('kegiatan_spp')->withoutTrashed() : ''],
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
                $kegiatanSpp = new KegiatanSpp();
                $kegiatanSpp->program_spp_id = $request->programSpp;
                $kegiatanSpp->nama = $request->nama;
                $kegiatanSpp->no_rek = $request->no_rek;
                $kegiatanSpp->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(KegiatanSpp $kegiatanSpp)
    {
        //
    }

    public function edit(Request $request)
    {
        $kegiatanSpp = KegiatanSpp::find($request->kegiatanSpp);
        return response()->json($kegiatanSpp);
    }

    public function update(Request $request)
    {
        $kegiatanSpp = KegiatanSpp::find($request->kegiatanSpp);
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('kegiatan_spp')->ignore($kegiatanSpp->id)->withoutTrashed()],
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
            DB::transaction(function () use ($request, $kegiatanSpp) {
                $kegiatanSpp->program_spp_id = $request->programSpp;
                $kegiatanSpp->nama = $request->nama;
                $kegiatanSpp->no_rek = $request->no_rek;
                $kegiatanSpp->save();
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
                KegiatanSpp::where('id', $request->kegiatanSpp)->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
