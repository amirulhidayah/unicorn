<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Program;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.masterData.program.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('program')->withoutTrashed()],
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
                $program = new Program();
                $program->nama = $request->nama;
                $program->no_rek = $request->no_rek;
                $program->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function edit(Program $program)
    {
        return response()->json($program);
    }

    public function update(Request $request, Program $program)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'no_rek' => ['required', Rule::unique('program')->ignore($program->id)->withoutTrashed()],
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
            DB::transaction(function () use ($program, $request) {
                $program->nama = $request->nama;
                $program->no_rek = $request->no_rek;
                $program->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(Program $program)
    {
        try {
            DB::transaction(function () use ($program) {
                $program->delete();
                Kegiatan::where('program_id', $program->id)->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
