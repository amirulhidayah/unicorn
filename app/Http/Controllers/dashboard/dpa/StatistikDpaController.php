<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\KegiatanDpa;
use App\Models\Spd;
use App\Models\SppGu;
use App\Models\SppLs;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatistikDpaController extends Controller
{
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'asc')->get();
        $SekretariatDaerah = SekretariatDaerah::orderBY('nama', 'asc')->get();

        $daftarBulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        return view('dashboard.pages.dpa.statistik.index', compact('tahun', 'daftarBulan', 'SekretariatDaerah'));
    }

    public function getDataStatistik(Request $request)
    {
        $jenisSpp = $request->jenis_spp;
        $role = Auth::user()->role;
        $tahunId = $request->tahun_id;
        $kegiatanDpaId = $request->kegiatan_id;
        $sekretariatDaerahId = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah_id : Auth::user()->profil->sekretariat_daerah_id;

        $arrayBulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $bulanDari = $request->bulan_dari;
        $bulanSampai = $request->bulan_sampai;

        $bulanDariIndex = array_search($bulanDari, $arrayBulan);
        $bulanSampaiIndex = array_search($bulanSampai, $arrayBulan);

        $listBulan = array_slice($arrayBulan, $bulanDariIndex, $bulanSampaiIndex - $bulanDariIndex + 1);

        $kegiatan = KegiatanDpa::where('id', $kegiatanDpaId)->first();
        $judul = $kegiatan ? $kegiatan->nama : 'Kegiatan Belum Dipilih';

        $anggaranDigunakan = [];
        $jumlahAnggaran = 0;
        if ($jenisSpp == "SPP-GU") {
            foreach ($listBulan as $bulan) {
                $jumlahAnggaran += SppGu::where('kegiatan_id', $kegiatanDpaId)->where('sekretariat_daerah_id', $sekretariatDaerahId)->where('tahap', 'Selesai')->where('tahun_id', $tahunId)->where('bulan', $bulan)->first()->perencanaan_anggaran ?? 0;
                $anggaranDigunakan[] = SppGu::where('sekretariat_daerah_id', $sekretariatDaerahId)
                    ->orderBy('created_at', 'asc')
                    ->where('tahun_id', $tahunId)
                    ->where('kegiatan_id', $kegiatanDpaId)
                    ->where('tahap', 'Selesai')
                    ->where('bulan', $bulan)
                    ->sum('anggaran_digunakan');
            }
        } else {
            $kegiatan = KegiatanDpa::where('id', $kegiatanDpaId)->first();
            $judul = $kegiatan ? $kegiatan->nama : 'Kegiatan Belum Dipilih';
            $spd = Spd::where('kegiatan_id', $kegiatanDpaId)->where('sekretariat_daerah_id', $sekretariatDaerahId)->where('tahun_id', $tahunId)->first();
            $jumlahAnggaran = $spd->jumlah_anggaran ?? 0;
            foreach ($listBulan as $bulan) {
                $anggaranDigunakan[] = SppLs::where('sekretariat_daerah_id', $sekretariatDaerahId)
                    ->orderBy('created_at', 'asc')
                    ->where('tahun_id', $tahunId)
                    ->where('kegiatan_id', $kegiatanDpaId)
                    ->where('status_validasi_akhir', 1)
                    ->where('bulan', $bulan)
                    ->sum('anggaran_digunakan');
            }
        }

        return response()->json([
            'judul' => $judul,
            'bulan' => $listBulan,
            'jumlah_anggaran' => $jumlahAnggaran,
            'data' => $anggaranDigunakan
        ]);
    }

    private function anggaranDigunakan($jenisSpp, $sekretariatDaerahId, $tahunId, $kegiatanDpaId, $bulan)
    {
        if ($jenisSpp == "SPP-GU") {
        }
        $sppLs = SppLs::where('sekretariat_daerah_id', $sekretariatDaerahId)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahunId)
            ->where('kegiatan_id', $kegiatanDpaId)
            ->where('status_validasi_akhir', 1)
            ->where('bulan', $bulan)
            ->sum('anggaran_digunakan');

        // $sppGu = SppGu::where('sekretariat_daerah_id', $sekretariatDaerahId)
        //     ->orderBy('created_at', 'asc')
        //     ->where('tahun_id', $tahunId)
        //     ->where('kegiatan_id', $kegiatanDpaId)
        //     ->where('status_validasi_akhir', 1)
        //     ->where('bulan', $bulan)
        //     ->sum('anggaran_digunakan');

        return ($sppLs);
    }
}
