<?php

namespace App\Http\Controllers\dashboard\repositori;

use App\Http\Controllers\Controller;
use App\Models\SppLs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class RepositoriSppLsController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.repositori.sppLs.index');
    }

    public function show(SppLs $sppLs)
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
            $anggaranDigunakan = $kegiatanSppLs->anggaran_digunakan;

            $programDanKegiatan[] = [
                'program' => $program,
                'kegiatan' => $kegiatan,
                'anggaran_digunakan' => $anggaranDigunakan,
            ];

            $totalAnggaranDigunakan += $anggaranDigunakan;
        }

        $totalProgramDanKegiatan = [
            'total_anggaran_digunakan' => $totalAnggaranDigunakan,
        ];

        return view('dashboard.pages.repositori.sppLs.show', compact(['sppLs', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']));
    }

    public function downloadSemuaBerkas(SppLs $sppLs)
    {
        $pdfMerger = PDFMergerFacade::init();

        foreach ($sppLs->dokumenSppLs as $dokumen) {
            if (Storage::exists('dokumen_spp_ls/' . $dokumen->dokumen)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spp_ls/' . $dokumen->dokumen));
            }
        }

        if ($sppLs->dokumen_spm) {
            if (Storage::exists('dokumen_spm_spp_ls/' . $sppLs->dokumen_spm)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spm_spp_ls/' . $sppLs->dokumen_spm));
            }
        }

        if ($sppLs->dokumen_arsip_sp2d) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_ls/' . $sppLs->dokumen_arsip_sp2d)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_arsip_sp2d_spp_ls/' . $sppLs->dokumen_arsip_sp2d));
            }
        }

        $pdfMerger->merge();
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="SPP-LS-' . $sppLs->nomor_surat . '.pdf"');
        echo $pdfMerger->output();
    }
}
