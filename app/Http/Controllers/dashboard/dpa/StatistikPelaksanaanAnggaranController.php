<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\SekretariatDaerah;
use App\Models\KegiatanDpa;
use App\Models\KegiatanSpjGu;
use App\Models\KegiatanSppLs;
use App\Models\Spd;
use App\Models\SppGu;
use App\Models\SppLs;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatistikPelaksanaanAnggaranController extends Controller
{
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'asc')->get();
        $sekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

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

        return view('dashboard.pages.dpa.statistik.index', compact('tahun', 'daftarBulan', 'sekretariatDaerah'));
    }

    public function getData(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun_id;
        $kegiatanId = $request->kegiatan_id;
        $sekretariatDaerahId = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM']) ? $request->sekretariat_daerah_id : Auth::user()->profil->sekretariat_daerah_id;

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

        $anggaranDigunakan = [];
        $jumlahAnggaran = 0;

        $kegiatan = Kegiatan::where('id', $kegiatanId)->first();
        $judul = $kegiatan ? $kegiatan->nama : 'Kegiatan Belum Dipilih';
        $spd = Spd::where('kegiatan_id', $kegiatanId)->where('sekretariat_daerah_id', $sekretariatDaerahId)->where('tahun_id', $tahun)->first();
        $jumlahAnggaran = $spd->jumlah_anggaran ?? 0;
        foreach ($listBulan as $bulan) {
            $sppLs = KegiatanSppLs::where('kegiatan_id', $kegiatan->id)->whereHas('sppLs', function ($query) use ($bulan, $tahun, $sekretariatDaerahId) {
                $query->where('sekretariat_daerah_id', $sekretariatDaerahId);
                $query->where('tahun_id', $tahun);
                $query->where('bulan', $bulan);
                $query->whereNotNull('dokumen_arsip_sp2d');
            })->sum('anggaran_digunakan') ?? 0;

            $sppGu = KegiatanSpjGu::where('kegiatan_id', $kegiatan->id)->whereHas('spjGu', function ($query) use ($bulan, $tahun, $sekretariatDaerahId) {
                $query->whereHas('sppGu', function ($query) use ($bulan, $tahun, $sekretariatDaerahId) {
                    $query->where('sekretariat_daerah_id', $sekretariatDaerahId);
                    $query->where('tahun_id', $tahun);
                    $query->where('bulan', $bulan);
                    $query->whereNotNull('dokumen_arsip_sp2d');
                });
            })->sum('anggaran_digunakan') ?? 0;

            $anggaranDigunakan[] = ($sppLs + $sppGu);
        }

        return response()->json([
            'judul' => $judul,
            'bulan' => $listBulan,
            'jumlah_anggaran' => $jumlahAnggaran,
            'data' => $anggaranDigunakan
        ]);
    }
}
