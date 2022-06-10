<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Exports\FormatImport;
use App\Http\Controllers\Controller;
use App\Imports\ImportSpd;
use App\Models\BiroOrganisasi;
use App\Models\Program;
use App\Models\Spd;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TabelDpaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'asc')->get();
        $biroOrganisasi = BiroOrganisasi::orderBY('nama', 'asc')->get();
        // $program = Program::with('kegiatan')->whereHas('kegiatan', function ($query) use ($biroOrganisasi) {
        //     $query->whereHas('spd', function ($query) use ($biroOrganisasi) {
        //         $query->where('biro_organisasi_id', $biroOrganisasi);
        //     });
        // })->get();

        return view('dashboard.pages.dpa.tabel.index', compact('tahun', 'biroOrganisasi'));
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
                'tahun' => 'required',
                'biro_organisasi' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'tw1' => 'required',
                'tw2' => 'required',
                'tw3' => 'required',
                'tw4' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'biro_organisasi.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'tw1.required' => 'TW1 tidak boleh kosong',
                'tw2.required' => 'TW2 tidak boleh kosong',
                'tw3.required' => 'TW3 tidak boleh kosong',
                'tw4.required' => 'TW4 tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $spd = new Spd();
        $spd->kegiatan_id = $request->kegiatan;
        $spd->tahun_id = $request->tahun;
        $spd->biro_organisasi_id = $request->biro_organisasi;
        $spd->tw1 = str_replace(".", "", $request->tw1);
        $spd->tw2 = str_replace(".", "", $request->tw2);
        $spd->tw3 = str_replace(".", "", $request->tw3);
        $spd->tw4 = str_replace(".", "", $request->tw4);
        $spd->save();

        return response()->json(['status' => 'success']);
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
    public function edit(Spd $spd)
    {
        $spd->kegiatan;
        return response()->json($spd);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spd $spd)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => 'required',
                'biro_organisasi' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'tw1' => 'required',
                'tw2' => 'required',
                'tw3' => 'required',
                'tw4' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'biro_organisasi.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'tw1.required' => 'TW1 tidak boleh kosong',
                'tw2.required' => 'TW2 tidak boleh kosong',
                'tw3.required' => 'TW3 tidak boleh kosong',
                'tw4.required' => 'TW4 tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $spd->kegiatan_id = $request->kegiatan;
        $spd->tahun_id = $request->tahun;
        $spd->biro_organisasi_id = $request->biro_organisasi;
        $spd->tw1 = str_replace(".", "", $request->tw1);
        $spd->tw2 = str_replace(".", "", $request->tw2);
        $spd->tw3 = str_replace(".", "", $request->tw3);
        $spd->tw4 = str_replace(".", "", $request->tw4);
        $spd->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spd $spd)
    {
        $spd->delete();

        return response()->json(['status' => 'success']);
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

    public function tabelDpa(Request $request)
    {
        $tahun = $request->tahun;
        $biroOrganisasi = Auth::user()->role != "Bendahara Pengeluaran" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;
        $daftarBiroOrganisasi = Spd::with('biroOrganisasi')->where('tahun_id', $tahun)
            ->where(function ($query) use ($biroOrganisasi) {
                if ($biroOrganisasi != "Semua") {
                    $query->where('biro_organisasi_id', $biroOrganisasi);
                }
            })
            ->whereHas('biroOrganisasi', function ($query) {
                $query->orderBy('nama', 'asc');
            })->groupBy('biro_organisasi_id')->get()->pluck('biroOrganisasi')->sortBy('nama');

        return view('dashboard.components.widgets.tabelSpd', compact(['daftarBiroOrganisasi', 'tahun']))->render();
    }
}
