<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DokumenSppLs;
use App\Models\RiwayatSppLs;
use App\Models\Spd;
use App\Models\SppLs;
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

class SppLsController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $SekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah_id : Auth::user()->profil->sekretariat_daerah_id;
        if ($request->ajax()) {
            $data = SppLs::where(function ($query) use ($request, $SekretariatDaerah, $role) {
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
                    $nama = $row->kegiatanDpa->nama . " (" . $row->kegiatanDpa->no_rek . ")";
                    return $nama;
                })
                ->addColumn('program', function ($row) {
                    $nama = $row->kegiatanDpa->programDpa->nama . " (" . $row->kegiatanDpa->programDpa->no_rek . ")";
                    return $nama;
                })
                ->addColumn('periode', function ($row) {
                    $periode = $row->bulan . ", " . $row->tahun->tahun;
                    return $periode;
                })
                ->addColumn('riwayat', function ($row) {
                    $actionBtn = '<a href="' . url('spp-ls/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';
                    return $actionBtn;
                })
                ->addColumn('sekretariat_daerah', function ($row) {
                    $SekretariatDaerah = $row->SekretariatDaerah->nama;
                    return $SekretariatDaerah;
                })
                ->addColumn('anggaran_digunakan', function ($row) {
                    return 'Rp. ' . number_format($row->anggaran_digunakan, 0, ',', '.');
                })
                ->addColumn('anggaran_digunakan', function ($row) {
                    return 'Rp. ' . number_format($row->anggaran_digunakan, 0, ',', '.');
                })->addColumn('verifikasi_asn', function ($row) {
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
                            $actionBtn .= '<div class="d-flex justify-content-center mb-1"><a href="' . url('/surat-penolakan/spp-ls/' . $row->id . '/' . $row->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                            $actionBtn .= '<a href="' . url('spp-ls/' . $row->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                        }
                    }


                    if ($row->status_validasi_akhir == 1) {
                        $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-ls/' . $row->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                    }

                    if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $row->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';

                        if (($row->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $row->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id) || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0 && Auth::user()->is_aktif  == 1)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-ls/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0 && Auth::user()->is_aktif  == 1) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->status_validasi_asn == 1) && (Auth::user()->is_aktif  == 1)) {
                                $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $row->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                            }
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }


                    $actionBtn .= '<a href="' . url('spp-ls/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';

                    return $actionBtn;
                })
                ->addColumn('status_verifikasi_akhir', function ($row) {
                    if ($row->status_validasi_akhir == 0) {
                        return '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else {
                        return '<span class="badge badge-success">Diverifikasi</span>';
                    }
                })
                ->rawColumns(['action', 'sekretariat_daerah', 'tanggal_dibuat', 'riwayat', 'periode', 'verifikasi_asn', 'verifikasi_ppk', 'anggaran_digunakan', 'status_verifikasi_akhir'])
                ->make(true);
        }

        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();

        return view('dashboard.pages.spp.sppLs.index', compact('daftarSekretariatDaerah', 'daftarTahun'));
    }

    public function create()
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppLs.create', compact(['daftarTahun', 'daftarSekretariatDaerah']));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $rules = [
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'kategori' => 'required',
            'tahun' => 'required',
            'program' => 'required',
            'kegiatan' => 'required',
            'bulan' => 'required',
            'anggaran_digunakan' => 'required',
        ];

        $messages = [
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'kategori.required' => 'Kategori Tidak Boleh Kosong',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
            'program.required' => 'Program Tidak Boleh Kosong',
            'kegiatan.required' => 'Kegiatan Tidak Boleh Kosong',
            'bulan.required' => 'Bulan Tidak Boleh Kosong',
            'anggaran_digunakan.required' => 'Anggaran Digunakan Tidak Boleh Kosong',
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
                $sppLs = new SppLs();
                $sppLs->user_id = Auth::user()->id;
                $sppLs->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppLs->tahun_id = $request->tahun;
                $sppLs->kegiatan_dpa_id = $request->kegiatan;
                $sppLs->bulan = $request->bulan;
                $sppLs->kategori = $request->kategori;
                $sppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                $sppLs->nomor_surat = $request->nomor_surat;
                $sppLs->save();

                foreach ($request->fileDokumen as $index => $nama) {
                    $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->$nama->storeAs('dokumen_spp_ls', $namaFileBerkas);
                    $arrayFileDokumen[] = $namaFileBerkas;

                    $dokumenSppLs = new DokumenSppLs();
                    $dokumenSppLs->nama_dokumen = $request[$request->namaFile[$index]];
                    $dokumenSppLs->dokumen = $namaFileBerkas;
                    $dokumenSppLs->spp_ls_id = $sppLs->id;
                    $dokumenSppLs->save();
                }

                $riwayatSppLs = new RiwayatSppLs();
                $riwayatSppLs->spp_ls_id = $sppLs->id;
                $riwayatSppLs->user_id = Auth::user()->id;
                $riwayatSppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                $riwayatSppLs->status = 'Dibuat';
                $riwayatSppLs->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_ls/' . $nama)) {
                    Storage::delete('dokumen_spp_ls/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(SppLs $sppLs)
    {
        $tipe = 'spp_ls';

        $anggaranDigunakan = 'Rp. ' . number_format($sppLs->anggaran_digunakan, 0, ',', '.');

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppLs.show', compact(['sppLs', 'tipe', 'anggaranDigunakan']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function edit(SppLs $sppLs, Request $request)
    {
        $spd = Spd::where('kegiatan_dpa_id', $sppLs->kegiatan_dpa_id)->where('sekretariat_daerah_id', $sppLs->sekretariat_daerah_id)->where('tahun_id', $sppLs->tahun_id)->first();
        $jumlahAnggaranHitung = $spd->jumlah_anggaran;
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaranHitung, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppLs->anggaran_digunakan, 0, ',', '.');
        $role = Auth::user()->role;
        if (($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id) && ($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2)) {
            return view('dashboard.pages.spp.sppLs.edit', compact(['sppLs', 'request', 'jumlahAnggaran', 'anggaranDigunakan', 'jumlahAnggaranHitung']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function update(Request $request, SppLs $sppLs)
    {
        $rules = [
            'surat_penolakan' => 'required|mimes:pdf|max:5120',
            'anggaran_digunakan' => 'required'
        ];

        $messages = [
            'surat_penolakan.required' => 'Surat Penolakan tidak boleh kosong',
            'surat_penolakan.mimes' => 'Dokumen Harus Berupa File PDF',
            'surat_penolakan.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
            'anggaran_digunakan.required' => 'Anggaran Digunakan tidak boleh kosong'
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
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, $sppLs) {
                if ($request->fileDokumenUpdate) {
                    $daftarDokumenSppLs = DokumenSppLs::where('spp_ls_id', $sppLs->id)->whereNotIn('id', $request->fileDokumenUpdate)->get();
                    foreach ($daftarDokumenSppLs as $dokumen) {
                        $arrayFileDokumenHapus[] = $dokumen->dokumen;
                        $dokumen->delete();
                    }

                    foreach ($request->fileDokumenUpdate as $index => $id) {
                        $dokumenSppLs = DokumenSppLs::where('id', $id)->first();
                        $dokumenSppLs->nama_dokumen = $request[$request->namaFileUpdate[$index]];

                        if ($request["$id"]) {
                            $namaFile = Str::slug($request->namaFileUpdate[$index], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                            $request->$id->storeAs('dokumen_spp_ls/', $namaFile);
                            $arrayFileDokumenUpdate[] = $namaFile;
                            $arrayFileDokumenSebelumnya[] = $dokumenSppLs->dokumen;

                            $dokumenSppLs->dokumen = $namaFile;
                        }
                        $dokumenSppLs->save();
                    }
                }

                if ($request->fileDokumen) {
                    foreach ($request->fileDokumen as $index => $nama) {
                        $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->$nama->storeAs('dokumen_spp_ls', $namaFileBerkas);
                        $arrayFileDokumen[] = $namaFileBerkas;

                        $dokumenSppLs = new DokumenSppLs();
                        $dokumenSppLs->nama_dokumen = $request[$request->namaFile[$index]];
                        $dokumenSppLs->dokumen = $namaFileBerkas;
                        $dokumenSppLs->spp_ls_id = $sppLs->id;
                        $dokumenSppLs->save();
                    }
                }


                $riwayatSppLs = new RiwayatSppLs();

                if ($request->file('surat_penolakan')) {
                    $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->file('surat_penolakan')->storeAs(
                        'surat_penolakan_spp_ls',
                        $namaFileBerkas
                    );
                    $riwayatSppLs->surat_penolakan = $namaFileBerkas;
                    $sppLs->surat_penolakan = $namaFileBerkas;
                }

                if ($sppLs->status_validasi_ppk == 2) {
                    $sppLs->status_validasi_ppk = 0;
                    $sppLs->alasan_validasi_ppk = null;
                }

                if ($sppLs->status_validasi_asn == 2) {
                    $sppLs->status_validasi_asn = 0;
                    $sppLs->alasan_validasi_asn = null;
                }
                $sppLs->tahap_riwayat = $sppLs->tahap_riwayat + 1;
                $sppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                $sppLs->save();

                $riwayatSppLs->spp_ls_id = $sppLs->id;
                $riwayatSppLs->user_id = Auth::user()->id;
                $riwayatSppLs->status = 'Diperbaiki';
                $riwayatSppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                $riwayatSppLs->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_ls/' . $nama)) {
                    Storage::delete('dokumen_spp_ls/' . $nama);
                }
            }

            foreach ($arrayFileDokumenUpdate as $nama) {
                if (Storage::exists('dokumen_spp_ls/' . $nama)) {
                    Storage::delete('dokumen_spp_ls/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        foreach ($arrayFileDokumenSebelumnya as $nama) {
            if (Storage::exists('dokumen_spp_ls/' . $nama)) {
                Storage::delete('dokumen_spp_ls/' . $nama);
            }
        }

        foreach ($arrayFileDokumenHapus as $nama) {
            if (Storage::exists('dokumen_spp_ls/' . $nama)) {
                Storage::delete('dokumen_spp_ls/' . $nama);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(SppLs $sppLs)
    {
        $riwayatSppLs = RiwayatSppLs::where('spp_ls_id', $sppLs->id)->whereNotNull('surat_penolakan')->get();

        $arraySuratPenolakan = null;

        $arrayDokumen = $sppLs->dokumenSppLs->pluck('dokumen');
        if ($riwayatSppLs) {
            $arraySuratPenolakan = $riwayatSppLs->pluck('surat_penolakan');
        }

        try {
            DB::transaction(
                function () use ($sppLs) {
                    $sppLs->delete();
                    $riwayatSppLs = RiwayatSppLs::where('spp_ls_id', $sppLs->id)->delete();
                    $dokumenSppLs = DokumenSppLs::where('spp_ls_id', $sppLs->id)->delete();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        if (count($arraySuratPenolakan) > 0) {
            foreach ($arraySuratPenolakan as $suratPenolakan) {
                Storage::delete('surat_penolakan_spp_ls/' . $suratPenolakan);
            }
        }

        if (count($arrayDokumen) > 0) {
            foreach ($arrayDokumen as $dokumen) {
                Storage::delete('dokumen_spp_ls/' . $dokumen);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppLs $sppLs)
    {
        $tipeSuratPenolakan = 'spp-ls';
        $tipeSuratPengembalian = 'spp_ls';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppLs.riwayat', compact(['sppLs', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function verifikasi(Request $request, SppLs $sppLs)
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
                function () use ($sppLs, $request) {
                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppLs->status_validasi_asn = $request->verifikasi;
                        $sppLs->alasan_validasi_asn = $request->alasan;
                        $sppLs->tanggal_validasi_asn = Carbon::now();
                    } else {
                        $sppLs->status_validasi_ppk = $request->verifikasi;
                        $sppLs->alasan_validasi_ppk = $request->alasan;
                        $sppLs->tanggal_validasi_ppk = Carbon::now();
                    }
                    $sppLs->save();

                    $riwayatTerakhir = RiwayatSppLs::whereNotNull('nomor_surat')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->first();

                    $riwayatSppLs = new RiwayatSppLs();
                    $riwayatSppLs->spp_ls_id = $sppLs->id;
                    $riwayatSppLs->anggaran_digunakan = $sppLs->anggaran_digunakan;
                    $riwayatSppLs->tahap_riwayat = $sppLs->tahap_riwayat;
                    $riwayatSppLs->user_id = Auth::user()->id;
                    $riwayatSppLs->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
                    if ($request->verifikasi == 2) {
                        $nomorSurat = DB::table('riwayat_spp_ls')
                            ->select(['spp_ls_id', 'tahap_riwayat'], DB::raw('count(*) as total'))
                            ->groupBy(['spp_ls_id', 'tahap_riwayat'])
                            ->whereNotNull('nomor_surat')
                            ->get()
                            ->count();
                        $riwayatSppLs->nomor_surat = $riwayatTerakhir ? $riwayatTerakhir->nomor_surat : ($nomorSurat + 1) . "/SPP-LS/P/" . Carbon::now()->format('m') . "/" . Carbon::now()->format('Y');
                    }
                    $riwayatSppLs->role = Auth::user()->role;
                    $riwayatSppLs->alasan = $request->alasan;
                    $riwayatSppLs->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppLs $sppLs)
    {
        try {
            DB::transaction(
                function () use ($sppLs) {
                    $sppLs->status_validasi_akhir = 1;
                    $sppLs->tanggal_validasi_akhir = Carbon::now();
                    $sppLs->save();

                    $riwayatSppLs = new RiwayatSppLs();
                    $riwayatSppLs->spp_ls_id = $sppLs->id;
                    $riwayatSppLs->anggaran_digunakan = $sppLs->anggaran_digunakan;
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
