<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\KegiatanDpa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KegiatanDpaController extends Controller
{
    public function index(Request $request)
    {
        $idProgram = $request->program;
        if ($request->ajax()) {
            $data = KegiatanDpa::where('program_dpa_id', $idProgram)->orderBy('no_rek', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.kegiatanDpa.index', compact('idProgram'));
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

        $kegiatanDpa = new KegiatanDpa();
        $kegiatanDpa->program_dpa_id = $request->program;
        $kegiatanDpa->nama = $request->nama;
        $kegiatanDpa->no_rek = $request->no_rek;
        $kegiatanDpa->save();

        return response()->json(['status' => 'success']);
    }

    public function show(KegiatanDpa $kegiatanDpa)
    {
        //
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

        $kegiatanDpa->program_dpa_id = $request->program;
        $kegiatanDpa->nama = $request->nama;
        $kegiatanDpa->no_rek = $request->no_rek;
        $kegiatanDpa->save();

        return response()->json(['status' => 'success']);
    }

    public function destroy(Request $request)
    {
        KegiatanDpa::where('id', $request->kegiatan_dpa)->delete();
        return response()->json(['status' => 'success']);
    }
}
