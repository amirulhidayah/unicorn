<?php

namespace App\Http\Controllers;

use App\Models\DokumenSppGu;
use App\Models\SppGu;
use Illuminate\Http\Request;

class ScanqrcodeController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.scanqrcode');
    }

    public function getData(Request $request)
    {
        $kode = $request->kode;

        $sppGu = SppGu::where('id', $kode)->where('tahap', '!=', 'SPJ')->first();
        if ($sppGu) {
            $dokumenSppGu = DokumenSppGu::where('tahap', 'SPJ')->where('spp_gu_id', $sppGu->id)->get();
            $perencanaanAnggaran = 'Rp. ' . number_format($sppGu->perencanaan_anggaran, 0, ',', '.');

            return response()->json([
                'status' => 'success',
                'html' => view('dashboard.components.widgets.hasilScan', compact(['sppGu', 'perencanaanAnggaran', 'dokumenSppGu']))->render()
            ]);
        }

        return response()->json([
            'status' => 'error',
        ]);
    }
}
