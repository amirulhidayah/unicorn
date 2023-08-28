<?php

namespace App\Http\Controllers;

use App\Models\SpjGu;
use App\Models\SppGu;
use App\Models\SppLs;
use App\Models\SppTu;
use App\Models\SppUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $sppUp = $this->sppUp();
        $sppTu = $this->sppTu();
        $sppLs = $this->sppLs();
        $spjGu = $this->spjGu();
        $sppGu = $this->sppGu();
        return view('dashboard.pages.dashboard', compact(['sppUp', 'sppTu', 'sppLs', 'spjGu', 'sppGu']));
    }

    private function sppUp()
    {
        $totalDokumen = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            } else if (Auth::user()->role == 'Operator SPM') {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
            }
        })->count();

        $belumProses = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }
            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else if (Auth::user()->role == "Operator SPM") {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
                $query->whereNull('dokumen_spm');
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 0)->orWhere('status_validasi_ppk', 0)->orWhere('status_validasi_akhir', 0)->orWhere('dokumen_spm', null)->orWhere('dokumen_arsip_sp2d', null);
                });
            }
        })->count();

        $ditolak = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 2);
                    $query->orWhere('status_validasi_ppk', 2);
                });
            }
        })->count();

        $selesai = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "Operator SPM") {
                $query->whereNotNull('dokumen_spm');
            }

            $query->where('status_validasi_akhir', 1);
        })->count();

        return [
            'totalDokumen' => $totalDokumen,
            'belumProses' => $belumProses,
            'ditolak' => $ditolak,
            'selesai' => $selesai
        ];
    }

    private function sppTu()
    {
        $totalDokumen = SppTu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            } else if (Auth::user()->role == 'Operator SPM') {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
            }
        })->count();

        $belumProses = SppTu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else if (Auth::user()->role == "Operator SPM") {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
                $query->whereNull('dokumen_spm');
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 0)->orWhere('status_validasi_ppk', 0)->orWhere('status_validasi_akhir', 0)->orWhere('dokumen_spm', null)->orWhere('dokumen_arsip_sp2d', null);
                });
            }
        })->count();

        $ditolak = SppTu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 2);
                    $query->orWhere('status_validasi_ppk', 2);
                });
            }
        })->count();

        $selesai = SppTu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "Operator SPM") {
                $query->whereNotNull('dokumen_spm');
            }

            $query->where('status_validasi_akhir', 1);
        })->count();

        return [
            'totalDokumen' => $totalDokumen,
            'belumProses' => $belumProses,
            'ditolak' => $ditolak,
            'selesai' => $selesai
        ];
    }

    private function sppLs()
    {
        $totalDokumen = SppLs::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            } else if (Auth::user()->role == 'Operator SPM') {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
            }
        })->count();

        $belumProses = SppLs::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else if (Auth::user()->role == "Operator SPM") {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
                $query->whereNull('dokumen_spm');
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 0)->orWhere('status_validasi_ppk', 0)->orWhere('status_validasi_akhir', 0)->orWhere('dokumen_spm', null)->orWhere('dokumen_arsip_sp2d', null);
                });
            }
        })->count();

        $ditolak = SppLs::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 2);
                    $query->orWhere('status_validasi_ppk', 2);
                });
            }
        })->count();

        $selesai = SppLs::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "Operator SPM") {
                $query->whereNotNull('dokumen_spm');
            }

            $query->where('status_validasi_akhir', 1);
        })->count();

        return [
            'totalDokumen' => $totalDokumen,
            'belumProses' => $belumProses,
            'ditolak' => $ditolak,
            'selesai' => $selesai
        ];
    }

    private function spjGu()
    {
        $totalDokumen = SpjGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }
        })->count();

        $belumProses = SpjGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 0)->orWhere('status_validasi_ppk', 0)->orWhere('status_validasi_akhir', 0);
                });
            }
        })->count();

        $ditolak = SpjGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 2);
                    $query->orWhere('status_validasi_ppk', 2);
                });
            }
        })->count();

        $selesai = SpjGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }
        })->where('status_validasi_akhir', 1)->count();

        return [
            'totalDokumen' => $totalDokumen,
            'belumProses' => $belumProses,
            'ditolak' => $ditolak,
            'selesai' => $selesai
        ];
    }

    private function sppGu()
    {
        $totalDokumen = SppGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->whereHas('spjGu', function ($query) {
                    $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                });
            } else if (Auth::user()->role == 'Operator SPM') {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
            }
        })->count();

        $belumProses = SppGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->whereHas('spjGu', function ($query) {
                    $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                });
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else if (Auth::user()->role == "Operator SPM") {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
                $query->whereNull('dokumen_spm');
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 0)->orWhere('status_validasi_ppk', 0)->orWhere('status_validasi_akhir', 0)->orWhere('dokumen_spm', null)->orWhere('dokumen_arsip_sp2d', null);
                });
            }
        })->count();

        $ditolak = SppGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->whereHas('spjGu', function ($query) {
                    $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                });
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where(function ($query) {
                    $query->where('status_validasi_asn', 2);
                    $query->orWhere('status_validasi_ppk', 2);
                });
            }
        })->count();

        $selesai = SppGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->whereHas('spjGu', function ($query) {
                    $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
                });
            }

            if (Auth::user()->role == "Operator SPM") {
                $query->whereNotNull('dokumen_spm');
            }

            $query->where('status_validasi_akhir', 1);
        })->count();

        return [
            'totalDokumen' => $totalDokumen,
            'belumProses' => $belumProses,
            'ditolak' => $ditolak,
            'selesai' => $selesai
        ];
    }
}
