<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\Kegiatan;
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
        return view('dashboard.pages.dpa.statistik.index', compact('tahun', 'SekretariatDaerah'));
    }

    public function getDataStatistik(Request $request)
    {
        $role = Auth::user()->role;
        $tahun_id = $request->tahun_id;
        $kegiatan_dpa_id = $request->kegiatan_dpa_id;
        $sekretariat_daerah_id = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah_id : Auth::user()->profil->sekretariat_daerah_id;

        $kegiatan = Kegiatan::where('id', $kegiatan_dpa_id)->first();

        $judul = $kegiatan ? $kegiatan->nama : 'Kegiatan Belum Dipilih';

        $spd = Spd::where('kegiatan_dpa_id', $kegiatan_dpa_id)->where('sekretariat_daerah_id', $sekretariat_daerah_id)->where('tahun_id', $tahun_id)->first();

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $anggaranDigunakan = [];
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Januari');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Februari');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Maret');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'April');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Mei');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Juni');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Juli');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Agustus');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'September');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Oktober');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'November');
        $anggaranDigunakan[] = $this->anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, 'Desember');

        return response()->json([
            'judul' => $judul,
            'bulan' => $bulan,
            'jumlah_anggaran' => $spd->jumlah_anggaran,
            'data' => $anggaranDigunakan
        ]);
    }

    private function anggaranDigunakan($sekretariat_daerah_id, $tahun_id, $kegiatan_dpa_id, $bulan)
    {
        $sppLs = SppLs::where('sekretariat_daerah_id', $sekretariat_daerah_id)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun_id)
            ->where('kegiatan_dpa_id', $kegiatan_dpa_id)
            ->where('status_validasi_akhir', 1)
            ->where('bulan', $bulan)
            ->sum('anggaran_digunakan');

        $sppGu = SppGu::where('sekretariat_daerah_id', $sekretariat_daerah_id)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun_id)
            ->where('kegiatan_dpa_id', $kegiatan_dpa_id)
            ->where('status_validasi_akhir', 1)
            ->where('bulan', $bulan)
            ->sum('anggaran_digunakan');

        return ($sppLs + $sppGu);
    }
}
