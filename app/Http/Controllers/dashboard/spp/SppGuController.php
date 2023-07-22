<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DaftarDokumenSppGu;
use App\Models\DokumenSppGu;
use App\Models\RiwayatSppGu;
use App\Models\Spd;
use App\Models\SppGu;
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

class SppGuController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.spp.sppGu.index');
    }

    public function create()
    {
        if (Auth::user()->role != "Admin") {
            $totalSppLs = SppGu::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->where('status_validasi_akhir', 1)->where('tahap', 'Selesai')->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppLs > 0) {
                return redirect(url('spp-gu'))->with('error', 'Selesaikan Terlebih Dahulu Arsip SP2D');
            }
        }

        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarDokumenSppGu = DaftarDokumenSppGu::where('kategori', 'SPJ')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppGu.create', compact(['daftarTahun', 'daftarDokumenSppGu', 'daftarSekretariatDaerah']));
    }

    public function createTahapSpp(SppGu $sppGu)
    {
        $role = Auth::user()->role;

        $totalSppLs = SppGu::where('id', $sppGu->id)->where('status_validasi_ppk', 0)->where('status_validasi_asn', 0)->where('tahap', 'SPJ')->count();
        if (!(($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) && $totalSppLs == 0)) {
            return redirect(url('spp-gu'));
        }

        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarDokumenSppGu = DaftarDokumenSppGu::where('kategori', 'SPP')->get();

        $perencanaanAnggaranHitung = $sppGu->perencanaan_anggaran;
        $perencanaanAnggaran = 'Rp. ' . number_format($sppGu->perencanaan_anggaran, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.');

        $role = Auth::user()->role;

        return view('dashboard.pages.spp.sppGu.createTahapSpp', compact(['daftarTahun', 'daftarDokumenSppGu', 'sppGu', 'perencanaanAnggaran', 'perencanaanAnggaranHitung', 'anggaranDigunakan']));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != "Admin") {
            $totalSppLs = SppGu::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppLs > 0) {
                return throw new Exception('Terjadi Kesalahan');
            }
        }

        $role = Auth::user()->role;

        $rules = [
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'tahun' => 'required',
            'program' => 'required',
            'kegiatan' => 'required',
            'bulan' => 'required',
            'perencanaan_anggaran' => 'required',
        ];

        $messages = [
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
            'program.required' => 'Program Tidak Boleh Kosong',
            'kegiatan.required' => 'Kegiatan Tidak Boleh Kosong',
            'bulan.required' => 'Bulan Tidak Boleh Kosong',
            'perencanaan_anggaran.required' => 'Perencanaan Tidak Boleh Kosong',
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
                $sppGu = new SppGu();
                $sppGu->user_id = Auth::user()->id;
                $sppGu->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppGu->tahun_id = $request->tahun;
                $sppGu->kegiatan_dpa_id = $request->kegiatan;
                $sppGu->bulan = $request->bulan;
                $sppGu->perencanaan_anggaran = str_replace(".", "", $request->perencanaan_anggaran);
                $sppGu->nomor_surat = $request->nomor_surat;
                $sppGu->save();

                foreach ($request->fileDokumen as $index => $nama) {
                    $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->$nama->storeAs('dokumen_spp_gu', $namaFileBerkas);
                    $arrayFileDokumen[] = $namaFileBerkas;

                    $dokumenSppGu = new DokumenSppGu();
                    $dokumenSppGu->nama_dokumen = $request[$request->namaFile[$index]];
                    $dokumenSppGu->dokumen = $namaFileBerkas;
                    $dokumenSppGu->spp_gu_id = $sppGu->id;
                    $dokumenSppGu->tahap = "SPJ";
                    $dokumenSppGu->save();
                }

                $riwayatSppGu = new RiwayatSppGu();
                $riwayatSppGu->spp_gu_id = $sppGu->id;
                $riwayatSppGu->user_id = Auth::user()->id;
                $riwayatSppGu->perencanaan_anggaran = str_replace(".", "", $request->perencanaan_anggaran);
                $riwayatSppGu->status = 'Dibuat';
                $riwayatSppGu->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_gu/' . $nama)) {
                    Storage::delete('dokumen_spp_gu/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function storeTahapSpp(SppGu $sppGu, Request $request)
    {
        $role = Auth::user()->role;

        $totalSppLs = SppGu::where('id', $sppGu->id)->where('status_validasi_ppk', 0)->where('status_validasi_asn', 0)->where('tahap', 'SPJ')->count();
        if (!(($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) && $totalSppLs == 0)) {
            return throw new Exception('Terjadi Kesalahan');
        }

        $rules = [
            'anggaran_digunakan' => 'required',
        ];

        $messages = [
            'anggaran_digunakan.required' => 'Anggaran yang digunakan Tidak Boleh Kosong',
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

        if ($request->anggaran_digunakan) {
            if ($request->anggaran_digunakan > $sppGu->perencanaan_anggaran) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('anggaran_digunakan', 'Anggaran melebihi anggaran yang telah ditentukan');
                });
            }
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, $sppGu) {
                $sppGu->tahap = "SPP";
                $sppGu->tahap_riwayat = $sppGu->tahap_riwayat + 1;
                $sppGu->surat_penolakan = null;
                $sppGu->status_validasi_asn = 0;
                $sppGu->status_validasi_ppk = 0;
                $sppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                $sppGu->save();

                foreach ($request->fileDokumen as $index => $nama) {
                    $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                    $request->$nama->storeAs('dokumen_spp_gu', $namaFileBerkas);
                    $arrayFileDokumen[] = $namaFileBerkas;

                    $dokumenSppGu = new DokumenSppGu();
                    $dokumenSppGu->nama_dokumen = $request[$request->namaFile[$index]];
                    $dokumenSppGu->dokumen = $namaFileBerkas;
                    $dokumenSppGu->spp_gu_id = $sppGu->id;
                    $dokumenSppGu->tahap = "SPP";
                    $dokumenSppGu->save();
                }

                $riwayatSppGu = new RiwayatSppGu();
                $riwayatSppGu->spp_gu_id = $sppGu->id;
                $riwayatSppGu->user_id = Auth::user()->id;
                $riwayatSppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                $riwayatSppGu->status = 'Upload Tahap SPP';
                $riwayatSppGu->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_gu/' . $nama)) {
                    Storage::delete('dokumen_spp_gu/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(SppGu $sppGu)
    {
        $role = Auth::user()->role;
        if ($sppGu->sekretariat_daerah_id != Auth::user()->profil->sekretariat_daerah_id  && in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        $tipe = 'spp_gu';
        $perencanaanAnggaran = 'Rp. ' . number_format($sppGu->perencanaan_anggaran, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.');

        if ($sppGu->tahap == "SPJ") {
            $tahap = '<span class="badge badge-primary">SPJ</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'SPJ')->get();
        } else if ($sppGu->tahap == "SPP" && $sppGu->status_validasi_akhir == 0) {
            $tahap = '<span class="badge badge-primary">SPP</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'SPP')->get();
        } else {
            $tahap = '<span class="badge badge-success">Selesai</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->get();
        }

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppGu.show', compact(['sppGu', 'tipe', 'perencanaanAnggaran', 'anggaranDigunakan', 'tahap', 'daftarDokumenSppGu']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function edit(Request $request, SppGu $sppGu)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) && ($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        $perencanaanAnggaranHitung = $sppGu->perencanaan_anggaran;
        $perencanaanAnggaran = 'Rp. ' . number_format($perencanaanAnggaranHitung, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.');

        if ($sppGu->tahap == "SPJ") {
            $tahap = '<span class="badge badge-primary">SPJ</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'SPJ')->get();
        } else if ($sppGu->tahap == "SPP") {
            $tahap = '<span class="badge badge-success">SPP</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'SPP')->get();
        } else {
            $tahap = '';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->get();
        }

        $role = Auth::user()->role;

        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) && ($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        return view('dashboard.pages.spp.sppGu.edit', compact(['sppGu', 'request', 'perencanaanAnggaran', 'anggaranDigunakan', 'perencanaanAnggaranHitung', 'daftarDokumenSppGu', 'tahap']));
    }

    public function update(Request $request, SppGu $sppGu)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) && (($sppGu->status_validasi_asn == 0 && $sppGu->status_validasi_ppk == 0) || ($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2))) {
            return throw new Exception('Terjadi Kesalahan');
        }

        if (!($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2)) {
            $suratPenolakan = 'nullable';
        } else {
            $suratPenolakan = 'required';
        }

        $rules = [
            'surat_penolakan' => $suratPenolakan . '|mimes:pdf|max:5120',
            'anggaran_digunakan' => $sppGu->tahap == "SPP" ? 'required' : 'nullable',
            'perencanaan_anggaran' => $sppGu->tahap == "SPJ" ? 'required' : 'nullable',
        ];

        $messages = [
            'surat_penolakan.required' => 'Surat Penolakan tidak boleh kosong',
            'surat_penolakan.mimes' => 'Dokumen Harus Berupa File PDF',
            'surat_penolakan.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
            'anggaran_digunakan.required' => 'Anggaran yang digunakan tidak boleh kosong',
            'perencanaan_anggaran.required' => 'Perencanaan anggaran tidak boleh kosong',
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

        if ($sppGu->tahap == "SPP") {
            if ($request->anggaran_digunakan) {
                if ($request->anggaran_digunakan > $sppGu->perencanaan_anggaran) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('anggaran_digunakan', 'Anggaran melebihi anggaran yang telah ditentukan');
                    });
                }
            }
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];
        $arrayFileDokumenSebelumnya = [];
        $arrayFileDokumenUpdate = [];
        $arrayFileDokumenHapus = [];

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, $sppGu) {
                if ($request->fileDokumenUpdate) {
                    $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', $sppGu->tahap)->whereNotIn('id', $request->fileDokumenUpdate)->get();
                    foreach ($daftarDokumenSppGu as $dokumen) {
                        $arrayFileDokumenHapus[] = $dokumen->dokumen;
                        $dokumen->delete();
                    }

                    foreach ($request->fileDokumenUpdate as $index => $id) {
                        $dokumenSppGu = DokumenSppGu::where('id', $id)->first();
                        $dokumenSppGu->nama_dokumen = $request[$request->namaFileUpdate[$index]];

                        if ($request["$id"]) {
                            $namaFile = Str::slug($request->namaFileUpdate[$index], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                            $request->$id->storeAs('dokumen_spp_gu/', $namaFile);
                            $arrayFileDokumenUpdate[] = $namaFile;
                            $arrayFileDokumenSebelumnya[] = $dokumenSppGu->dokumen;
                            $dokumenSppGu->dokumen = $namaFile;
                        }
                        $dokumenSppGu->save();
                    }
                }

                if ($request->fileDokumen) {
                    foreach ($request->fileDokumen as $index => $nama) {
                        $namaFileBerkas = Str::slug($request[$request->namaFile[$index]], '-') . "-" . ($index + 1) . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->$nama->storeAs('dokumen_spp_gu', $namaFileBerkas);
                        $arrayFileDokumen[] = $namaFileBerkas;

                        $dokumenSppGu = new DokumenSppGu();
                        $dokumenSppGu->nama_dokumen = $request[$request->namaFile[$index]];
                        $dokumenSppGu->dokumen = $namaFileBerkas;
                        $dokumenSppGu->tahap = $sppGu->tahap;
                        $dokumenSppGu->spp_gu_id = $sppGu->id;
                        $dokumenSppGu->save();
                    }
                }

                if (($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2)) {
                    $riwayatSppGu = new RiwayatSppGu();

                    if ($request->file('surat_penolakan')) {
                        $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->file('surat_penolakan')->storeAs(
                            'surat_penolakan_spp_gu',
                            $namaFileBerkas
                        );
                        $riwayatSppGu->surat_penolakan = $namaFileBerkas;
                        $sppGu->surat_penolakan = $namaFileBerkas;
                    }

                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = Auth::user()->id;
                    $riwayatSppGu->status = 'Diperbaiki';
                    if ($sppGu->tahap == "SPJ") {
                        $riwayatSppGu->perencanaan_anggaran = str_replace(".", "", $request->perencanaan_anggaran);
                    } else {
                        $riwayatSppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                    }
                    $riwayatSppGu->save();
                }

                if (($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2)) {
                    $sppGu->tahap_riwayat = $sppGu->tahap_riwayat + 1;
                }

                if ($sppGu->status_validasi_ppk == 2) {
                    $sppGu->status_validasi_ppk = 0;
                    $sppGu->alasan_validasi_ppk = null;
                }

                if ($sppGu->status_validasi_asn == 2) {
                    $sppGu->status_validasi_asn = 0;
                    $sppGu->alasan_validasi_asn = null;
                }

                if ($sppGu->tahap == "SPJ") {
                    $sppGu->perencanaan_anggaran = str_replace(".", "", $request->perencanaan_anggaran);
                } else {
                    $sppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                }
                $sppGu->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spp_gu/' . $nama)) {
                    Storage::delete('dokumen_spp_gu/' . $nama);
                }
            }

            foreach ($arrayFileDokumenUpdate as $nama) {
                if (Storage::exists('dokumen_spp_gu/' . $nama)) {
                    Storage::delete('dokumen_spp_gu/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        foreach ($arrayFileDokumenSebelumnya as $nama) {
            if (Storage::exists('dokumen_spp_gu/' . $nama)) {
                Storage::delete('dokumen_spp_gu/' . $nama);
            }
        }

        foreach ($arrayFileDokumenHapus as $nama) {
            if (Storage::exists('dokumen_spp_gu/' . $nama)) {
                Storage::delete('dokumen_spp_gu/' . $nama);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(SppGu $sppGu)
    {
        if (!(Auth::user()->role == "Admin" || ($sppGu->status_validasi_asn == 0 && $sppGu->status_validasi_ppk == 0 && $sppGu->tahap == "SPJ"))) {
            return throw new Exception('Gagal Diproses');
        }

        $riwayatSppGu = RiwayatSppGu::where('spp_gu_id', $sppGu->id)->whereNotNull('surat_penolakan')->get();

        $arraySuratPenolakan = null;

        $arrayDokumen = $sppGu->dokumenSppGu->pluck('dokumen');
        if ($riwayatSppGu) {
            $arraySuratPenolakan = $riwayatSppGu->pluck('surat_penolakan');
        }

        try {
            DB::transaction(
                function () use ($sppGu) {
                    $sppGu->delete();
                    $riwayatSppGu = RiwayatSppGu::where('spp_gu_id', $sppGu->id)->delete();
                    $dokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->delete();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        if (count($arraySuratPenolakan) > 0) {
            foreach ($arraySuratPenolakan as $suratPenolakan) {
                Storage::delete('surat_penolakan_spp_gu/' . $suratPenolakan);
            }
        }

        if (count($arrayDokumen) > 0) {
            foreach ($arrayDokumen as $dokumen) {
                Storage::delete('dokumen_spp_gu/' . $dokumen);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasi(Request $request, SppGu $sppGu)
    {
        if (!(in_array(Auth::user()->role, ['ASN Sub Bagian Keuangan', 'PPK']) && $sppGu->status_validasi_akhir == 0 && Auth::user()->is_aktif == 1)) {
            return throw new Exception('Gagal Diproses');
        }

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
                function () use ($sppGu, $request) {

                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppGu->status_validasi_asn = $request->verifikasi;
                        $sppGu->alasan_validasi_asn = $request->alasan;
                        $sppGu->tanggal_validasi_asn = Carbon::now();

                        $riwayatTerakhir = RiwayatSppGu::where('role', 'ASN Sub Bagian Keuangan')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    } else {
                        $sppGu->status_validasi_ppk = $request->verifikasi;
                        $sppGu->alasan_validasi_ppk = $request->alasan;
                        $sppGu->tanggal_validasi_ppk = Carbon::now();
                        $riwayatTerakhir = RiwayatSppGu::where('role', 'PPK')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    }
                    $sppGu->save();

                    $riwayatTerakhir = RiwayatSppGu::whereNotNull('nomor_surat')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->first();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = Auth::user()->id;
                    $riwayatSppGu->tahap_riwayat = $sppGu->tahap_riwayat;
                    $riwayatSppGu->perencanaan_anggaran = str_replace(".", "", $sppGu->perencanaan_anggaran);
                    $riwayatSppGu->anggaran_digunakan = str_replace(".", "", $sppGu->anggaran_digunakan);
                    $riwayatSppGu->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
                    if ($request->verifikasi == 2) {
                        $nomorSurat = DB::table('riwayat_spp_gu')
                            ->select(['spp_gu_id', 'tahap_riwayat'], DB::raw('count(*) as total'))
                            ->groupBy(['spp_gu_id', 'tahap_riwayat'])
                            ->whereNotNull('nomor_surat')
                            ->get()
                            ->count();
                        $riwayatSppGu->nomor_surat = $riwayatTerakhir ? $riwayatTerakhir->nomor_surat : ($nomorSurat + 1) . "/SPP-GU/P/" . Carbon::now()->format('m') . "/" . Carbon::now()->format('Y');
                    }
                    $riwayatSppGu->alasan = $request->alasan;
                    $riwayatSppGu->role = Auth::user()->role;
                    $riwayatSppGu->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppGu $sppGu)
    {
        if (!($sppGu->status_validasi_ppk == 1 && $sppGu->status_validasi_akhir == 0 && $sppGu->status_validasi_asn == 1 && $sppGu->tahap == "Selesai" && Auth::user()->is_aktif == 1)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        try {
            DB::transaction(
                function () use ($sppGu) {
                    $sppGu->tahap = 'Selesai';
                    $sppGu->status_validasi_akhir = 1;
                    $sppGu->tanggal_validasi_akhir = Carbon::now();
                    $sppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->anggaran_digunakan = $sppGu->anggaran_digunakan;
                    $riwayatSppGu->user_id = Auth::user()->id;
                    $riwayatSppGu->status = 'Diselesaikan';
                    $riwayatSppGu->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppGu $sppGu)
    {
        $tipeSuratPenolakan = 'spp-gu';
        $tipeSuratPengembalian = 'spp_gu';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppGu.riwayat', compact(['sppGu', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }


    public function storeSpm(Request $request, SppGu $sppGu)
    {
        if (!(($sppGu->status_validasi_ppk == 1 && $sppGu->status_validasi_asn == 1 && $sppGu->status_validasi_akhir == 1 && !$sppGu->dokumen_arsip_sp2d))) {
            return throw new Exception('Gagal Diproses');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'dokumen_spm' => 'required|mimes:pdf|max:5120',
            ],
            [
                'dokumen_spm.required' => "File tidak boleh kosong",
                'dokumen_spm.mimes' => "File harus berupa file pdf",
                'dokumen_spm.max' => "File tidak boleh lebih dari 5 MB"
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $namaDokumenSebelumnya = $sppGu->dokumen_spm ?? null;

        $namaDokumen = '';

        if ($request->dokumen_spm) {
            $namaDokumen = time() . '.' . $request->dokumen_spm->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppGu, $namaDokumen) {
                if ($request->dokumen_spm) {
                    $request->dokumen_spm->storeAs('dokumen_spm_spp_gu', $namaDokumen);
                }

                $sppGu->dokumen_spm = $namaDokumen;
                $sppGu->save();

                $riwayatSppGu = RiwayatSppGu::where('status', 'Upload SPM')->where('spp_gu_id', $sppGu->id)->delete();

                $riwayatSppGu = new RiwayatSppGu();
                $riwayatSppGu->spp_gu_id = $sppGu->id;
                $riwayatSppGu->user_id = Auth::user()->id;
                $riwayatSppGu->status = 'Upload SPM';
                $riwayatSppGu->role = Auth::user()->role;
                $riwayatSppGu->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_spm) {
                if (Storage::exists('dokumen_spm_spp_gu/' . $namaDokumen)) {
                    Storage::delete('dokumen_spm_spp_gu/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_spm_spp_gu/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_spm_spp_gu/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function storeSp2d(Request $request, SppGu $sppGu)
    {
        if (!((Auth::user()->role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) && ($sppGu->status_validasi_ppk == 1 && $sppGu->status_validasi_asn == 1 && $sppGu->status_validasi_akhir == 1 && $sppGu->dokumen_spm))) {
            return throw new Exception('Gagal Diproses');
        }
        $validator = Validator::make(
            $request->all(),
            [
                'dokumen_arsip_sp2d' => 'required|mimes:pdf|max:5120',
            ],
            [
                'dokumen_arsip_sp2d.required' => "File tidak boleh kosong",
                'dokumen_arsip_sp2d.mimes' => "File harus berupa file pdf",
                'dokumen_arsip_sp2d.max' => "File tidak boleh lebih dari 5 MB"
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $namaDokumenSebelumnya = $sppGu->dokumen_arsip_sp2d ?? null;

        $namaDokumen = '';

        if ($request->dokumen_arsip_sp2d) {
            $namaDokumen = time() . '.' . $request->dokumen_arsip_sp2d->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppGu, $namaDokumen) {
                if ($request->dokumen_arsip_sp2d) {
                    $request->dokumen_arsip_sp2d->storeAs('dokumen_arsip_sp2d_spp_gu', $namaDokumen);
                }

                $sppGu->dokumen_arsip_sp2d = $namaDokumen;
                $sppGu->save();

                $riwayatSppGu = RiwayatSppGu::where('status', 'Upload Arsip SP2D')->where('spp_gu_id', $sppGu->id)->delete();

                $riwayatSppGu = new RiwayatSppGu();
                $riwayatSppGu->spp_gu_id = $sppGu->id;
                $riwayatSppGu->user_id = Auth::user()->id;
                $riwayatSppGu->status = 'Upload Arsip SP2D';
                $riwayatSppGu->role = Auth::user()->role;
                $riwayatSppGu->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_arsip_sp2d) {
                if (Storage::exists('dokumen_arsip_sp2d_spp_gu/' . $namaDokumen)) {
                    Storage::delete('dokumen_arsip_sp2d_spp_gu/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_gu/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_arsip_sp2d_spp_gu/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function cekSp2d()
    {
        if (Auth::user()->role != "Admin") {
            $totalSppLs = SppGu::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->where('status_validasi_akhir', 1)->where('tahap', 'Selesai')->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppLs > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terdapat arsip SP2D yang belum diupload'
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
        ]);
    }
}
