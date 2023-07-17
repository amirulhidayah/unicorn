<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DaftarDokumenSppTu;
use App\Models\DokumenSppTu;
use App\Models\ProgramSpp;
use App\Models\RiwayatSppTu;
use App\Models\SppTu;
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

class SppTuController extends Controller
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
            $data = SppTu::where(function ($query) use ($request, $SekretariatDaerah, $role) {
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
                    $periode = $row->bulan . ", " . $row->tahun->tahun;
                    return $periode;
                })
                ->addColumn('sekretariat_daerah', function ($row) {

                    $SekretariatDaerah = $row->SekretariatDaerah->nama;
                    return $SekretariatDaerah;
                })
                ->addColumn('jumlah_anggaran', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah_anggaran, 0, ',', '.');
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
                            $actionBtn .= '<div class="d-flex justify-content-center mb-1"><a href="' . url('/surat-penolakan/spp-tu/' . $row->id . '/' . $row->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                            $actionBtn .= '<a href="' . url('spp-tu/' . $row->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                        }
                    }


                    if ($row->status_validasi_akhir == 1) {
                        $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-tu/' . $row->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                    }

                    if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';

                        if (($row->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $row->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id) || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0 && Auth::user()->is_aktif  == 1)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-tu/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0 && Auth::user()->is_aktif  == 1) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->status_validasi_asn == 1) && (Auth::user()->is_aktif  == 1)) {
                                $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $row->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                            }
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }


                    $actionBtn .= '<a href="' . url('spp-tu/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';

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

        return view('dashboard.pages.spp.sppTu.index', compact('daftarSekretariatDaerah', 'daftarTahun'));
    }

    public function create()
    {
        $daftarDokumenSppTu = DaftarDokumenSppTu::orderBy('created_at', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarProgram = ProgramSpp::orderBy('nama', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.sppTu.create', compact(['daftarDokumenSppTu', 'daftarTahun', 'daftarProgram', 'daftarSekretariatDaerah']));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $rules = [
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'tahun' => 'required',
            'program' => 'required',
            'kegiatan' => 'required',
            'jumlah_anggaran' => 'required',
            'bulan' => 'required',
            'nomor_surat' => 'required',
        ];

        $messages = [
            'nama_kegiatan.required' => 'Nama Kegiatan Tidak Boleh Kosong',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
            'program.required' => 'Program Tidak Boleh Kosong',
            'kegiatan.required' => 'Kegiatan Tidak Boleh Kosong',
            'jumlah_anggaran.required' => 'Jumlah Anggaran Tidak Boleh Kosong',
            'bulan.required' => 'Bulan Tidak Boleh Kosong',
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
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
                $sppTu = new SppTu();
                $sppTu->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppTu->tahun_id = $request->tahun;
                $sppTu->kegiatan_spp_id = $request->kegiatan;
                $sppTu->user_id = Auth::user()->id;
                $sppTu->nomor_surat = $request->nomor_surat;
                $sppTu->bulan = $request->bulan;
                $sppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $sppTu->save();

                foreach ($request->fileDokumen as $index => $nama) {
                    $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->$nama->storeAs('dokumen_spp_tu', $namaFileBerkas);
                    $arrayFileDokumen[] = $namaFileBerkas;

                    $dokumenSppTu = new DokumenSppTu();
                    $dokumenSppTu->nama_dokumen = $request[$request->namaFile[$index]];
                    $dokumenSppTu->dokumen = $namaFileBerkas;
                    $dokumenSppTu->spp_tu_id = $sppTu->id;
                    $dokumenSppTu->save();
                }

                $riwayatSppTu = new RiwayatSppTu();
                $riwayatSppTu->spp_tu_id = $sppTu->id;
                $riwayatSppTu->user_id = Auth::user()->id;
                $riwayatSppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $riwayatSppTu->status = 'Dibuat';
                $riwayatSppTu->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_tu/' . $nama)) {
                    Storage::delete('dokumen_spp_tu/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(SppTu $sppTu)
    {
        $role = Auth::user()->role;
        $tipe = 'spp_tu';
        $jumlahAnggaran = 'Rp. ' . number_format($sppTu->jumlah_anggaran, 0, ',', '.');
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppTu.show', compact(['sppTu', 'tipe', 'jumlahAnggaran']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        };
    }

    public function edit(SppTu $sppTu, Request $request)
    {
        $role = Auth::user()->role;
        if (($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) && ($sppTu->status_validasi_asn == 2 || $sppTu->status_validasi_ppk == 2)) {
            return view('dashboard.pages.spp.sppTu.edit', compact(['sppTu', 'request']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function update(Request $request, SppTu $sppTu)
    {
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
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, $sppTu) {
                if ($request->fileDokumenUpdate) {
                    $daftarDokumenSppTu = DokumenSppTu::where('spp_tu_id', $sppTu->id)->whereNotIn('id', $request->fileDokumenUpdate)->get();
                    foreach ($daftarDokumenSppTu as $dokumen) {
                        $arrayFileDokumenHapus[] = $dokumen->dokumen;
                        $dokumen->delete();
                    }

                    foreach ($request->fileDokumenUpdate as $index => $id) {
                        $dokumenSppTu = DokumenSppTu::where('id', $id)->first();
                        $dokumenSppTu->nama_dokumen = $request[$request->namaFileUpdate[$index]];

                        if ($request["$id"]) {
                            $namaFile = Str::slug($request->namaFileUpdate[$index], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                            $request->$id->storeAs('dokumen_spp_tu/', $namaFile);
                            $arrayFileDokumenUpdate[] = $namaFile;
                            $arrayFileDokumenSebelumnya[] = $dokumenSppTu->dokumen;

                            $dokumenSppTu->dokumen = $namaFile;
                        }
                        $dokumenSppTu->save();
                    }
                }

                if ($request->fileDokumen) {
                    foreach ($request->fileDokumen as $index => $nama) {
                        $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->$nama->storeAs('dokumen_spp_tu', $namaFileBerkas);
                        $arrayFileDokumen[] = $namaFileBerkas;

                        $dokumenSppTu = new DokumenSppTu();
                        $dokumenSppTu->nama_dokumen = $request[$request->namaFile[$index]];
                        $dokumenSppTu->dokumen = $namaFileBerkas;
                        $dokumenSppTu->spp_tu_id = $sppTu->id;
                        $dokumenSppTu->save();
                    }
                }


                $riwayatSppTu = new RiwayatSppTu();

                if ($request->file('surat_penolakan')) {
                    $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->file('surat_penolakan')->storeAs(
                        'surat_penolakan_spp_tu',
                        $namaFileBerkas
                    );
                    $riwayatSppTu->surat_penolakan = $namaFileBerkas;
                    $sppTu->surat_penolakan = $namaFileBerkas;
                }

                if ($sppTu->status_validasi_ppk == 2) {
                    $sppTu->status_validasi_ppk = 0;
                    $sppTu->alasan_validasi_ppk = null;
                }

                if ($sppTu->status_validasi_asn == 2) {
                    $sppTu->status_validasi_asn = 0;
                    $sppTu->alasan_validasi_asn = null;
                }
                $sppTu->tahap_riwayat = $sppTu->tahap_riwayat + 1;
                $sppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $sppTu->save();


                $riwayatSppTu->spp_tu_id = $sppTu->id;
                $riwayatSppTu->user_id = Auth::user()->id;
                $riwayatSppTu->status = 'Diperbaiki';
                $riwayatSppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $riwayatSppTu->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_tu/' . $nama)) {
                    Storage::delete('dokumen_spp_tu/' . $nama);
                }
            }

            foreach ($arrayFileDokumenUpdate as $nama) {
                if (Storage::exists('dokumen_spp_tu/' . $nama)) {
                    Storage::delete('dokumen_spp_tu/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        foreach ($arrayFileDokumenSebelumnya as $nama) {
            if (Storage::exists('dokumen_spp_tu/' . $nama)) {
                Storage::delete('dokumen_spp_tu/' . $nama);
            }
        }

        foreach ($arrayFileDokumenHapus as $nama) {
            if (Storage::exists('dokumen_spp_tu/' . $nama)) {
                Storage::delete('dokumen_spp_tu/' . $nama);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(SppTu $sppTu)
    {
        $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $sppTu->id)->whereNotNull('surat_penolakan')->get();

        $arraySuratPenolakan = null;

        $arrayDokumen = $sppTu->dokumenSppTu->pluck('dokumen');
        if ($riwayatSppTu) {
            $arraySuratPenolakan = $riwayatSppTu->pluck('surat_penolakan');
        }

        try {
            DB::transaction(
                function () use ($sppTu) {
                    $sppTu->delete();
                    $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $sppTu->id)->delete();
                    $dokumenSppTu = DokumenSppTu::where('spp_tu_id', $sppTu->id)->delete();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        if (count($arraySuratPenolakan) > 0) {
            foreach ($arraySuratPenolakan as $suratPenolakan) {
                Storage::delete('surat_penolakan_spp_tu/' . $suratPenolakan);
            }
        }

        if (count($arrayDokumen) > 0) {
            foreach ($arrayDokumen as $dokumen) {
                Storage::delete('dokumen_spp_tu/' . $dokumen);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppTu $sppTu)
    {
        $tipeSuratPenolakan = 'spp-tu';
        $tipeSuratPengembalian = 'spp_tu';
        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppTu.riwayat', compact(['sppTu', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function verifikasi(Request $request, SppTu $sppTu)
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
                function () use ($sppTu, $request) {
                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppTu->status_validasi_asn = $request->verifikasi;
                        $sppTu->alasan_validasi_asn = $request->alasan;
                        $sppTu->tanggal_validasi_asn = Carbon::now();
                    } else {
                        $sppTu->status_validasi_ppk = $request->verifikasi;
                        $sppTu->alasan_validasi_ppk = $request->alasan;
                        $sppTu->tanggal_validasi_ppk = Carbon::now();
                    }
                    $sppTu->save();

                    $riwayatTerakhir = RiwayatSppTu::whereNotNull('nomor_surat')->where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $sppTu->tahap_riwayat)->first();

                    $riwayatSppTu = new RiwayatSppTu();
                    $riwayatSppTu->spp_tu_id = $sppTu->id;
                    $riwayatSppTu->user_id = Auth::user()->id;
                    $riwayatSppTu->tahap_riwayat = $sppTu->tahap_riwayat;
                    $riwayatSppTu->jumlah_anggaran = str_replace(".", "", $sppTu->jumlah_anggaran);
                    $riwayatSppTu->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
                    if ($request->verifikasi == 2) {
                        $nomorSurat = DB::table('riwayat_spp_tu')
                            ->select(['spp_tu_id', 'tahap_riwayat'], DB::raw('count(*) as total'))
                            ->groupBy(['spp_tu_id', 'tahap_riwayat'])
                            ->whereNotNull('nomor_surat')
                            ->get()
                            ->count();
                        $riwayatSppTu->nomor_surat = $riwayatTerakhir ? $riwayatTerakhir->nomor_surat : ($nomorSurat + 1) . "/SPP-TU/P/" . Carbon::now()->format('m') . "/" . Carbon::now()->format('Y');
                    }
                    $riwayatSppTu->role = Auth::user()->role;
                    $riwayatSppTu->alasan = $request->alasan;
                    $riwayatSppTu->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppTu $sppTu)
    {
        try {
            DB::transaction(
                function () use ($sppTu) {
                    $sppTu->status_validasi_akhir = 1;
                    $sppTu->tanggal_validasi_akhir = Carbon::now();
                    $sppTu->save();

                    $riwayatSppTu = new RiwayatSppTu();
                    $riwayatSppTu->spp_tu_id = $sppTu->id;
                    $riwayatSppTu->jumlah_anggaran = $sppTu->jumlah_anggaran;
                    $riwayatSppTu->user_id = Auth::user()->id;
                    $riwayatSppTu->status = 'Diselesaikan';
                    $riwayatSppTu->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
