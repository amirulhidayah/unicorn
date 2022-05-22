<?php

namespace App\Http\Controllers;

use App\Models\RiwayatSppTu;
use App\Models\RiwayatSppUp;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class UnduhController extends Controller
{
    public function suratPenolakanSppUp(RiwayatSppUp $riwayatSppUp)
    {
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $pdf = PDF::loadView('dashboard.pages.spp.sppUp.suratPenolakan', compact(['riwayatSppUp', 'hariIni']))->setPaper('f4', 'portrait');;
        return $pdf->download('Surat-Penolakan-' . $riwayatSppUp->sppUp->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPenolakanSppTu(RiwayatSppTu $riwayatSppTu)
    {
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $pdf = PDF::loadView('dashboard.pages.spp.sppTu.suratPenolakan', compact(['riwayatSppTu', 'hariIni']))->setPaper('f4', 'portrait');;
        return $pdf->download('Surat-Penolakan-' . $riwayatSppTu->sppTu->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }
}
