<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DaftarDokumenSppUp;
use App\Models\DokumenSppUp;
use App\Models\ProgramSpp;
use App\Models\RiwayatSppUp;
use App\Models\SppUp;
use App\Models\Tahun;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SppUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $SekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah_id : Auth::user()->profil->sekretariat_daerah_id;

        if ($request->ajax()) {
            $data = SppUp::where(function ($query) use ($request, $SekretariatDaerah, $role) {
                if ($SekretariatDaerah && $SekretariatDaerah != 'Semua') {
                    $query->where('sekretariat_daerah_id', $SekretariatDaerah);
                }


                if ($request->tahun && $request->tahun != 'Semua') {
                    $query->where('tahun_id', $request->tahun);
                }

                if ($request->status && $request->status != 'Semua') {
                    if ($request->status == "Belum Diproses") {
                        if ($role == "ASN Sub Bagian Keuangan") {
                            $query->where('status_validasi_asn', 0);
                        } else if ($role == "PPK") {
                            $query->where('status_validasi_ppk', 0);
                        } else {
                            $query->where('status_validasi_asn', 0)->where('status_validasi_ppk', 0);
                        }
                    } else if ($request->status == "Ditolak") {
                        if ($role == "ASN Sub Bagian Keuangan") {
                            $query->where('status_validasi_asn', 2);
                        } else if ($role == "PPK") {
                            $query->where('status_validasi_ppk', 2);
                        } else {
                            $query->where('status_validasi_asn', 2);
                            $query->orWhere('status_validasi_ppk', 2);
                        }
                    } else {
                        $query->where('status_validasi_akhir', 1);
                    }
                }

                if ($request->search) {
                    $query->whereHas('kegiatan', function ($query) use ($request) {
                        $query->where('nama', 'like', "%" . $request->search . "%");
                        $query->orWhere('no_rek', 'like', "%" . $request->search . "%");
                        $query->orWhereHas('program', function ($query) use ($request) {
                            $query->where('nama', 'like', "%" .  $request->search . "%");
                            $query->orWhere('no_rek', 'like', "%" . $request->search . "%");
                        });
                    });
                }
            })->orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal_dibuat', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d F Y');
                })
                ->addColumn('nama', function ($row) {
                    $nama = $row->kegiatanSpp->nama . " (" . $row->kegiatanSpp->no_rek . ")";
                    return $nama;
                })
                ->addColumn('program', function ($row) {
                    $nama = $row->kegiatanSpp->programSpp->nama . " (" . $row->kegiatanSpp->programSpp->no_rek . ")";
                    return $nama;
                })
                ->addColumn('periode', function ($row) {

                    $periode = $row->tahun->tahun;
                    return $periode;
                })
                ->addColumn('sekretariat_daerah', function ($row) {

                    $SekretariatDaerah = $row->SekretariatDaerah->nama;
                    return $SekretariatDaerah;
                })
                ->addColumn('jumlah_anggaran', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah_anggaran, 0, ',', '.');
                })
                ->addColumn('riwayat', function ($row) {
                    $actionBtn = '<a href="' . url('spp-up/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';
                    return $actionBtn;
                })
                ->addColumn('verifikasi_asn', function ($row) {
                    if ($row->status_validasi_asn == 0) {
                        $actionBtn = '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else if ($row->status_validasi_asn == 1) {
                        $actionBtn = '<span class="badge badge-success">Diterima</span>';
                    } else if ($row->status_validasi_asn == 2) {
                        $actionBtn = '<span class="badge badge-danger">Ditolak</span>';
                    }
                    return $actionBtn;
                })
                ->addColumn('verifikasi_ppk', function ($row) {
                    if ($row->status_validasi_ppk == 0) {
                        $actionBtn = '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else if ($row->status_validasi_ppk == 1) {
                        $actionBtn = '<span class="badge badge-success">Diterima</span>';
                    } else if ($row->status_validasi_ppk == 2) {
                        $actionBtn = '<span class="badge badge-danger">Ditolak</span>';
                    }
                    return $actionBtn;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';

                    if ($row->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id  || in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {

                        if (($row->status_validasi_asn != 0 && $row->status_validasi_ppk != 0) && (($row->status_validasi_asn == 2 || $row->status_validasi_ppk == 2))) {
                            $actionBtn .= '<div class="d-flex justify-content-center mb-1"><a href="' . url('/surat-penolakan/spp-up/' . $row->id . '/' . $row->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                            $actionBtn .= '<a href="' . url('spp-up/' . $row->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                        }
                    }


                    if ($row->status_validasi_akhir == 1) {
                        $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-up/' . $row->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                    }

                    if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';

                        if (($row->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $row->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id) || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0 && Auth::user()->is_aktif  == 1)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-up/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0 && Auth::user()->is_aktif  == 1) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->status_validasi_asn == 1) && (Auth::user()->is_aktif  == 1)) {
                                $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $row->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                            }
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }


                    $actionBtn .= '<a href="' . url('spp-up/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';

                    return $actionBtn;
                })
                ->addColumn('status_verifikasi_akhir', function ($row) {
                    if ($row->status_validasi_akhir == 0) {
                        return '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else {
                        return '<span class="badge badge-success">Diverifikasi</span>';
                    }
                })
                ->rawColumns(['action', 'riwayat', 'verifikasi_asn', 'verifikasi_ppk', 'sekretariat_daerah', 'periode', 'program', 'nama', 'jumlah_anggaran', 'status_verifikasi_akhir'])
                ->make(true);
        }

        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();

        return view('dashboard.pages.spp.sppUp.index', compact('daftarSekretariatDaerah', 'daftarTahun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarDokumenSppUp = DaftarDokumenSppUp::orderBy('created_at', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarProgram = ProgramSpp::orderBy('nama', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppUp.create', compact(['daftarDokumenSppUp', 'daftarTahun', 'daftarProgram', 'daftarSekretariatDaerah']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $rules = [
            'tahun' => 'required',
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'program' => 'required',
            'kegiatan' => 'required',
            'jumlah_anggaran' => 'required',
            'nomor_surat' => 'required',
        ];

        $messages = [
            'nama_kegiatan.required' => 'Nama Kegiatan Tidak Boleh Kosong',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
            'program.required' => 'Program Tidak Boleh Kosong',
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'kegiatan.required' => 'Kegiatan Tidak Boleh Kosong',
            'jumlah_anggaran.required' => 'Jumlah Anggaran Tidak Boleh Kosong',
            'nomor_surat.required' => 'Nomor Surat Tidak Boleh Kosong',
        ];

        if ($request->fileDokumen) {
            foreach ($request->fileDokumen as $dokumen) {
                $rules["$dokumen"] = 'required|mimes:pdf|max:5120';
                $messages["$dokumen.required"] = "File tidak boleh kosong";
                $messages["$dokumen.mimes"] = "File harus berupa file pdf";
                $messages["$dokumen.max"] = "File tidak boleh lebih dari 5 MB";
            }
        }

        if ($request->namaFile) {
            foreach ($request->namaFile as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Nama tidak boleh kosong";
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$request->fileDokumen) {
            $validator->after(function ($validator) {
                $validator->errors()->add('dokumenFileHitung', 'Dokumen Minimal 1');
            });
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, $role) {
                $sppUp = new SppUp();
                $sppUp->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppUp->tahun_id = $request->tahun;
                $sppUp->kegiatan_spp_id = $request->kegiatan;
                $sppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $sppUp->user_id = Auth::user()->id;
                $sppUp->nomor_surat = $request->nomor_surat;
                $sppUp->save();

                foreach ($request->fileDokumen as $index => $nama) {
                    $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->$nama->storeAs('dokumen_spp_up', $namaFileBerkas);
                    $arrayFileDokumen[] = $namaFileBerkas;

                    $dokumenSppUp = new DokumenSppUp();
                    $dokumenSppUp->nama_dokumen = $request[$request->namaFile[$index]];
                    $dokumenSppUp->dokumen = $namaFileBerkas;
                    $dokumenSppUp->spp_up_id = $sppUp->id;
                    $dokumenSppUp->save();
                }

                $riwayatSppUp = new RiwayatSppUp();
                $riwayatSppUp->spp_up_id = $sppUp->id;
                $riwayatSppUp->user_id = Auth::user()->id;
                $riwayatSppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $riwayatSppUp->status = 'Dibuat';
                $riwayatSppUp->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_up/' . $nama)) {
                    Storage::delete('dokumen_spp_up/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(SppUp $sppUp)
    {
        $tipe = 'spp_up';
        $jumlahAnggaran = 'Rp. ' . number_format($sppUp->jumlah_anggaran, 0, ',', '.');

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppUp.show', compact(['sppUp', 'tipe', 'jumlahAnggaran']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function edit(SppUp $sppUp, Request $request)
    {
        $role = Auth::user()->role;
        if (($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) && ($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2)) {
            return view('dashboard.pages.spp.sppUp.edit', compact(['sppUp', 'request']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function update(Request $request, SppUp $sppUp)
    {
        $role = Auth::user()->role;

        $rules = [
            'surat_penolakan' => 'required|mimes:pdf|max:5120',
            'jumlah_anggaran' => 'required',
        ];

        $messages = [
            'surat_penolakan.required' => 'Surat Penolakan tidak boleh kosong',
            'surat_penolakan.mimes' => 'Dokumen Harus Berupa File PDF',
            'surat_penolakan.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
            'jumlah_anggaran.required' => 'Jumlah Anggaran tidak boleh kosong',
        ];

        if ($request->fileDokumenUpdate) {
            foreach ($request->fileDokumenUpdate as $dokumen) {
                $dokumen = "'" . $dokumen . "'";
                $rules["$dokumen"] = $request["$dokumen"] ? 'required|mimes:pdf|max:5120' : 'nullable';
                $messages["$dokumen.required"] = "File tidak boleh kosong";
                $messages["$dokumen.mimes"] = "File harus berupa file pdf";
                $messages["$dokumen.max"] = "File tidak boleh lebih dari 5 MB";
            }
        }

        if ($request->namaFileUpdate) {
            foreach ($request->namaFileUpdate as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Nama tidak boleh kosong";
            }
        }

        if ($request->fileDokumen) {
            foreach ($request->fileDokumen as $dokumen) {
                $rules["$dokumen"] = 'required|mimes:pdf|max:5120';
                $messages["$dokumen.required"] = "File tidak boleh kosong";
                $messages["$dokumen.mimes"] = "File harus berupa file pdf";
                $messages["$dokumen.max"] = "File tidak boleh lebih dari 5 MB";
            }
        }

        if ($request->namaFile) {
            foreach ($request->namaFile as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Nama tidak boleh kosong";
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$request->fileDokumen && !$request->fileDokumenUpdate) {
            $validator->after(function ($validator) {
                $validator->errors()->add('dokumenFileHitung', 'Dokumen Minimal 1');
            });
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];
        $arrayFileDokumenSebelumnya = [];
        $arrayFileDokumenUpdate = [];
        $arrayFileDokumenHapus = [];

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, $sppUp) {
                if ($request->fileDokumenUpdate) {
                    $daftarDokumenSppUp = DokumenSppUp::where('spp_up_id', $sppUp->id)->whereNotIn('id', $request->fileDokumenUpdate)->get();
                    foreach ($daftarDokumenSppUp as $dokumen) {
                        $arrayFileDokumenHapus[] = $dokumen->dokumen;
                        $dokumen->delete();
                    }

                    foreach ($request->fileDokumenUpdate as $index => $id) {
                        $dokumenSppUp = DokumenSppUp::where('id', $id)->first();
                        $dokumenSppUp->nama_dokumen = $request[$request->namaFileUpdate[$index]];

                        if ($request["$id"]) {
                            $namaFile = Str::slug($request->namaFileUpdate[$index], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                            $request->$id->storeAs('dokumen_spp_up/', $namaFile);
                            $arrayFileDokumenUpdate[] = $namaFile;
                            $arrayFileDokumenSebelumnya[] = $dokumenSppUp->dokumen;

                            $dokumenSppUp->dokumen = $namaFile;
                        }
                        $dokumenSppUp->save();
                    }
                }

                if ($request->fileDokumen) {
                    foreach ($request->fileDokumen as $index => $nama) {
                        $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->$nama->storeAs('dokumen_spp_up', $namaFileBerkas);
                        $arrayFileDokumen[] = $namaFileBerkas;

                        $dokumenSppUp = new DokumenSppUp();
                        $dokumenSppUp->nama_dokumen = $request[$request->namaFile[$index]];
                        $dokumenSppUp->dokumen = $namaFileBerkas;
                        $dokumenSppUp->spp_up_id = $sppUp->id;
                        $dokumenSppUp->save();
                    }
                }


                $riwayatSppUp = new RiwayatSppUp();

                if ($request->file('surat_penolakan')) {
                    $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->file('surat_penolakan')->storeAs(
                        'surat_penolakan_spp_up',
                        $namaFileBerkas
                    );
                    $riwayatSppUp->surat_penolakan = $namaFileBerkas;
                    $sppUp->surat_penolakan = $namaFileBerkas;
                }

                if ($sppUp->status_validasi_ppk == 2) {
                    $sppUp->status_validasi_ppk = 0;
                    $sppUp->alasan_validasi_ppk = null;
                }

                if ($sppUp->status_validasi_asn == 2) {
                    $sppUp->status_validasi_asn = 0;
                    $sppUp->alasan_validasi_asn = null;
                }
                $sppUp->tahap_riwayat = $sppUp->tahap_riwayat + 1;
                $sppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $sppUp->save();


                $riwayatSppUp->spp_up_id = $sppUp->id;
                $riwayatSppUp->user_id = Auth::user()->id;
                $riwayatSppUp->status = 'Diperbaiki';
                $riwayatSppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $riwayatSppUp->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_up/' . $nama)) {
                    Storage::delete('dokumen_spp_up/' . $nama);
                }
            }

            foreach ($arrayFileDokumenUpdate as $nama) {
                if (Storage::exists('dokumen_spp_up/' . $nama)) {
                    Storage::delete('dokumen_spp_up/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        foreach ($arrayFileDokumenSebelumnya as $nama) {
            if (Storage::exists('dokumen_spp_up/' . $nama)) {
                Storage::delete('dokumen_spp_up/' . $nama);
            }
        }

        foreach ($arrayFileDokumenHapus as $nama) {
            if (Storage::exists('dokumen_spp_up/' . $nama)) {
                Storage::delete('dokumen_spp_up/' . $nama);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(SppUp $sppUp)
    {
        $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $sppUp->id)->whereNotNull('surat_penolakan')->get();

        $arraySuratPenolakan = null;

        $arrayDokumen = $sppUp->dokumenSppUp->pluck('dokumen');
        if ($riwayatSppUp) {
            $arraySuratPenolakan = $riwayatSppUp->pluck('surat_penolakan');
        }

        try {
            DB::transaction(
                function () use ($sppUp) {
                    $sppUp->delete();
                    $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $sppUp->id)->delete();
                    $dokumenSppUp = DokumenSppUp::where('spp_up_id', $sppUp->id)->delete();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        if (count($arraySuratPenolakan) > 0) {
            foreach ($arraySuratPenolakan as $suratPenolakan) {
                Storage::delete('surat_penolakan_spp_up/' . $suratPenolakan);
            }
        }

        if (count($arrayDokumen) > 0) {
            foreach ($arrayDokumen as $dokumen) {
                Storage::delete('dokumen_spp_up/' . $dokumen);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppUp $sppUp)
    {
        $tipeSuratPenolakan = 'spp-up';
        $tipeSuratPengembalian = 'spp_up';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppUp.riwayat', compact(['sppUp', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function verifikasi(Request $request, SppUp $sppUp)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'verifikasi' => 'required',
                'alasan' => $request->verifikasi == '1' ? 'nullable' : 'required',
            ],
            [
                'verifikasi.required' => 'Verifikasi Harus Dipilih',
                'alasan.required' => 'Alasan Harus Diisi',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            DB::transaction(
                function () use ($sppUp, $request) {

                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppUp->status_validasi_asn = $request->verifikasi;
                        $sppUp->alasan_validasi_asn = $request->alasan;
                        $sppUp->tanggal_validasi_asn = Carbon::now();
                    } else {
                        $sppUp->status_validasi_ppk = $request->verifikasi;
                        $sppUp->alasan_validasi_ppk = $request->alasan;
                        $sppUp->tanggal_validasi_ppk = Carbon::now();
                    }
                    $sppUp->save();

                    $riwayatTerakhir = RiwayatSppUp::whereNotNull('nomor_surat')->where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $sppUp->tahap_riwayat)->first();

                    $riwayatSppUp = new RiwayatSppUp();
                    $riwayatSppUp->spp_up_id = $sppUp->id;
                    $riwayatSppUp->user_id = Auth::user()->id;
                    $riwayatSppUp->tahap_riwayat = $sppUp->tahap_riwayat;
                    $riwayatSppUp->jumlah_anggaran = str_replace(".", "", $sppUp->jumlah_anggaran);
                    $riwayatSppUp->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
                    if ($request->verifikasi == 2) {
                        $nomorSurat = DB::table('riwayat_spp_up')
                            ->select(['spp_up_id', 'tahap_riwayat'], DB::raw('count(*) as total'))
                            ->groupBy(['spp_up_id', 'tahap_riwayat'])
                            ->whereNotNull('nomor_surat')
                            ->get()
                            ->count();
                        $riwayatSppUp->nomor_surat = $riwayatTerakhir ? $riwayatTerakhir->nomor_surat : ($nomorSurat + 1) . "/SPP-UP/P/" . Carbon::now()->format('m') . "/" . Carbon::now()->format('Y');
                    }
                    $riwayatSppUp->alasan = $request->alasan;
                    $riwayatSppUp->role = Auth::user()->role;
                    $riwayatSppUp->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppUp $sppUp)
    {
        try {
            DB::transaction(
                function () use ($sppUp) {
                    $sppUp->status_validasi_akhir = 1;
                    $sppUp->tanggal_validasi_akhir = Carbon::now();
                    $sppUp->save();

                    $riwayatSppLs = new RiwayatSppUp();
                    $riwayatSppLs->spp_up_id = $sppUp->id;
                    $riwayatSppLs->jumlah_anggaran = $sppUp->jumlah_anggaran;
                    $riwayatSppLs->user_id = Auth::user()->id;
                    $riwayatSppLs->status = 'Diselesaikan';
                    $riwayatSppLs->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
