<?php

namespace App\Http\Controllers\dashboard\spd;

use App\Exports\FormatImport;
use App\Http\Controllers\Controller;
use App\Imports\ImportSpd;
use App\Models\Program;
use App\Models\Spd;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class SpdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahunId = '8fef08db-e1bf-4a1f-8bd2-9809d5e60426';
        $daftarBiroOrganisasi = Spd::with('biroOrganisasi')->where('tahun_id', $tahunId)->whereHas('biroOrganisasi', function ($query) {
            $query->orderBy('nama', 'asc');
        })->groupBy('biro_organisasi_id')->get()->pluck('biroOrganisasi')->sortBy('nama');
        $tahun = Tahun::orderBy('tahun', 'asc')->get();
        // $program = Program::with('kegiatan')->whereHas('kegiatan', function ($query) use ($biroOrganisasi) {
        //     $query->whereHas('spd', function ($query) use ($biroOrganisasi) {
        //         $query->where('biro_organisasi_id', $biroOrganisasi);
        //     });
        // })->get();

        return view('dashboard.pages.spd.index', compact('tahun', 'daftarBiroOrganisasi', 'tahunId'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function formatImport()
    {
        return Excel::download(new FormatImport, 'Format Import SPD.xlsx');
    }

    public function importSpd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file_spd' => 'required|mimes:xlsx,xls',
                'tahun' => 'required',
            ],
            [
                'file_spd.required' => 'File SPD tidak boleh kosong',
                'file_spd.mimes' => 'File SPD harus berformat .xlsx atau .xls',
                'tahun.required' => 'Tahun tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        // return response()->json($request->all());

        $tahun = $request->tahun;
        Excel::import(new ImportSpd($tahun), $request->file('file_spd'));
        return response()->json(['status' => 'success']);
    }

    public function getSpd(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $kegiatan = $request->kegiatan;
        $tw = $request->tw;
        $biroOrganisasi = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;

        $spd = Spd::where('kegiatan_id', $kegiatan)->where('biro_organisasi_id', $biroOrganisasi)->where('tahun_id', $tahun)->first();
        if ($tw == 1) {
            $jumlahAnggaran = $spd->tw1;
        } else if ($tw == 2) {
            $jumlahAnggaran = $spd->tw2;
        } else if ($tw == 3) {
            $jumlahAnggaran = $spd->tw3;
        } else if ($tw == 4) {
            $jumlahAnggaran = $spd->tw4;
        }

        return response()->json([
            'jumlah_anggaran' => $jumlahAnggaran
        ]);
    }
}
