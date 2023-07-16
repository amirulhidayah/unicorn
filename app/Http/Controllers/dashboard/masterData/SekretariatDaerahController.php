<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SekretariatDaerahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SekretariatDaerah::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="btn-edit" class="btn btn-warning btn-sm mr-1" value="' . $row->id . '" ><i class="fas fa-edit"></i> Ubah</button><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.sekretariatDaerah.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('sekretariat_daerah')->withoutTrashed()],
            ],
            [
                'nama.required' => 'Sekretariat Daerah tidak boleh kosong',
                'nama.unique' => 'Sekretariat Daerah sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request) {
                $sekretariatDaerah = new SekretariatDaerah();
                $sekretariatDaerah->nama = $request->nama;
                $sekretariatDaerah->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function edit(SekretariatDaerah $sekretariatDaerah)
    {
        return response()->json($sekretariatDaerah);
    }

    public function update(Request $request, SekretariatDaerah $sekretariatDaerah)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('sekretariat_daerah')->ignore($sekretariatDaerah->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Sekretariat Daerah tidak boleh kosong',
                'nama.unique' => 'Sekretariat Daerah sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request, $sekretariatDaerah) {
                $sekretariatDaerah->nama = $request->nama;
                $sekretariatDaerah->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(SekretariatDaerah $sekretariatDaerah)
    {
        try {
            DB::transaction(function () use ($sekretariatDaerah) {
                $sekretariatDaerah->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
