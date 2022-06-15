<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Http\Controllers\Controller;
use App\Models\BiroOrganisasi;
use App\Models\Kegiatan;
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
        $biroOrganisasi = BiroOrganisasi::orderBY('nama', 'asc')->get();
        return view('dashboard.pages.dpa.statistik.index', compact('tahun', 'biroOrganisasi'));
    }

    public function getDataStatistik(Request $request)
    {
        $role = Auth::user()->role;
        $tahun_id = $request->tahun_id;
        $kegiatan_id = $request->kegiatan_id;
        $biro_organisasi_id = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;

        $kegiatan = Kegiatan::where('id', $kegiatan_id)->first();

        $judul = $kegiatan ? $kegiatan->nama : 'Kegiatan Belum Dipilih';

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $anggaranDigunakan = [];
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Januari');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Februari');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Maret');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'April');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Mei');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Juni');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Juli');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Agustus');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'September');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Oktober');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'November');
        $anggaranDigunakan[] = $this->anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, 'Desember');

        return response()->json([
            'judul' => $judul,
            'bulan' => $bulan,
            'data' => $anggaranDigunakan
        ]);
    }

    private function anggaranDigunakan($biro_organisasi_id, $tahun_id, $kegiatan_id, $bulan)
    {
        $sppLs = SppLs::where('biro_organisasi_id', $biro_organisasi_id)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('status_validasi_akhir', 1)
            ->where('bulan', $bulan)
            ->sum('anggaran_digunakan');

        $sppGu = SppGu::where('biro_organisasi_id', $biro_organisasi_id)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('status_validasi_akhir', 1)
            ->where('bulan', $bulan)
            ->sum('anggaran_digunakan');

        return ($sppLs + $sppGu);
    }
}
