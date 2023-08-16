<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DokumenSppLs;
use App\Models\RiwayatSppLs;
use App\Models\Spd;
use App\Models\SppGu;
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
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();

        return view('dashboard.pages.spp.sppLs.index', compact('daftarSekretariatDaerah', 'daftarTahun'));
    }

    public function create()
    {
        if (Auth::user()->role != "Admin") {
            $totalSppLs = SppLs::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppLs > 0) {
                return redirect(url('spp-ls'))->with('error', 'Selesaikan Terlebih Dahulu Arsip SP2D');
            }
        }

        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppLs.create', compact(['daftarTahun', 'daftarSekretariatDaerah']));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != "Admin") {
            $totalSppLs = SppLs::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppLs > 0) {
                return throw new Exception('Terjadi Kesalahan');
            }
        }

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

        if ($request->anggaran_digunakan && $request->kegiatan && $request->bulan && $request->tahun) {
            $jumlahAnggaran = $this->_getJumlahAnggaran($request->sekretariat_daerah, $request->kegiatan, $request->bulan, $request->tahun);
            if ($request->anggaran_digunakan > $jumlahAnggaran) {
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
            DB::transaction(function () use ($request, &$arrayFileDokumen, $role) {
                $sppLs = new SppLs();
                $sppLs->user_id = Auth::user()->id;
                $sppLs->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppLs->tahun_id = $request->tahun;
                $sppLs->kegiatan_id = $request->kegiatan;
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
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id) && ($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        $spd = Spd::where('kegiatan_id', $sppLs->kegiatan_id)->where('sekretariat_daerah_id', $sppLs->sekretariat_daerah_id)->where('tahun_id', $sppLs->tahun_id)->first();
        $jumlahAnggaranHitung = $this->_getJumlahAnggaran($sppLs->sekretariat_daerah_id, $sppLs->kegiatan_id, $sppLs->bulan, $sppLs->tahun_id);
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaranHitung, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppLs->anggaran_digunakan, 0, ',', '.');


        return view('dashboard.pages.spp.sppLs.edit', compact(['sppLs', 'request', 'jumlahAnggaran', 'anggaranDigunakan', 'jumlahAnggaranHitung']));
    }

    public function update(Request $request, SppLs $sppLs)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id) && (($sppLs->status_validasi_asn == 0 && $sppLs->status_validasi_ppk == 0) || ($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2))) {
            return throw new Exception('Terjadi Kesalahan');
        }

        if (!($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2)) {
            $suratPenolakan = 'nullable';
        } else {
            $suratPenolakan = 'required';
        }

        $rules = [
            'surat_penolakan' => $suratPenolakan . '|mimes:pdf|max:5120',
            'anggaran_digunakan' => 'required',
        ];

        $messages = [
            'surat_penolakan.required' => 'Surat Penolakan tidak boleh kosong',
            'surat_penolakan.mimes' => 'Dokumen Harus Berupa File PDF',
            'surat_penolakan.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
            'anggaran_digunakan.required' => 'Anggaran yang digunakan tidak boleh kosong',
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

        if ($request->anggaran_digunakan) {
            $jumlahAnggaran = $this->_getJumlahAnggaran($sppLs->sekretariat_daerah_id, $sppLs->kegiatan_id, $sppLs->bulan, $sppLs->tahun_id);
            if ($request->anggaran_digunakan > $jumlahAnggaran) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('anggaran_digunakan', 'Anggaran melebihi anggaran yang telah ditentukan');
                });
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

                if (($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2)) {
                    $riwayatSppUp = new RiwayatSppLs();

                    if ($request->file('surat_penolakan')) {
                        $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->file('surat_penolakan')->storeAs(
                            'surat_penolakan_spp_ls',
                            $namaFileBerkas
                        );
                        $riwayatSppUp->surat_penolakan = $namaFileBerkas;
                        $sppLs->surat_penolakan = $namaFileBerkas;
                    }

                    $riwayatSppUp->spp_ls_id = $sppLs->id;
                    $riwayatSppUp->user_id = Auth::user()->id;
                    $riwayatSppUp->status = 'Diperbaiki';
                    $riwayatSppUp->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                    $riwayatSppUp->save();
                }

                if (($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2)) {
                    $sppLs->tahap_riwayat = $sppLs->tahap_riwayat + 1;
                }

                if ($sppLs->status_validasi_ppk == 2) {
                    $sppLs->status_validasi_ppk = 0;
                    $sppLs->alasan_validasi_ppk = null;
                }

                if ($sppLs->status_validasi_asn == 2) {
                    $sppLs->status_validasi_asn = 0;
                    $sppLs->alasan_validasi_asn = null;
                }

                $sppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
                $sppLs->save();
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
        if (!(Auth::user()->role == "Admin" || ($sppLs->status_validasi_asn == 0 && $sppLs->status_validasi_ppk == 0))) {
            return throw new Exception('Gagal Diproses');
        }

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
        if (!((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        return view('dashboard.pages.spp.sppLs.riwayat', compact(['sppLs', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
    }

    public function verifikasi(Request $request, SppLs $sppLs)
    {
        if (!(in_array(Auth::user()->role, ['ASN Sub Bagian Keuangan', 'PPK']) && $sppLs->status_validasi_akhir == 0 && Auth::user()->is_aktif == 1)) {
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
                function () use ($sppLs, $request) {

                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppLs->status_validasi_asn = $request->verifikasi;
                        $sppLs->alasan_validasi_asn = $request->alasan;
                        $sppLs->tanggal_validasi_asn = Carbon::now();

                        $riwayatTerakhir = RiwayatSppLs::where('role', 'ASN Sub Bagian Keuangan')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    } else {
                        $sppLs->status_validasi_ppk = $request->verifikasi;
                        $sppLs->alasan_validasi_ppk = $request->alasan;
                        $sppLs->tanggal_validasi_ppk = Carbon::now();
                        $riwayatTerakhir = RiwayatSppLs::where('role', 'PPK')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    }
                    $sppLs->save();

                    $riwayatTerakhir = RiwayatSppLs::whereNotNull('nomor_surat')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->first();

                    $riwayatSppLs = new RiwayatSppLs();
                    $riwayatSppLs->spp_ls_id = $sppLs->id;
                    $riwayatSppLs->user_id = Auth::user()->id;
                    $riwayatSppLs->tahap_riwayat = $sppLs->tahap_riwayat;
                    $riwayatSppLs->anggaran_digunakan = str_replace(".", "", $sppLs->anggaran_digunakan);
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
                    $riwayatSppLs->alasan = $request->alasan;
                    $riwayatSppLs->role = Auth::user()->role;
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
        if (!($sppLs->status_validasi_ppk == 1 && $sppLs->status_validasi_akhir == 0 && $sppLs->status_validasi_asn == 1 && Auth::user()->is_aktif == 1)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

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


    public function storeSpm(Request $request, SppLs $sppLs)
    {
        if (!(($sppLs->status_validasi_ppk == 1 && $sppLs->status_validasi_asn == 1 && $sppLs->status_validasi_akhir == 1 && !$sppLs->dokumen_arsip_sp2d))) {
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

        $namaDokumenSebelumnya = $sppLs->dokumen_spm ?? null;

        $namaDokumen = '';

        if ($request->dokumen_spm) {
            $namaDokumen = time() . '.' . $request->dokumen_spm->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppLs, $namaDokumen) {
                if ($request->dokumen_spm) {
                    $request->dokumen_spm->storeAs('dokumen_spm_spp_ls', $namaDokumen);
                }

                $sppLs->dokumen_spm = $namaDokumen;
                $sppLs->save();

                $riwayatSppLs = RiwayatSppLs::where('status', 'Upload SPM')->where('spp_ls_id', $sppLs->id)->delete();

                $riwayatSppLs = new RiwayatSppLs();
                $riwayatSppLs->spp_ls_id = $sppLs->id;
                $riwayatSppLs->user_id = Auth::user()->id;
                $riwayatSppLs->status = 'Upload SPM';
                $riwayatSppLs->role = Auth::user()->role;
                $riwayatSppLs->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_spm) {
                if (Storage::exists('dokumen_spm_spp_ls/' . $namaDokumen)) {
                    Storage::delete('dokumen_spm_spp_ls/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_spm_spp_ls/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_spm_spp_ls/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function storeSp2d(Request $request, SppLs $sppLs)
    {
        if (!((Auth::user()->role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id) && ($sppLs->status_validasi_ppk == 1 && $sppLs->status_validasi_asn == 1 && $sppLs->status_validasi_akhir == 1 && $sppLs->dokumen_spm))) {
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

        $namaDokumenSebelumnya = $sppLs->dokumen_arsip_sp2d ?? null;

        $namaDokumen = '';

        if ($request->dokumen_arsip_sp2d) {
            $namaDokumen = time() . '.' . $request->dokumen_arsip_sp2d->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppLs, $namaDokumen) {
                if ($request->dokumen_arsip_sp2d) {
                    $request->dokumen_arsip_sp2d->storeAs('dokumen_arsip_sp2d_spp_ls', $namaDokumen);
                }

                $sppLs->dokumen_arsip_sp2d = $namaDokumen;
                $sppLs->save();

                $riwayatSppLs = RiwayatSppLs::where('status', 'Upload Arsip SP2D')->where('spp_ls_id', $sppLs->id)->delete();

                $riwayatSppLs = new RiwayatSppLs();
                $riwayatSppLs->spp_ls_id = $sppLs->id;
                $riwayatSppLs->user_id = Auth::user()->id;
                $riwayatSppLs->status = 'Upload Arsip SP2D';
                $riwayatSppLs->role = Auth::user()->role;
                $riwayatSppLs->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_arsip_sp2d) {
                if (Storage::exists('dokumen_arsip_sp2d_spp_ls/' . $namaDokumen)) {
                    Storage::delete('dokumen_arsip_sp2d_spp_ls/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_ls/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_arsip_sp2d_spp_ls/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function cekSp2d()
    {
        if (Auth::user()->role != "Admin") {
            $totalSppLs = SppLs::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
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

    private function _getJumlahAnggaran($sekretariatDaerah, $kegiatan, $bulan, $tahun)
    {
        $role = Auth::user()->role;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $sekretariatDaerah : Auth::user()->profil->sekretariat_daerah_id;

        $spd = Spd::where('kegiatan_id', $kegiatan)->where('sekretariat_daerah_id', $sekretariatDaerah)->where('tahun_id', $tahun)->first();

        $jumlahAnggaran = $spd->jumlah_anggaran ?? 0;

        $sppLs = SppLs::where('sekretariat_daerah_id', $sekretariatDaerah)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun)
            ->where('kegiatan_id', $kegiatan)
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

        return $jumlahAnggaran;
    }
}
