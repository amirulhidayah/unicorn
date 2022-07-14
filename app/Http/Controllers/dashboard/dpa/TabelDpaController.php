<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Exports\FormatImport;
use App\Exports\TabelDpaExport;
use App\Http\Controllers\Controller;
use App\Imports\ImportSpd;
use App\Models\BiroOrganisasi;
use App\Models\Program;
use App\Models\Spd;
use App\Models\SppGu;
use App\Models\SppLs;
use App\Models\Tahun;
use Carbon\Carbon;
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
                'jumlah_anggaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'biro_organisasi.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'jumlah_anggaran.required' => 'Jumlah anggaran tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $cekSpd = Spd::where('kegiatan_id', $request->kegiatan)->where('biro_organisasi_id', $request->biro_organisasi)->where('tahun_id', $request->tahun)->first();

        if ($cekSpd) {
            return response()->json(['status' => 'unique']);
        }

        $spd = new Spd();
        $spd->kegiatan_id = $request->kegiatan;
        $spd->tahun_id = $request->tahun;
        $spd->biro_organisasi_id = $request->biro_organisasi;
        $spd->jumlah_anggaran = preg_replace("/[^0-9]/", "", $request->jumlah_anggaran);
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
                'jumlah_anggaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'biro_organisasi.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'jumlah_anggaran.required' => 'TW1 tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $cekSpd = Spd::where('kegiatan_id', $request->kegiatan)->where('biro_organisasi_id', $request->biro_organisasi)->where('tahun_id', $request->tahun)->where('id', '!=', $spd->id)->first();

        if ($cekSpd) {
            return response()->json(['status' => 'unique']);
        }

        $spd->kegiatan_id = $request->kegiatan;
        $spd->tahun_id = $request->tahun;
        $spd->biro_organisasi_id = $request->biro_organisasi;
        $spd->jumlah_anggaran = preg_replace("/[^0-9]/", "", $request->jumlah_anggaran);
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
                'file_spd.required' => 'File DPA tidak boleh kosong',
                'file_spd.mimes' => 'File DPA harus berformat .xlsx atau .xls',
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
        $bulan = $request->bulan;

        $biroOrganisasi = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;

        $spd = Spd::where('kegiatan_id', $kegiatan)->where('biro_organisasi_id', $biroOrganisasi)->where('tahun_id', $tahun)->first();

        $jumlahAnggaran = $spd->jumlah_anggaran;

        $sppLs = SppLs::where('biro_organisasi_id', $biroOrganisasi)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun)
            ->where('kegiatan_id', $kegiatan)
            ->where('status_validasi_akhir', 1);

        $sppGu = SppGu::where('biro_organisasi_id', $biroOrganisasi)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun)
            ->where('kegiatan_id', $kegiatan)
            ->where('status_validasi_akhir', 1);

        if ($bulan == 'Januari') {
            $sppLs = $sppLs->where('bulan', 'Januari');
            $sppGu = $sppGu->where('bulan', 'Januari');
        } else if ($bulan == 'Februari') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari']);
        } else if ($bulan == 'Maret') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret']);
        } else if ($bulan == 'April') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April']);
        } else if ($bulan == 'Mei') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei']);
        } else if ($bulan == 'Juni') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni']);
        } else if ($bulan == 'Juli') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli']);
        } else if ($bulan == 'Agustus') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus']);
        } else if ($bulan == 'September') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September']);
        } else if ($bulan == 'Oktober') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober']);
        } else if ($bulan == 'November') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November']);
        } else if ($bulan == 'Desember') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
            $sppGu = $sppGu->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
        }

        $sppLs = $sppLs->sum('anggaran_digunakan');
        $sppGu = $sppGu->sum('anggaran_digunakan');

        $totalSpp = ($sppLs + $sppGu);
        $jumlahAnggaran = ($spd->jumlah_anggaran - $totalSpp);

        return response()->json([
            'jumlah_anggaran' => $jumlahAnggaran,
        ]);
    }

    public function tabelDpa(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $biroOrganisasi = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;
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

    public function exportSpd(Request $request)
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

        $tanggal = Carbon::parse(Carbon::now())->translatedFormat('d F Y');

        return Excel::download(new TabelDpaExport($daftarBiroOrganisasi, $tahun), "Export" . "-" . $tanggal . "-" . rand(1, 9999) . '.xlsx');

        // return view('dashboard.components.widgets.tabelSpd', compact(['daftarBiroOrganisasi', 'tahun']))->render();
    }
}
