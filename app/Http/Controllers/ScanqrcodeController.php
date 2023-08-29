<?php

namespace App\Http\Controllers;

use App\Models\DokumenSppGu;
use App\Models\SpjGu;
use App\Models\SppGu;
use App\Models\SppLs;
use App\Models\SppTu;
use App\Models\SppUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanqrcodeController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.scanqrcode');
    }

    public function getData(Request $request)
    {
        $kode = explode('-', $request->kode);
        $data = null;
        $html = null;
        if ($kode) {
            $dokumen = $kode[0];
            $id = substr_replace($request->kode, '', 0,  strlen($dokumen) + 1);
            if ($dokumen == "SPP UP") {
                $sppUp = SppUp::where('id', $id)->where(function ($query) {
                    if (!in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) {
                        $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                    } else if (Auth::user()->role == 'Operator SPM') {
                        $query->where('status_validasi_asn', 1);
                        $query->where('status_validasi_ppk', 1);
                        $query->where('status_validasi_akhir', 1);
                    }
                })->where('status_validasi_akhir', 1)->first();
                if ($sppUp) {
                    return response()->json([
                        'status' => 'success',
                        'html' => $this->_sppUp($sppUp)
                    ]);
                }
            }

            if ($dokumen == "SPP TU") {
                $sppTu = SppTu::where('id', $id)->where(function ($query) {
                    if (!in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) {
                        $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                    } else if (Auth::user()->role == 'Operator SPM') {
                        $query->where('status_validasi_asn', 1);
                        $query->where('status_validasi_ppk', 1);
                        $query->where('status_validasi_akhir', 1);
                    }
                })->where('status_validasi_akhir', 1)->first();
                if ($sppTu) {
                    return response()->json([
                        'status' => 'success',
                        'html' => $this->_sppTu($sppTu)
                    ]);
                }
            }

            if ($dokumen == "SPP LS") {
                $sppLs = SppLs::where('id', $id)->where(function ($query) {
                    if (!in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) {
                        $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                    } else if (Auth::user()->role == 'Operator SPM') {
                        $query->where('status_validasi_asn', 1);
                        $query->where('status_validasi_ppk', 1);
                        $query->where('status_validasi_akhir', 1);
                    }
                })->where('status_validasi_akhir', 1)->first();
                if ($sppLs) {
                    return response()->json([
                        'status' => 'success',
                        'html' => $this->_sppLs($sppLs)
                    ]);
                }
            }

            if ($dokumen == "SPP GU") {
                $sppGu = SppGu::where('id', $id)->whereHas('spjGu', function ($query) {
                    $query->where(function ($query) {
                        if (!in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) {
                            $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                        } else if (Auth::user()->role == 'Operator SPM') {
                            $query->where('status_validasi_asn', 1);
                            $query->where('status_validasi_ppk', 1);
                            $query->where('status_validasi_akhir', 1);
                        }
                    });
                })->where('status_validasi_akhir', 1)->first();
                if ($sppGu) {
                    return response()->json([
                        'status' => 'success',
                        'html' => $this->_sppGu($sppGu)
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'error',
        ]);
    }

    private function _sppUp($sppUp)
    {
        $tipe = 'spp_up';
        $totalJumlahAnggaran = 0;
        $programDanKegiatan = [];
        $totalProgramDanKegiatan = [];

        foreach ($sppUp->kegiatanSppUp as $kegiatanSppUp) {
            $program = $kegiatanSppUp->kegiatan->program->nama . ' (' . $kegiatanSppUp->kegiatan->program->no_rek . ')';
            $kegiatan = $kegiatanSppUp->kegiatan->nama . ' (' . $kegiatanSppUp->kegiatan->no_rek . ')';
            $jumlahAnggaran = $kegiatanSppUp->jumlah_anggaran;

            $programDanKegiatan[] = [
                'program' => $program,
                'kegiatan' => $kegiatan,
                'jumlah_anggaran' => $jumlahAnggaran,
            ];

            $totalJumlahAnggaran += $jumlahAnggaran;
        }

        $totalProgramDanKegiatan = [
            'total_jumlah_anggaran' => $totalJumlahAnggaran,
        ];

        return view('dashboard.components.hasilScan.sppUp', compact(['sppUp', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']))->render();
    }

    private function _sppTu($sppTu)
    {
        $tipe = 'spp_tu';
        $totalJumlahAnggaran = 0;
        $programDanKegiatan = [];
        $totalProgramDanKegiatan = [];

        foreach ($sppTu->kegiatanSppTu as $kegiatanSppTu) {
            $program = $kegiatanSppTu->kegiatan->program->nama . ' (' . $kegiatanSppTu->kegiatan->program->no_rek . ')';
            $kegiatan = $kegiatanSppTu->kegiatan->nama . ' (' . $kegiatanSppTu->kegiatan->no_rek . ')';
            $jumlahAnggaran = $kegiatanSppTu->jumlah_anggaran;

            $programDanKegiatan[] = [
                'program' => $program,
                'kegiatan' => $kegiatan,
                'jumlah_anggaran' => $jumlahAnggaran,
            ];

            $totalJumlahAnggaran += $jumlahAnggaran;
        }

        $totalProgramDanKegiatan = [
            'total_jumlah_anggaran' => $totalJumlahAnggaran,
        ];

        return view('dashboard.components.hasilScan.sppTu', compact(['sppTu', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']))->render();
    }

    private function _sppLs($sppLs)
    {
        $tipe = 'spp_ls';
        $totalJumlahAnggaran = 0;
        $totalAnggaranDigunakan = 0;
        $totalSisaAnggaran = 0;
        $programDanKegiatan = [];
        $totalProgramDanKegiatan = [];

        foreach ($sppLs->kegiatanSppLs as $kegiatanSppLs) {
            $program = $kegiatanSppLs->kegiatan->program->nama . ' (' . $kegiatanSppLs->kegiatan->program->no_rek . ')';
            $kegiatan = $kegiatanSppLs->kegiatan->nama . ' (' . $kegiatanSppLs->kegiatan->no_rek . ')';
            $jumlahAnggaran = jumlah_anggaran($sppLs->sekretariat_daerah_id, $kegiatanSppLs->kegiatan_id, $sppLs->bulan_id, $sppLs->tahun_id, $sppLs->id);
            $anggaranDigunakan = $kegiatanSppLs->anggaran_digunakan;
            $sisaAnggaran = $jumlahAnggaran - $anggaranDigunakan;

            $programDanKegiatan[] = [
                'program' => $program,
                'kegiatan' => $kegiatan,
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

        return view('dashboard.components.hasilScan.sppLs', compact(['sppLs', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']))->render();
    }

    private function _sppGu($sppGu)
    {
        $tipe = 'spp_gu';
        $spjGu = SpjGu::where('id', $sppGu->spj_gu_id)->first();

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

        return view('dashboard.components.hasilScan.sppGu', compact(['sppGu', 'spjGu', 'totalProgramDanKegiatan', 'tipe', 'programDanKegiatan']))->render();
    }
}
