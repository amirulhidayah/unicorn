<?php

namespace App\Http\Controllers\dashboard\repositori;

use App\Http\Controllers\Controller;
use App\Models\SpjGu;
use App\Models\SppGu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class RepositoriSppGuController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.repositori.sppGu.index');
    }

    public function show(SppGu $sppGu)
    {
        $tipe = 'spp_gu';
        $spjGu = SpjGu::where('id', $sppGu->spj_gu_id)->first();
        if ($spjGu) {
            $totalJumlahAnggaran = 0;
            $totalAnggaranDigunakan = 0;
            $totalSisaAnggaran = 0;
            $programDanKegiatan = [];
            $totalProgramDanKegiatan = [];

            foreach ($spjGu->kegiatanSpjGu as $kegiatanSpjGu) {
                $program = $kegiatanSpjGu->kegiatan->program->nama . ' (' . $kegiatanSpjGu->kegiatan->program->no_rek . ')';
                $kegiatan = $kegiatanSpjGu->kegiatan->nama . ' (' . $kegiatanSpjGu->kegiatan->no_rek . ')';
                $anggaranDigunakan = $kegiatanSpjGu->anggaran_digunakan;

                $programDanKegiatan[] = [
                    'program' => $program,
                    'kegiatan' => $kegiatan,
                    'dokumen' => $kegiatanSpjGu->dokumen,
                    'anggaran_digunakan' => $anggaranDigunakan,
                ];

                $totalAnggaranDigunakan += $anggaranDigunakan;
            }

            $totalProgramDanKegiatan = [
                'total_anggaran_digunakan' => $totalAnggaranDigunakan,
            ];
        }

        return view('dashboard.pages.repositori.sppGu.show', compact(['sppGu', 'spjGu', 'totalProgramDanKegiatan', 'tipe', 'programDanKegiatan']));
    }

    public function downloadSemuaBerkas(SppGu $sppGu)
    {
        $pdfMerger = PDFMergerFacade::init();

        foreach ($sppGu->dokumenSppGu as $dokumen) {
            if (Storage::exists('dokumen_spp_gu/' . $dokumen->dokumen)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spp_gu/' . $dokumen->dokumen));
            }
        }

        if ($sppGu->dokumen_spm) {
            if (Storage::exists('dokumen_spm_spp_gu/' . $sppGu->dokumen_spm)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spm_spp_gu/' . $sppGu->dokumen_spm));
            }
        }

        if ($sppGu->dokumen_arsip_sp2d) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_gu/' . $sppGu->dokumen_arsip_sp2d)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_arsip_sp2d_spp_gu/' . $sppGu->dokumen_arsip_sp2d));
            }
        }

        $pdfMerger->merge();
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="SPP-GU-' . $sppGu->nomor_surat . '.pdf"');
        echo $pdfMerger->output();
    }
}
