<?php

namespace App\Http\Controllers\dashboard\dpa;

use App\Exports\FormatImport;
use App\Exports\TabelDpaExport;
use App\Http\Controllers\Controller;
use App\Imports\ImportSpd;
use App\Models\KegiatanDpa;
use App\Models\SekretariatDaerah;
use App\Models\Program;
use App\Models\ProgramDpa;
use App\Models\Spd;
use App\Models\SppGu;
use App\Models\SppLs;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TabelDpaController extends Controller
{
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'asc')->get();
        $sekretariatDaerah = SekretariatDaerah::orderBY('nama', 'asc')->get();
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

        return view('dashboard.pages.dpa.tabel.index', compact('tahun', 'sekretariatDaerah', 'daftarBulan'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => 'required',
                'sekretariat_daerah' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'sekretariat_daerah.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'jumlah_anggaran.required' => 'Jumlah anggaran tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $cekSpd = Spd::where('kegiatan_dpa_id', $request->kegiatan)->where('sekretariat_daerah_id', $request->sekretariat_daerah)->where('tahun_id', $request->tahun)->first();

        if ($cekSpd) {
            return response()->json(['status' => 'unique']);
        }

        $spd = new Spd();
        $spd->kegiatan_dpa_id = $request->kegiatan;
        $spd->tahun_id = $request->tahun;
        $spd->sekretariat_daerah_id = $request->sekretariat_daerah;
        $spd->jumlah_anggaran = preg_replace("/[^0-9]/", "", $request->jumlah_anggaran);
        $spd->save();

        return response()->json(['status' => 'success']);
    }

    public function show($id)
    {
        //
    }

    public function edit(Spd $spd)
    {
        $spd->kegiatan;
        return response()->json($spd);
    }

    public function update(Request $request, Spd $spd)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => 'required',
                'sekretariat_daerah' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'sekretariat_daerah.required' => 'Biro Organisasi tidak boleh kosong',
                'program.required' => 'Program tidak boleh kosong',
                'kegiatan.required' => 'Kegiatan tidak boleh kosong',
                'jumlah_anggaran.required' => 'TW1 tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $cekSpd = Spd::where('kegiatan_dpa_id', $request->kegiatan)->where('sekretariat_daerah_id', $request->sekretariat_daerah)->where('tahun_id', $request->tahun)->where('id', '!=', $spd->id)->first();

        if ($cekSpd) {
            return response()->json(['status' => 'unique']);
        }

        $spd->kegiatan_dpa_id = $request->kegiatan;
        $spd->tahun_id = $request->tahun;
        $spd->sekretariat_daerah_id = $request->sekretariat_daerah;
        $spd->jumlah_anggaran = preg_replace("/[^0-9]/", "", $request->jumlah_anggaran);
        $spd->save();

        return response()->json(['status' => 'success']);
    }

    public function destroy(Spd $spd)
    {
        $spd->delete();

        return response()->json(['status' => 'success']);
    }

    public function formatImport()
    {
        return Excel::download(new FormatImport, 'Format Import SPD.xlsx');
    }

    public function importSpd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file_spd' => 'required|mimes:xlsx,xls',
                'tahun' => 'required',
            ],
            [
                'file_spd.required' => 'File DPA tidak boleh kosong',
                'file_spd.mimes' => 'File DPA harus berformat .xlsx atau .xls',
                'tahun.required' => 'Tahun tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        // return response()->json($request->all());

        $tahun = $request->tahun;
        Excel::import(new ImportSpd($tahun), $request->file('file_spd'));
        return response()->json(['status' => 'success']);
    }

    public function getSpd(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $kegiatan = $request->kegiatan;
        $bulan = $request->bulan;

        $SekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;

        $spd = Spd::where('kegiatan_dpa_id', $kegiatan)->where('sekretariat_daerah_id', $SekretariatDaerah)->where('tahun_id', $tahun)->first();

        $jumlahAnggaran = $spd->jumlah_anggaran ?? 0;

        $sppLs = SppLs::where('sekretariat_daerah_id', $SekretariatDaerah)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun)
            ->where('kegiatan_dpa_id', $kegiatan)
            ->where('status_validasi_akhir', 1)
            ->whereNotNull('dokumen_spm')
            ->whereNotNull('dokumen_arsip_sp2d');

        if ($bulan == 'Januari') {
            $sppLs = $sppLs->where('bulan', 'Januari');
        } else if ($bulan == 'Februari') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari']);
        } else if ($bulan == 'Maret') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret']);
        } else if ($bulan == 'April') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April']);
        } else if ($bulan == 'Mei') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei']);
        } else if ($bulan == 'Juni') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni']);
        } else if ($bulan == 'Juli') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli']);
        } else if ($bulan == 'Agustus') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus']);
        } else if ($bulan == 'September') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September']);
        } else if ($bulan == 'Oktober') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober']);
        } else if ($bulan == 'November') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November']);
        } else if ($bulan == 'Desember') {
            $sppLs = $sppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
        }

        $sppLs = $sppLs->sum('anggaran_digunakan');

        $totalSpp = ($sppLs);
        $jumlahAnggaran = (($spd->jumlah_anggaran ?? 0) - $totalSpp);

        return response()->json([
            'jumlah_anggaran' => $jumlahAnggaran,
        ]);
    }

    public function tabelDpaDebug(Request $request)
    {
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

        $bulanDari = 'Januari';
        $bulanSampai = 'Desember';

        // Find the indices of $bulanDari and $bulanSampai in the $bulan array
        $bulanDariIndex = array_search($bulanDari, $arrayBulan);
        $bulanSampaiIndex = array_search($bulanSampai, $arrayBulan);

        // Extract the elements from $bulanDari to $bulanSampai using array_slice
        $listBulan = array_slice($arrayBulan, $bulanDariIndex, $bulanSampaiIndex - $bulanDariIndex + 1);

        // $tahun = $request->tahun;
        $tahun = '8fef08db-e1bf-4a1f-8bd2-9809d5e60426';
        // $sekretariatDaerahId = $request->sekretariatDaerah;
        $sekretariatDaerahId = 'Semua';

        $sekretariatDaerahS = SekretariatDaerah::where(function ($query) use ($sekretariatDaerahId) {
            if ($sekretariatDaerahId != "Semua") {
                $query->where('id', $sekretariatDaerahId);
            }
        })->orderBy('nama', 'asc')->get();

        $array = [];

        $array = [
            'bulan' => $listBulan
        ];
        foreach ($sekretariatDaerahS as $sekretariatDaerah) {
            $programDpa = ProgramDpa::with('kegiatanDpa')->whereHas('kegiatanDpa', function ($query) use ($sekretariatDaerah, $tahun) {
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
                $kegiatanDpa = KegiatanDpa::where('program_dpa_id', $program->id)->whereHas('sppLs', function ($query) use ($sekretariatDaerah, $tahun) {
                    $query->where('tahun_id', $tahun);
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                })->orderBy('no_rek', 'asc')->get();

                foreach ($kegiatanDpa as $kegiatan) {
                    $bulanData = [];
                    $spd = Spd::where('sekretariat_daerah_id', $sekretariatDaerah->id)->where('tahun_id', $tahun)->where('kegiatan_dpa_id', $kegiatan->id)->first();
                    $sisaAnggaran = $spd->jumlah_anggaran ?? 0;
                    foreach ($listBulan as $bulan) {
                        $sppLs = SppLs::where('kegiatan_dpa_id', $kegiatan->id)->where('bulan', $bulan)->whereNotNull('dokumen_spm')
                            ->whereNotNull('dokumen_arsip_sp2d')->where('tahun_id', $tahun)->first();

                        $sisaAnggaran -= ($sppLs ? $sppLs->anggaran_digunakan : 0);

                        $bulanData[] = [
                            'nama' => $bulan,
                            'anggaran_digunakan' => $sppLs ? $sppLs->anggaran_digunakan : 0,
                            'sisa_anggaran' =>  $sisaAnggaran,
                        ];
                    }

                    $kegiatanData[] = [
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

        $totalPerencanaanAnggaran = 0;
        $totalAnggaranDigunakan = 0;
        $totalSisaAnggaran = 0;

        foreach ($array['data'] as &$sekretariat) {
            foreach ($sekretariat['program'] as &$program) {
                foreach ($program['kegiatan'] as &$kegiatan) {
                    foreach ($kegiatan['bulan'] as &$bulanData) {
                        // Accumulate the values for each month
                        $totalAnggaranDigunakan += $bulanData['anggaran_digunakan'];
                        $totalSisaAnggaran += $bulanData['sisa_anggaran'];
                    }
                }
            }
        }
        foreach ($array['data'] as &$sekretariat) {
            foreach ($sekretariat['program'] as &$program) {
                foreach ($program['kegiatan'] as &$kegiatan) {
                    // Initialize sums for each kegiatan
                    $sum_anggaran_digunakan_kegiatan = 0;
                    $sum_sisa_anggaran_kegiatan = 0;

                    foreach ($kegiatan['bulan'] as &$bulanData) {
                        // Accumulate the values for each kegiatan
                        $sum_anggaran_digunakan_kegiatan += $bulanData['anggaran_digunakan'];
                        $sum_sisa_anggaran_kegiatan += $bulanData['sisa_anggaran'];
                    }

                    // Add the sums to the 'kegiatan' array
                    $kegiatan['total_anggaran_digunakan'] = $sum_anggaran_digunakan_kegiatan;
                    $kegiatan['total_sisa_anggaran'] = $sum_sisa_anggaran_kegiatan;
                }
            }
        }

        // Loop through each sekretariat_daerah
        foreach ($array['data'] as &$sekretariat) {
            // Loop through each program under the sekretariat_daerah
            foreach ($sekretariat['program'] as &$program) {
                $totalBulan = null;
                // Initialize sums for each program
                $sum_perencanaan_anggaran_program = array_fill(0, count($listBulan), 0);
                $sum_anggaran_digunakan_program = array_fill(0, count($listBulan), 0);
                $sum_sisa_anggaran_program = array_fill(0, count($listBulan), 0);

                $jumlahAnggaran = 0;
                foreach ($program['kegiatan'] as &$kegiatan) {
                    $jumlahAnggaran += $kegiatan['jumlah_anggaran'];
                    // Loop through each month data under the kegiatan
                    foreach ($kegiatan['bulan'] as $index => $bulanData) {
                        // Accumulate the values for each month in each program
                        $totalBulan[$index] = [
                            'nama' => $bulanData['nama'],
                            'anggaran_digunakan' => ($totalBulan[$index]['anggaran_digunakan'] ?? 0) + $bulanData['anggaran_digunakan'],
                            'sisa_anggaran' => ($totalBulan[$index]['sisa_anggaran'] ?? 0) + $bulanData['sisa_anggaran']
                        ];
                        // $sum_perencanaan_anggaran_program[$index] += $bulanData['perencanaan_anggaran'];
                        // $sum_anggaran_digunakan_program[$index] += $bulanData['anggaran_digunakan'];
                        // $sum_sisa_anggaran_program[$index] += $bulanData['sisa_anggaran'];
                    }
                }

                $anggaranDigunakan = 0;
                $sisaAnggaran = 0;
                // foreach ($totalBulan as $bulanData) {
                //     $anggaranDigunakan += $bulanData['anggaran_digunakan'];
                //     $sisaAnggaran += $bulanData['sisa_anggaran'];
                // }
                $program['total_bulan'] = [
                    'bulan' => $totalBulan,
                    // 'anggaran_digunakan' => $anggaranDigunakan,
                    // 'sisa_anggaran' => $sisaAnggaran
                ];
                $program['jumlah_anggaran'] = $jumlahAnggaran;
            }
        }


        // echo "<pre>";
        // print_r($array);
        // echo "</pre>";
        // die;

        return view('dashboard.pages.dpa.tabel.indexDebug', compact(['array']));
    }

    public function tabelDpa(Request $request)
    {
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
        $sekretariatDaerahId = $request->sekretariat_daerah;
        $jenisSpp = $request->jenis_spp;

        if ($jenisSpp == "SPP-GU") {
            $array = $this->_tabelDpaSppGu($sekretariatDaerahId, $tahun, $bulanDari, $bulanSampai, $listBulan);
            return view('dashboard.components.widgets.tabelSpdDebug', compact(['array']))->render();
        } else {
            $array = $this->_tabelDpaSppLs($sekretariatDaerahId, $tahun, $bulanDari, $bulanSampai, $listBulan);
            return view('dashboard.components.widgets.tabelSpdSppLsDebug', compact(['array']))->render();
        }
    }

    private function _tabelDpaSppGu($sekretariatDaerahId, $tahun, $bulanDari, $bulanSampai, $listBulan)
    {

        $sekretariatDaerahS = SekretariatDaerah::where(function ($query) use ($sekretariatDaerahId) {
            if (isset($sekretariatDaerahId) && $sekretariatDaerahId != "Semua") {
                $query->where('id', $sekretariatDaerahId);
            }
        })->get();


        $array = [];

        if ($tahun && $bulanDari && $bulanSampai) {
            $array = [
                'bulan' => $listBulan
            ];
            foreach ($sekretariatDaerahS as $sekretariatDaerah) {
                $programDpa = ProgramDpa::with('kegiatanDpa')->whereHas('kegiatanDpa', function ($query) use ($sekretariatDaerah, $tahun) {
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
                    $kegiatanDpa = KegiatanDpa::where('program_dpa_id', $program->id)->whereHas('sppGu', function ($query) use ($sekretariatDaerah, $tahun) {
                        $query->where('tahun_id', $tahun);
                        $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                    })->orderBy('no_rek', 'asc')->get();

                    foreach ($kegiatanDpa as $kegiatan) {
                        $bulanData = [];
                        foreach ($listBulan as $bulan) {
                            $sppGu = SppGu::where('kegiatan_dpa_id', $kegiatan->id)->where('bulan', $bulan)->where('tahap', 'Selesai')->where('tahun_id', $tahun)->first();

                            $bulanData[] = [
                                'nama' => $bulan,
                                'perencanaan_anggaran' => $sppGu ? $sppGu->perencanaan_anggaran : 0,
                                'anggaran_digunakan' => $sppGu ? $sppGu->anggaran_digunakan : 0,
                                'sisa_anggaran' => ($sppGu->perencanaan_anggaran - $sppGu->anggaran_digunakan) ?? 0
                            ];
                        }

                        $kegiatanData[] = [
                            'nama' => $kegiatan->nama,
                            'no_rek' => $kegiatan->no_rek,
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

            $totalPerencanaanAnggaran = 0;
            $totalAnggaranDigunakan = 0;
            $totalSisaAnggaran = 0;

            foreach ($array['data'] as &$sekretariat) {
                foreach ($sekretariat['program'] as &$program) {
                    foreach ($program['kegiatan'] as &$kegiatan) {
                        foreach ($kegiatan['bulan'] as &$bulanData) {
                            $totalPerencanaanAnggaran += $bulanData['perencanaan_anggaran'];
                            $totalAnggaranDigunakan += $bulanData['anggaran_digunakan'];
                            $totalSisaAnggaran += $bulanData['sisa_anggaran'];
                        }
                    }
                }
            }
            foreach ($array['data'] as &$sekretariat) {
                foreach ($sekretariat['program'] as &$program) {
                    foreach ($program['kegiatan'] as &$kegiatan) {
                        $sum_perencanaan_anggaran_kegiatan = 0;
                        $sum_anggaran_digunakan_kegiatan = 0;
                        $sum_sisa_anggaran_kegiatan = 0;

                        foreach ($kegiatan['bulan'] as &$bulanData) {
                            $sum_perencanaan_anggaran_kegiatan += $bulanData['perencanaan_anggaran'];
                            $sum_anggaran_digunakan_kegiatan += $bulanData['anggaran_digunakan'];
                            $sum_sisa_anggaran_kegiatan += $bulanData['sisa_anggaran'];
                        }

                        $kegiatan['total_perencanaan_anggaran'] = $sum_perencanaan_anggaran_kegiatan;
                        $kegiatan['total_anggaran_digunakan'] = $sum_anggaran_digunakan_kegiatan;
                        $kegiatan['total_sisa_anggaran'] = $sum_sisa_anggaran_kegiatan;
                    }
                }
            }

            foreach ($array['data'] as &$sekretariat) {
                foreach ($sekretariat['program'] as &$program) {
                    $totalBulan = null;
                    $sum_perencanaan_anggaran_program = array_fill(0, count($listBulan), 0);
                    $sum_anggaran_digunakan_program = array_fill(0, count($listBulan), 0);
                    $sum_sisa_anggaran_program = array_fill(0, count($listBulan), 0);

                    foreach ($program['kegiatan'] as &$kegiatan) {
                        foreach ($kegiatan['bulan'] as $index => $bulanData) {
                            $totalBulan[$index] = [
                                'nama' => $bulanData['nama'],
                                'perencanaan_anggaran' => ($totalBulan[$index]['perencanaan_anggaran'] ?? 0) + $bulanData['perencanaan_anggaran'],
                                'anggaran_digunakan' => ($totalBulan[$index]['anggaran_digunakan'] ?? 0) + $bulanData['anggaran_digunakan'],
                                'sisa_anggaran' => ($totalBulan[$index]['sisa_anggaran'] ?? 0) + $bulanData['sisa_anggaran']
                            ];
                        }
                    }

                    $perencanaanAnggaran = 0;
                    $anggaranDigunakan = 0;
                    $sisaAnggaran = 0;
                    foreach ($totalBulan as $bulanData) {
                        $perencanaanAnggaran += $bulanData['perencanaan_anggaran'];
                        $anggaranDigunakan += $bulanData['anggaran_digunakan'];
                        $sisaAnggaran += $bulanData['sisa_anggaran'];
                    }
                    $program['total_bulan'] = [
                        'bulan' => $totalBulan,
                        'perencanaan_anggaran' => $perencanaanAnggaran,
                        'anggaran_digunakan' => $anggaranDigunakan,
                        'sisa_anggaran' => $sisaAnggaran
                    ];
                }
            }
        }

        return $array;
    }

    private function _tabelDpaSppLs($sekretariatDaerahId, $tahun, $bulanDari, $bulanSampai, $listBulan)
    {
        $sekretariatDaerahS = SekretariatDaerah::where(function ($query) use ($sekretariatDaerahId) {
            if ($sekretariatDaerahId != "Semua") {
                $query->where('id', $sekretariatDaerahId);
            }
        })->orderBy('nama', 'asc')->get();

        $array = [];

        if ($tahun && $bulanDari && $bulanSampai) {
            $array = [
                'bulan' => $listBulan
            ];
            foreach ($sekretariatDaerahS as $sekretariatDaerah) {
                $programDpa = ProgramDpa::with('kegiatanDpa')->whereHas('kegiatanDpa', function ($query) use ($sekretariatDaerah, $tahun) {
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
                    $kegiatanDpa = KegiatanDpa::where('program_dpa_id', $program->id)->whereHas('sppLs', function ($query) use ($sekretariatDaerah, $tahun) {
                        $query->where('tahun_id', $tahun);
                        $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
                    })->orderBy('no_rek', 'asc')->get();

                    foreach ($kegiatanDpa as $kegiatan) {
                        $bulanData = [];
                        $spd = Spd::where('sekretariat_daerah_id', $sekretariatDaerah->id)->where('tahun_id', $tahun)->where('kegiatan_dpa_id', $kegiatan->id)->first();
                        $sisaAnggaran = $spd->jumlah_anggaran ?? 0;
                        foreach ($listBulan as $bulan) {
                            $sppLs = SppLs::where('kegiatan_dpa_id', $kegiatan->id)->where('bulan', $bulan)->whereNotNull('dokumen_spm')
                                ->whereNotNull('dokumen_arsip_sp2d')->where('tahun_id', $tahun)->first();

                            $sisaAnggaran -= ($sppLs ? $sppLs->anggaran_digunakan : 0);

                            $bulanData[] = [
                                'nama' => $bulan,
                                'anggaran_digunakan' => $sppLs ? $sppLs->anggaran_digunakan : 0,
                                'sisa_anggaran' =>  $sisaAnggaran,
                            ];
                        }

                        $kegiatanData[] = [
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
        }

        return $array;
    }

    public function exportSpd(Request $request)
    {
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
        $sekretariatDaerahId = $request->sekretariat_daerah;
        $jenisSpp = $request->jenis_spp;

        $array = [];
        if ($jenisSpp == "SPP-GU") {
            $array = $this->_tabelDpaSppGu($sekretariatDaerahId, $tahun, $bulanDari, $bulanSampai, $listBulan);
        } else {
            $array = $this->_tabelDpaSppLs($sekretariatDaerahId, $tahun, $bulanDari, $bulanSampai, $listBulan);
        }

        $tanggal = Carbon::parse(Carbon::now())->translatedFormat('d F Y');

        return Excel::download(new TabelDpaExport($jenisSpp, $array), "Export" . "-" . $tanggal . "-" . rand(1, 9999) . '.xlsx');

        // return view('dashboard.components.widgets.tabelSpd', compact(['daftarSekretariatDaerah', 'tahun']))->render();
    }
}
