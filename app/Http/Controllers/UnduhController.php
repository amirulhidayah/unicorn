<?php

namespace App\Http\Controllers;

use App\Models\RiwayatSppGu;
use App\Models\RiwayatSppLs;
use App\Models\RiwayatSppTu;
use App\Models\RiwayatSppUp;
use App\Models\Spd;
use App\Models\SppGu;
use App\Models\SppLs;
use App\Models\SppTu;
use App\Models\SppUp;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;

class UnduhController extends Controller
{
    public function suratPenolakanSppUp(SppUp $sppUp, Request $request)
    {
        $tahapRiwayat = $request->tahapRiwayat;
        $riwayatSppUp = RiwayatSppUp::whereNotNull('tahap_riwayat')->where('spp_up_id', $sppUp->id)->whereNotNull('nomor_surat')->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppUpAsn = RiwayatSppUp::whereNotNull('tahap_riwayat')->where('role', 'ASN Sub Bagian Keuangan')->where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppUpPpk = RiwayatSppUp::whereNotNull('tahap_riwayat')->where('role', 'PPK')->where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $tahapRiwayat)->first();
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();
        $pdf = PDF::loadView('dashboard.pages.spp.sppUp.suratPenolakan', compact(['riwayatSppUp', 'riwayatSppUpAsn', 'riwayatSppUpPpk', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Penolakan-' . $riwayatSppUp->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPenolakanSppTu(SppTu $sppTu, Request $request)
    {
        $tahapRiwayat = $request->tahapRiwayat;
        $riwayatSppTu = RiwayatSppTu::whereNotNull('tahap_riwayat')->where('spp_tu_id', $sppTu->id)->whereNotNull('nomor_surat')->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppTuAsn = RiwayatSppTu::whereNotNull('tahap_riwayat')->where('role', 'ASN Sub Bagian Keuangan')->where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppTuPpk = RiwayatSppTu::whereNotNull('tahap_riwayat')->where('role', 'PPK')->where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $tahapRiwayat)->first();
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();
        $pdf = PDF::loadView('dashboard.pages.spp.sppTu.suratPenolakan', compact(['riwayatSppTu', 'riwayatSppTuAsn', 'riwayatSppTuPpk', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Penolakan-' . $riwayatSppTu->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPenolakanSppLs(SppLs $sppLs, Request $request)
    {
        $tahapRiwayat = $request->tahapRiwayat;

        $spd = Spd::where('kegiatan_id', $sppLs->kegiatan_id)->where('tahun_id', $sppLs->tahun_id)->where('sekretariat_daerah_id', $sppLs->sekretariat_daerah_id)->first();

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

        $riwayatSppLs = RiwayatSppLs::whereNotNull('tahap_riwayat')->where('spp_ls_id', $sppLs->id)->whereNotNull('nomor_surat')->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppLsAsn = RiwayatSppLs::whereNotNull('tahap_riwayat')->where('role', 'ASN Sub Bagian Keuangan')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppLsPpk = RiwayatSppLs::whereNotNull('tahap_riwayat')->where('role', 'PPK')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $tahapRiwayat)->first();

        $hariIni = Carbon::now()->translatedFormat('d F Y');

        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();

        $pdf = PDF::loadView('dashboard.pages.spp.sppLs.suratPenolakan', compact(['riwayatSppLs', 'riwayatSppLsAsn', 'riwayatSppLsPpk', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran', 'spd', 'programDanKegiatan', 'totalProgramDanKegiatan']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Penolakan-' . $riwayatSppLs->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPenolakanSppGu(SppGu $sppGu, Request $request)
    {
        $tahapRiwayat = $request->tahapRiwayat;

        $spd = Spd::where('kegiatan_id', $sppGu->kegiatan_id)->where('tahun_id', $sppGu->tahun_id)->where('sekretariat_daerah_id', $sppGu->sekretariat_daerah_id)->first();

        $riwayatSppGu = RiwayatSppGu::whereNotNull('tahap_riwayat')->where('spp_gu_id', $sppGu->id)->whereNotNull('nomor_surat')->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppGuAsn = RiwayatSppGu::whereNotNull('tahap_riwayat')->where('role', 'ASN Sub Bagian Keuangan')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $tahapRiwayat)->first();
        $riwayatSppGuPpk = RiwayatSppGu::whereNotNull('tahap_riwayat')->where('role', 'PPK')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $tahapRiwayat)->first();

        $hariIni = Carbon::now()->translatedFormat('d F Y');

        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();

        $pdf = PDF::loadView('dashboard.pages.spp.sppGu.suratPenolakan', compact(['riwayatSppGu', 'riwayatSppGuAsn', 'riwayatSppGuPpk', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran', 'spd']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Penolakan-' . $riwayatSppGu->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPernyataanSppUp(SppUp $sppUp)
    {
        if ($sppUp->status_validasi_akhir != 1) {
            return throw new Exception('Gagal Diproses');
        }
        $tipe = "SPP UP";
        $spp = $sppUp;
        $tanggal = Carbon::parse($sppUp->tanggal_validasi_akhir)->translatedFormat('d F Y');
        $jumlah = "Rp." . number_format($spp->jumlah_anggaran, 0, ',', '.');
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $pdf = PDF::loadView('dashboard.pages.spp.suratPernyataan', compact(['spp', 'tipe', 'tanggal', 'jumlah', 'hariIni', 'ppk']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Pernyataan-' . $sppUp->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPernyataanSppTu(SppTu $sppTu)
    {
        if ($sppTu->status_validasi_akhir != 1) {
            return throw new Exception('Gagal Diproses');
        }
        $tipe = "SPP TU";
        $spp = $sppTu;
        $tanggal = Carbon::parse($sppTu->tanggal_validasi_akhir)->translatedFormat('d F Y');
        $jumlah = "Rp." . number_format($spp->jumlah_anggaran, 0, ',', '.');
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $pdf = PDF::loadView('dashboard.pages.spp.suratPernyataan', compact(['spp', 'tipe', 'tanggal', 'jumlah', 'hariIni', 'ppk']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Pernyataan-' . $sppTu->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPernyataanSppLs(SppLs $sppLs)
    {
        if ($sppLs->status_validasi_akhir != 1) {
            return throw new Exception('Gagal Diproses');
        }
        $tipe = "SPP LS";
        $spp = $sppLs;
        $tanggal = Carbon::parse($sppLs->tanggal_validasi_akhir)->translatedFormat('d F Y');
        $jumlah = "Rp." . number_format($spp->anggaran_digunakan, 0, ',', '.');
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $pdf = PDF::loadView('dashboard.pages.spp.suratPernyataan', compact(['spp', 'tipe', 'tanggal', 'jumlah', 'hariIni', 'ppk']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Pernyataan-' . $sppLs->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }

    public function suratPernyataanSppGu(SppGu $sppGu)
    {
        if ($sppGu->status_validasi_akhir != 1) {
            return throw new Exception('Gagal Diproses');
        }
        $tipe = "SPP GU";
        $spp = $sppGu;
        $tanggal = Carbon::parse($sppGu->tanggal_validasi_akhir)->translatedFormat('d F Y');
        $jumlah = "Rp." . number_format($spp->anggaran_digunakan, 0, ',', '.');
        $hariIni = Carbon::now()->translatedFormat('d F Y');
        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
        $pdf = PDF::loadView('dashboard.pages.spp.suratPernyataan', compact(['spp', 'tipe', 'tanggal', 'jumlah', 'hariIni', 'ppk']))->setPaper('f4', 'portrait');
        return $pdf->download('Surat-Pernyataan-' . $sppGu->nomor_surat . '-' . Carbon::now()  . '.pdf');
    }
}
