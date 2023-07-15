<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SekretariatDaerahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('sekretariat_daerah')->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Biro Organisasi tidak boleh kosong',
                'nama.unique' => 'Nama Biro Organisasi sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $SekretariatDaerah = new SekretariatDaerah();
        $SekretariatDaerah->nama = $request->nama;
        $SekretariatDaerah->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SekretariatDaerah  $SekretariatDaerah
     * @return \Illuminate\Http\Response
     */
    public function show(SekretariatDaerah $SekretariatDaerah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SekretariatDaerah  $SekretariatDaerah
     * @return \Illuminate\Http\Response
     */
    public function edit(SekretariatDaerah $SekretariatDaerah)
    {
        return response()->json($SekretariatDaerah);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SekretariatDaerah  $SekretariatDaerah
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SekretariatDaerah $SekretariatDaerah)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required', Rule::unique('sekretariat_daerah')->ignore($SekretariatDaerah->id)->withoutTrashed()],
            ],
            [
                'nama.required' => 'Nama Biro Organisasi tidak boleh kosong',
                'nama.unique' => 'Nama Biro Organisasi sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $SekretariatDaerah->nama = $request->nama;
        $SekretariatDaerah->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SekretariatDaerah  $SekretariatDaerah
     * @return \Illuminate\Http\Response
     */
    public function destroy(SekretariatDaerah $SekretariatDaerah)
    {
        $SekretariatDaerah->delete();
        return response()->json(['status' => 'success']);
    }
}
