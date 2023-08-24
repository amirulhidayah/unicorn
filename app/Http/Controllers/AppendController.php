<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\SpjGu;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppendController extends Controller
{
    public function spp()
    {
        $nameFileDokumen = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $classDokumen = 'file_dokumen';
        $classNama = 'nama_file';
        $class = 'col-4';
        $html = view('dashboard.components.dynamicForm.spp', compact(['nameFileDokumen', 'classDokumen', 'classNama', 'class']))->render();
        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function sppLs(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $id = $request->id;
        $daftarProgram = [];

        try {
            if ($tahun && $sekretariatDaerah && $bulan) {
                $daftarProgram = Program::with(['kegiatan'])->whereHas('kegiatan', function ($query) use ($tahun, $sekretariatDaerah) {
                    $query->whereHas('spd', function ($query) use ($tahun, $sekretariatDaerah) {
                        if ($tahun) {
                            $query->where('tahun_id', $tahun);
                        }
                        if ($sekretariatDaerah) {
                            $query->where('sekretariat_daerah_id', $sekretariatDaerah);
                        }
                    });
                })->orderBy('no_rek', 'asc')->get();
            }

            $dataKey = Str::random(5) . rand(111, 999) . Str::random(5);
            $html = view('dashboard.components.appends.sppLs', compact(['daftarProgram', 'dataKey']))->render();

            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
        } catch (QueryException $error) {
            return throw new Exception($error);
        }
    }

    public function spjGu(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $id = $request->id;
        $daftarProgram = [];

        try {
            if ($tahun && $sekretariatDaerah && $bulan) {
                $daftarProgram = Program::with(['kegiatan'])->whereHas('kegiatan', function ($query) use ($tahun, $sekretariatDaerah) {
                    $query->whereHas('spd', function ($query) use ($tahun, $sekretariatDaerah) {
                        if ($tahun) {
                            $query->where('tahun_id', $tahun);
                        }
                        if ($sekretariatDaerah) {
                            $query->where('sekretariat_daerah_id', $sekretariatDaerah);
                        }
                    });
                })->orderBy('no_rek', 'asc')->get();
            }

            $dataKey = Str::random(5) . rand(111, 999) . Str::random(5);
            $html = view('dashboard.components.appends.spjGu', compact(['daftarProgram', 'dataKey']))->render();

            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
        } catch (QueryException $error) {
            return throw new Exception($error);
        }
    }

    public function sppGu(Request $request)
    {
        $id = $request->id;
        try {
            $spjGu = SpjGu::where('id', $id)->first();
            if ($spjGu) {
                $totalJumlahAnggaran = 0;
                $totalAnggaranDigunakan = 0;
                $totalSisaAnggaran = 0;
                $programDanKegiatan = [];
                $totalProgramDanKegiatan = [];

                foreach ($spjGu->kegiatanSpjGu as $kegiatanSpjGu) {
                    $program = $kegiatanSpjGu->kegiatan->program->nama . ' (' . $kegiatanSpjGu->kegiatan->program->no_rek . ')';
                    $kegiatan = $kegiatanSpjGu->kegiatan->nama . ' (' . $kegiatanSpjGu->kegiatan->no_rek . ')';
                    $jumlahAnggaran = jumlah_anggaran($spjGu->sekretariat_daerah_id, $kegiatanSpjGu->kegiatan_id, $spjGu->bulan_id, $spjGu->tahun_id, $spjGu->id);
                    $anggaranDigunakan = $kegiatanSpjGu->anggaran_digunakan;
                    $sisaAnggaran = $jumlahAnggaran - $anggaranDigunakan;

                    $programDanKegiatan[] = [
                        'program' => $program,
                        'kegiatan' => $kegiatan,
                        'dokumen' => $kegiatanSpjGu->dokumen,
                        'jumlah_anggaran' => $jumlahAnggaran,
                        'anggaran_digunakan' => $anggaranDigunakan,
                        'sisa_anggaran' => $sisaAnggaran
                    ];

                    $totalJumlahAnggaran += $jumlahAnggaran;
                    $totalAnggaranDigunakan += $anggaranDigunakan;
                    $totalSisaAnggaran += $sisaAnggaran;
                }

                $totalProgramDanKegiatan = [
                    'total_jumlah_anggaran' => $totalJumlahAnggaran,
                    'total_anggaran_digunakan' => $totalAnggaranDigunakan,
                    'total_sisa_anggaran' => $totalSisaAnggaran,
                ];

                $html = view('dashboard.components.appends.sppGu', compact(['spjGu', 'programDanKegiatan', 'totalProgramDanKegiatan']))->render();
                return response()->json([
                    'status' => 'success',
                    'html' => $html
                ]);
            }

            return response()->json([
                'status' => 'error',
            ]);
        } catch (QueryException $error) {
            return throw new Exception($error);
        }
    }

    public function sppTu(Request $request)
    {
        $role = Auth::user()->role;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $daftarProgram = [];

        try {
            $daftarProgram = Program::with(['kegiatan'])->whereHas('kegiatan')->orderBy('no_rek', 'asc')->get();

            $dataKey = Str::random(5) . rand(111, 999) . Str::random(5);
            $html = view('dashboard.components.appends.sppTu', compact(['daftarProgram', 'dataKey']))->render();

            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
        } catch (QueryException $error) {
            return throw new Exception($error);
        }
    }
}
