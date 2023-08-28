<?php

use App\Models\KegiatanSpjGu;
use App\Models\KegiatanSppLs;
use App\Models\Spd;
use App\Models\SppLs;
use Illuminate\Support\Facades\Auth;

if (!function_exists('jumlah_anggaran')) {
    function jumlah_anggaran($sekretariatDaerah, $kegiatan, $bulan, $tahun, $idKegiatan = null)
    {
        $role = Auth::user()->role;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $sekretariatDaerah : Auth::user()->profil->sekretariat_daerah_id;

        $spd = Spd::where('kegiatan_id', $kegiatan)->where('sekretariat_daerah_id', $sekretariatDaerah)->where('tahun_id', $tahun)->first();
        $jumlahAnggaran = $spd->jumlah_anggaran ?? 0;

        $kegiatanSppLs = KegiatanSppLs::where('kegiatan_id', $kegiatan)->whereHas('sppLs', function ($query) use ($sekretariatDaerah, $bulan, $tahun, $idKegiatan) {
            if ($idKegiatan) {
                $query->where('id', '!=', $idKegiatan);
            }
            $query->where('sekretariat_daerah_id', $sekretariatDaerah);
            // $query->where('tahun_id', $tahun);
            // if ($bulan == 'Januari') {
            //     $query->where('bulan', 'Januari');
            // } else if ($bulan == 'Februari') {
            //     $query->whereIn('bulan', ['Januari', 'Februari']);
            // } else if ($bulan == 'Maret') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret']);
            // } else if ($bulan == 'April') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April']);
            // } else if ($bulan == 'Mei') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei']);
            // } else if ($bulan == 'Juni') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni']);
            // } else if ($bulan == 'Juli') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli']);
            // } else if ($bulan == 'Agustus') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus']);
            // } else if ($bulan == 'September') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September']);
            // } else if ($bulan == 'Oktober') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober']);
            // } else if ($bulan == 'November') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November']);
            // } else if ($bulan == 'Desember') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
            // }
        })->get();

        $kegiatanSpjGu = KegiatanSpjGu::where('kegiatan_id', $kegiatan)->whereHas('spjGu', function ($query) use ($sekretariatDaerah, $bulan, $tahun, $idKegiatan) {
            if ($idKegiatan) {
                $query->where('id', '!=', $idKegiatan);
            }
            $query->where('sekretariat_daerah_id', $sekretariatDaerah);
            $query->where('tahun_id', $tahun);
            // if ($bulan == 'Januari') {
            //     $query->where('bulan', 'Januari');
            // } else if ($bulan == 'Februari') {
            //     $query->whereIn('bulan', ['Januari', 'Februari']);
            // } else if ($bulan == 'Maret') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret']);
            // } else if ($bulan == 'April') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April']);
            // } else if ($bulan == 'Mei') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei']);
            // } else if ($bulan == 'Juni') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni']);
            // } else if ($bulan == 'Juli') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli']);
            // } else if ($bulan == 'Agustus') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus']);
            // } else if ($bulan == 'September') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September']);
            // } else if ($bulan == 'Oktober') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober']);
            // } else if ($bulan == 'November') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November']);
            // } else if ($bulan == 'Desember') {
            //     $query->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
            // }
        })->get();

        $totalSpp = $kegiatanSppLs->sum('anggaran_digunakan') + $kegiatanSpjGu->sum('anggaran_digunakan');
        $jumlahAnggaran = (($spd->jumlah_anggaran ?? 0) - $totalSpp);

        return $jumlahAnggaran;
    }
}
