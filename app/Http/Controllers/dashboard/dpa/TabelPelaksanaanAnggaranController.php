<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Exports\TabelDpaExport;
use App\Exports\TabelPelaksanaanAnggaranExport;
use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanSpjGu;
use App\Models\KegiatanSppLs;
use App\Models\Program;
use App\Models\SekretariatDaerah;
use App\Models\Spd;
use App\Models\SppLs;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TabelPelaksanaanAnggaranController extends Controller
{
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'asc')->get();
        $sekretariatDaerah = SekretariatDaerah::orderBY('nama', 'asc')->get();
        $role = Auth::user()->role;
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
        return view('dashboard.pages.dpa.tabelPelaksanaanAnggaran.index', compact('tahun', 'sekretariatDaerah', 'daftarBulan', 'role'));
    }

    public function getTable(Request $request)
    {
        $role = Auth::user()->role;
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

        $tahun = $request->tahun;
        $sekretariatDaerahId = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $jenisSpp = $request->jenis_spp;

        $array = [];

        if ($tahun && $bulanDari && $bulanSampai) {
            if ($bulanDariIndex <= $bulanSampaiIndex) {
                $array = $this->_dataTabel($sekretariatDaerahId, $tahun, $listBulan);
                return view('dashboard.components.widgets.tabelPelaksanaanAnggaran', compact(['array']))->render();
            }
        }
    }

    private function _dataTabel($sekretariatDaerahId, $tahun, $listBulan)
    {
        $sekretariatDaerahS = SekretariatDaerah::where(function ($query) use ($sekretariatDaerahId) {
            if ($sekretariatDaerahId != "Semua") {
                $query->where('id', $sekretariatDaerahId);
            }
        })->orderBy('nama', 'asc')->get();

        $array = [
            'bulan' => $listBulan
        ];
        foreach ($sekretariatDaerahS as $sekretariatDaerah) {
            $programDpa = Program::with('kegiatan')->whereHas('kegiatan', function ($query) use ($sekretariatDaerah, $tahun) {
                $query->whereHas('spd', function ($query) use ($sekretariatDaerah, $tahun) {
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                    if ($tahun) {
                        $query->where('tahun_id', $tahun);
                    }
                });
            })->withTrashed()->get();

            $programData = [];
            foreach ($programDpa as $program) {
                $kegiatanData = [];
                $daftarKegiatan = Kegiatan::where('program_id', $program->id)->whereHas('spd', function ($query) use ($sekretariatDaerah, $tahun) {
                    $query->where('tahun_id', $tahun);
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                })->orderBy('no_rek', 'asc')->get();

                foreach ($daftarKegiatan as $kegiatan) {
                    $bulanData = [];
                    $spd = Spd::where('sekretariat_daerah_id', $sekretariatDaerah->id)->where('tahun_id', $tahun)->where('kegiatan_id', $kegiatan->id)->first();
                    $sisaAnggaran = $spd->jumlah_anggaran ?? 0;
                    foreach ($listBulan as $bulan) {
                        $sppLs = KegiatanSppLs::where('kegiatan_id', $kegiatan->id)->whereHas('sppLs', function ($query) use ($bulan, $tahun, $sekretariatDaerah) {
                            $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                            $query->where('tahun_id', $tahun);
                            $query->where('bulan', $bulan);
                            $query->whereNotNull('dokumen_arsip_sp2d');
                        })->sum('anggaran_digunakan');

                        $anggaranSppLs = $sppLs ?? 0;

                        $sppGu = KegiatanSpjGu::where('kegiatan_id', $kegiatan->id)->whereHas('spjGu', function ($query) use ($bulan, $tahun, $sekretariatDaerah) {
                            $query->whereHas('sppGu', function ($query) use ($bulan, $tahun, $sekretariatDaerah) {
                                $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                                $query->where('tahun_id', $tahun);
                                $query->where('bulan', $bulan);
                                $query->whereNotNull('dokumen_arsip_sp2d');
                            });
                        })->sum('anggaran_digunakan');
                        $anggaranSppGu = $sppGu ?? 0;

                        $totalAnggaran = $anggaranSppLs + $anggaranSppGu;
                        $sisaAnggaran -= $totalAnggaran;

                        $bulanData[] = [
                            'nama' => $bulan,
                            'anggaran_digunakan' => $totalAnggaran,
                            'sisa_anggaran' =>  $sisaAnggaran,
                        ];
                    }

                    $kegiatanData[] = [
                        'id' => $spd->id,
                        'nama' => $kegiatan->nama,
                        'no_rek' => $kegiatan->no_rek,
                        'jumlah_anggaran' => $spd->jumlah_anggaran,
                        'bulan' => $bulanData,
                    ];
                }

                $programData[] = [
                    'nama' => $program->nama,
                    'no_rek' => $program->no_rek,
                    'kegiatan' => $kegiatanData,
                ];
            }

            $array['data'][] = [
                'sekretariat_daerah' => $sekretariatDaerah->nama,
                'program' => $programData,
            ];
        }

        $totalAnggaranDigunakan = 0;
        $totalSisaAnggaran = 0;

        foreach ($array['data'] as &$sekretariat) {
            foreach ($sekretariat['program'] as &$program) {
                foreach ($program['kegiatan'] as &$kegiatan) {
                    foreach ($kegiatan['bulan'] as &$bulanData) {
                        $totalAnggaranDigunakan += $bulanData['anggaran_digunakan'];
                        $totalSisaAnggaran += $bulanData['sisa_anggaran'];
                    }
                }
            }
        }
        foreach ($array['data'] as &$sekretariat) {
            foreach ($sekretariat['program'] as &$program) {
                foreach ($program['kegiatan'] as &$kegiatan) {
                    $sum_anggaran_digunakan_kegiatan = 0;
                    $sum_sisa_anggaran_kegiatan = 0;

                    foreach ($kegiatan['bulan'] as &$bulanData) {
                        $sum_anggaran_digunakan_kegiatan += $bulanData['anggaran_digunakan'];
                        $sum_sisa_anggaran_kegiatan += $bulanData['sisa_anggaran'];
                    }

                    $kegiatan['total_anggaran_digunakan'] = $sum_anggaran_digunakan_kegiatan;
                    $kegiatan['total_sisa_anggaran'] = $sum_sisa_anggaran_kegiatan;
                }
            }
        }

        foreach ($array['data'] as &$sekretariat) {
            foreach ($sekretariat['program'] as &$program) {
                $totalBulan = null;

                $jumlahAnggaran = 0;
                foreach ($program['kegiatan'] as &$kegiatan) {
                    $jumlahAnggaran += $kegiatan['jumlah_anggaran'];
                    foreach ($kegiatan['bulan'] as $index => $bulanData) {
                        $totalBulan[$index] = [
                            'nama' => $bulanData['nama'],
                            'anggaran_digunakan' => ($totalBulan[$index]['anggaran_digunakan'] ?? 0) + $bulanData['anggaran_digunakan'],
                            'sisa_anggaran' => ($totalBulan[$index]['sisa_anggaran'] ?? 0) + $bulanData['sisa_anggaran']
                        ];
                    }
                }

                $anggaranDigunakan = 0;
                $sisaAnggaran = 0;

                $program['total_bulan'] = [
                    'bulan' => $totalBulan,
                ];
                $program['jumlah_anggaran'] = $jumlahAnggaran;
            }
        }

        return $array;
    }

    public function export(Request $request)
    {
        $role = Auth::user()->role;
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

        $bulanDari = $request->bulan_dari_filter;
        $bulanSampai = $request->bulan_sampai_filter;

        $bulanDariIndex = array_search($bulanDari, $arrayBulan);
        $bulanSampaiIndex = array_search($bulanSampai, $arrayBulan);

        $listBulan = array_slice($arrayBulan, $bulanDariIndex, $bulanSampaiIndex - $bulanDariIndex + 1);

        $tahun = $request->tahun_filter;
        $sekretariatDaerahId = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $jenisSpp = $request->jenis_spp_filter;

        $array = [];
        if ($tahun && $bulanDari && $bulanSampai) {
            if ($bulanDariIndex <= $bulanSampaiIndex) {
                $array = $this->_dataTabel($sekretariatDaerahId, $tahun, $listBulan);
            }
        }

        $tanggal = Carbon::parse(Carbon::now())->translatedFormat('d F Y');

        return Excel::download(new TabelPelaksanaanAnggaranExport($jenisSpp, $array), "Export" . "-" . $tanggal . "-" . rand(1, 9999) . '.xlsx');
    }
}
