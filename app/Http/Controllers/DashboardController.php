<?php

namespace App\Http\Controllers;

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
        $sppGu = $this->sppGu();
        return view('dashboard.pages.dashboard', compact(['sppUp', 'sppTu', 'sppLs', 'sppGu']));
    }

    private function sppUp()
    {
        $totalDokumen = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }
        })->count();

        $belumProses = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else {
                $query->where('status_validasi_asn', 0)->where('status_validasi_ppk', 0);
            }
        })->count();

        $ditolak = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where('status_validasi_asn', 2);
                $query->orWhere('status_validasi_ppk', 2);
            }
        })->count();

        $selesai = SppUp::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }
        })->where('status_validasi_akhir', 1)->count();

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
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }
        })->count();

        $belumProses = SppTu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else {
                $query->where('status_validasi_asn', 0)->where('status_validasi_ppk', 0);
            }
        })->count();

        $ditolak = SppTu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where('status_validasi_asn', 2);
                $query->orWhere('status_validasi_ppk', 2);
            }
        })->count();

        $selesai = SppTu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }
        })->where('status_validasi_akhir', 1)->count();

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
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }
        })->count();

        $belumProses = SppLs::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else {
                $query->where('status_validasi_asn', 0)->where('status_validasi_ppk', 0);
            }
        })->count();

        $ditolak = SppLs::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where('status_validasi_asn', 2);
                $query->orWhere('status_validasi_ppk', 2);
            }
        })->count();

        $selesai = SppLs::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
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
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }
        })->count();

        $belumProses = SppGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 0);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 0);
            } else {
                $query->where('status_validasi_asn', 0)->where('status_validasi_ppk', 0);
            }
        })->count();

        $ditolak = SppGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }

            if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                $query->where('status_validasi_asn', 2);
            } else if (Auth::user()->role == "PPK") {
                $query->where('status_validasi_ppk', 2);
            } else {
                $query->where('status_validasi_asn', 2);
                $query->orWhere('status_validasi_ppk', 2);
            }
        })->count();

        $selesai = SppGu::where(function ($query) {
            if (in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                $query->where('biro_organisasi_id', Auth::user()->profil->biro_organisasi_id);
            }
        })->where('status_validasi_akhir', 1)->count();

        return [
            'totalDokumen' => $totalDokumen,
            'belumProses' => $belumProses,
            'ditolak' => $ditolak,
            'selesai' => $selesai
        ];
    }
}
