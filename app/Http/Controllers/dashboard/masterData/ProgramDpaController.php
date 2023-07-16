<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanDpa;
use App\Models\ProgramDpa;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramDpaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProgramDpa::orderBy('no_rek', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . url('master-data/kegiatan-dpa/' . $row->id) . '" class="btn btn-primary btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-eye"></i> Lihat Kegiatan</a><button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.programDpa.index');
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
                'no_rek' => ['required', Rule::unique('program_dpa')->withoutTrashed()],
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
                $programDpa = new ProgramDpa();
                $programDpa->nama = $request->nama;
                $programDpa->no_rek = $request->no_rek;
                $programDpa->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(ProgramDpa $programDpa)
    {
    }

    public function edit(ProgramDpa $programDpa)
    {
        return response()->json($programDpa);
    }

    public function update(Request $request, ProgramDpa $programDpa)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('program_dpa')->ignore($programDpa->id)->withoutTrashed()],
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
            DB::transaction(function () use ($programDpa, $request) {
                $programDpa->nama = $request->nama;
                $programDpa->no_rek = $request->no_rek;
                $programDpa->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(ProgramDpa $programDpa)
    {
        try {
            DB::transaction(function () use ($programDpa) {
                $programDpa->delete();
                KegiatanDpa::where('program_dpa_id', $programDpa->id)->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
