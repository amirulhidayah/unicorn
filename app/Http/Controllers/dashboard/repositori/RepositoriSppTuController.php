<?php

namespace App\Http\Controllers\dashboard\repositori;

use App\Http\Controllers\Controller;
use App\Models\SppTu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class RepositoriSppTuController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.repositori.sppTu.index');
    }

    public function show(SppTu $sppTu)
    {
        $tipe = 'spp_tu';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) {
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

            return view('dashboard.pages.repositori.sppTu.show', compact(['sppTu', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function downloadSemuaBerkas(SppTu $sppTu)
    {
        $pdfMerger = PDFMergerFacade::init();

        foreach ($sppTu->dokumenSppTu as $dokumen) {
            if (Storage::exists('dokumen_spp_tu/' . $dokumen->dokumen)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spp_tu/' . $dokumen->dokumen));
            }
        }

        if ($sppTu->dokumen_spm) {
            if (Storage::exists('dokumen_spm_spp_tu/' . $sppTu->dokumen_spm)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spm_spp_tu/' . $sppTu->dokumen_spm));
            }
        }

        if ($sppTu->dokumen_arsip_sp2d) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_tu/' . $sppTu->dokumen_arsip_sp2d)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_arsip_sp2d_spp_tu/' . $sppTu->dokumen_arsip_sp2d));
            }
        }

        $pdfMerger->merge();
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="SPP-TU-' . $sppTu->nomor_surat . '.pdf"');
        echo $pdfMerger->output();
    }
}
