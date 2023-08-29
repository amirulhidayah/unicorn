<?php

namespace App\Http\Controllers\dashboard\repositori;

use App\Http\Controllers\Controller;
use App\Models\SppUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class RepositoriSppUpController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.repositori.sppUp.index');
    }

    public function show(SppUp $sppUp)
    {
        $role = Auth::user()->role;
        if (!(((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) && $sppUp->dokumen_arsip_sp2d)) {
            return redirect('repositori/spp-up');
        }

        $tipe = 'spp_up';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) {
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

            return view('dashboard.pages.repositori.sppUp.show', compact(['sppUp', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function downloadSemuaBerkas(SppUp $sppUp)
    {
        $role = Auth::user()->role;
        if (!(((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) && $sppUp->dokumen_arsip_sp2d)) {
            return redirect('repositori/spp-up');
        }

        $pdfMerger = PDFMergerFacade::init();

        foreach ($sppUp->dokumenSppUp as $dokumen) {
            if (Storage::exists('dokumen_spp_up/' . $dokumen->dokumen)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spp_up/' . $dokumen->dokumen));
            }
        }

        if ($sppUp->dokumen_spm) {
            if (Storage::exists('dokumen_spm_spp_up/' . $sppUp->dokumen_spm)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spm_spp_up/' . $sppUp->dokumen_spm));
            }
        }

        if ($sppUp->dokumen_arsip_sp2d) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_up/' . $sppUp->dokumen_arsip_sp2d)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_arsip_sp2d_spp_up/' . $sppUp->dokumen_arsip_sp2d));
            }
        }

        $pdfMerger->merge();
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="SPP-UP-' . $sppUp->nomor_surat . '.pdf"');
        echo $pdfMerger->output();
    }
}
