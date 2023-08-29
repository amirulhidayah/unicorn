<?php

namespace App\Http\Controllers\dashboard\repositori;

use App\Http\Controllers\Controller;
use App\Models\SpjGu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class RepositoriSpjGuController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.repositori.spjGu.index');
    }

    public function show(SpjGu $spjGu)
    {
        $role = Auth::user()->role;
        if (!(((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $spjGu->sekretariat_daerah_id) && $spjGu->status_validasi_akhir == 1)) {
            return redirect('repositori/spj-gu');
        }

        $tipe = 'spj_gu';

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

        return view('dashboard.pages.repositori.spjGu.show', compact(['spjGu', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']));
    }

    public function downloadSemuaBerkas(SpjGu $spjGu)
    {
        $role = Auth::user()->role;
        if (!(((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $spjGu->sekretariat_daerah_id) && $spjGu->status_validasi_akhir == 1)) {
            return redirect('repositori/spj-gu');
        }

        $pdfMerger = PDFMergerFacade::init();

        foreach ($spjGu->kegiatanSpjGu as $dokumen) {
            if (Storage::exists('dokumen_spj_gu/' . $dokumen->dokumen)) {
                $pdfMerger->addPdf(storage_path('app/public/dokumen_spj_gu/' . $dokumen->dokumen));
            }
        }

        $pdfMerger->merge();
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="SPJ-GU-' . $spjGu->nomor_surat . '.pdf"');
        echo $pdfMerger->output();
    }
}
