<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Exports\FormatImport;
use App\Http\Controllers\Controller;
use App\Imports\ImportSpd;
use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\SekretariatDaerah;
use App\Models\Spd;
use App\Models\Tahun;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SuratPenyediaanDanaController extends Controller
{
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'asc')->get();
        $sekretariatDaerah = SekretariatDaerah::orderBY('nama', 'asc')->get();
        $role = Auth::user()->role;

        return view('dashboard.pages.dpa.suratPenyediaanDana.index', compact('tahun', 'sekretariatDaerah', 'role'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => 'required',
                'sekretariat_daerah' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'sekretariat_daerah.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'jumlah_anggaran.required' => 'Jumlah anggaran tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $cekSpd = Spd::where('kegiatan_id', $request->kegiatan)->where('sekretariat_daerah_id', $request->sekretariat_daerah)->where('tahun_id', $request->tahun)->first();

        if ($cekSpd) {
            return response()->json(['status' => 'unique']);
        }

        try {
            DB::transaction(function () use ($request) {
                $spd = new Spd();
                $spd->kegiatan_id = $request->kegiatan;
                $spd->tahun_id = $request->tahun;
                $spd->sekretariat_daerah_id = $request->sekretariat_daerah;
                $spd->jumlah_anggaran = preg_replace("/[^0-9]/", "", $request->jumlah_anggaran);
                $spd->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(Spd $spd)
    {
        //
    }

    public function edit(Spd $spd)
    {
        $spd->kegiatan;
        return response()->json($spd);
    }

    public function update(Request $request, Spd $spd)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => 'required',
                'sekretariat_daerah' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'sekretariat_daerah.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'jumlah_anggaran.required' => 'TW1 tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(function () use ($request, $spd) {
                $spd->kegiatan_id = $request->kegiatan;
                $spd->tahun_id = $request->tahun;
                $spd->sekretariat_daerah_id = $request->sekretariat_daerah;
                $spd->jumlah_anggaran = preg_replace("/[^0-9]/", "", $request->jumlah_anggaran);
                $spd->save();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        $cekSpd = Spd::where('kegiatan_id', $request->kegiatan)->where('sekretariat_daerah_id', $request->sekretariat_daerah)->where('tahun_id', $request->tahun)->where('id', '!=', $spd->id)->first();

        if ($cekSpd) {
            return response()->json(['status' => 'unique']);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(Spd $spd)
    {
        try {
            DB::transaction(function () use ($spd) {
                $spd->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function getTabel(Request $request)
    {
        $role = Auth::user()->role;

        $tahun = $request->tahun;
        $sekretariatDaerahId = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;

        $array = $this->_dataSpd($sekretariatDaerahId, $tahun);
        return view('dashboard.components.widgets.tabelSpd', compact(['array']))->render();
    }

    private function _dataSpd($sekretariatDaerahId, $tahun)
    {
        $daftarSekretariatDaerah = SekretariatDaerah::where(function ($query) use ($sekretariatDaerahId) {
            if ($sekretariatDaerahId != "Semua") {
                $query->where('id', $sekretariatDaerahId);
            }
        })->orderBy('nama', 'asc')->get();

        $array = [];

        foreach ($daftarSekretariatDaerah as $sekretariatDaerah) {
            $daftarProgram = Program::with('kegiatan')->whereHas('kegiatan', function ($query) use ($sekretariatDaerah, $tahun) {
                $query->whereHas('spd', function ($query) use ($sekretariatDaerah, $tahun) {
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                    if ($tahun) {
                        $query->where('tahun_id', $tahun);
                    }
                });
            })->withTrashed()->get();

            $programData = [];
            foreach ($daftarProgram as $program) {
                $kegiatanData = [];
                $daftarKegiatan = Kegiatan::where('program_id', $program->id)->whereHas('spd', function ($query) use ($sekretariatDaerah, $tahun) {
                    $query->where('tahun_id', $tahun);
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                })->orderBy('no_rek', 'asc')->get();

                foreach ($daftarKegiatan as $kegiatan) {
                    $spd = Spd::where('sekretariat_daerah_id', $sekretariatDaerah->id)->where('tahun_id', $tahun)->where('kegiatan_id', $kegiatan->id)->first();
                    if ($spd) {
                        $kegiatanData[] = [
                            'id' => $spd->id,
                            'nama' => $kegiatan->nama,
                            'no_rek' => $kegiatan->no_rek,
                            'jumlah_anggaran' => $spd->jumlah_anggaran,
                        ];
                    }
                }

                $programData[] = [
                    'nama' => $program->nama,
                    'no_rek' => $program->no_rek,
                    'kegiatan' => $kegiatanData,
                ];
            }

            $array['data'][] = [
                'sekretariat_daerah' => $sekretariatDaerah->nama,
                'program' => $programData,
            ];
        }

        return $array;
    }

    public function formatImport()
    {
        return Excel::download(new FormatImport, 'Format Import SPD.xlsx');
    }

    public function import(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file_spd' => 'required|mimes:xlsx,xls',
                'tahun_import' => 'required',
            ],
            [
                'file_spd.required' => 'File DPA tidak boleh kosong',
                'file_spd.mimes' => 'File DPA harus berformat .xlsx atau .xls',
                'tahun_import.required' => 'Tahun tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $tahun = $request->tahun_import;
        Excel::import(new ImportSpd($tahun), $request->file('file_spd'));
        return response()->json(['status' => 'success']);
    }
}
