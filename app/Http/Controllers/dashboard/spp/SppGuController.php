<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DaftarDokumenSppGu;
use App\Models\DokumenSppGu;
use App\Models\RiwayatSppGu;
use App\Models\Spd;
use App\Models\SpjGu;
use App\Models\SppGu;
use App\Models\Tahun;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $daftarDokumenSppGu = DaftarDokumenSppGu::get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.sppGu.create', compact(['daftarTahun', 'daftarDokumenSppGu', 'daftarSekretariatDaerah']));
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
            'nomor_surat' => 'required',
            'spj_gu' => 'required',
        ];

        $messages = [
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'nomor_surat.required' => 'Nomor Surat Pertanggungjawaban (SPJ) Tidak Boleh Kosong',
            'spj_gu.required' => 'Nomor Surat Permintaan Pembayaran (SPP) Tidak Boleh Kosong',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
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
                $sppGu->spj_gu_id = $request->spj_gu;
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

    public function show(SppGu $sppGu)
    {
        $role = Auth::user()->role;
        if ($sppGu->sekretariat_daerah_id != Auth::user()->profil->sekretariat_daerah_id  && in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        $tipe = 'spp_gu';
        $spjGu = SpjGu::where('id', $sppGu->spj_gu_id)->first();
        if ($spjGu) {
            $totalJumlahAnggaran = 0;
            $totalAnggaranDigunakan = 0;
            $totalSisaAnggaran = 0;
            $programDanKegiatan = [];
            $totalProgramDanKegiatan = [];

            foreach ($spjGu->kegiatanSpjGu as $kegiatanSpjGu) {
                $program = $kegiatanSpjGu->kegiatan->program->nama . ' (' . $kegiatanSpjGu->kegiatan->program->no_rek . ')';
                $kegiatan = $kegiatanSpjGu->kegiatan->nama . ' (' . $kegiatanSpjGu->kegiatan->no_rek . ')';
                $jumlahAnggaran = jumlah_anggaran($spjGu->sekretariat_daerah_id, $kegiatanSpjGu->kegiatan_id, $spjGu->bulan_id, $spjGu->tahun_id, $spjGu->id);
                $anggaranDigunakan = $kegiatanSpjGu->anggaran_digunakan;
                $sisaAnggaran = $jumlahAnggaran - $anggaranDigunakan;

                $programDanKegiatan[] = [
                    'program' => $program,
                    'kegiatan' => $kegiatan,
                    'dokumen' => $kegiatanSpjGu->dokumen,
                    'jumlah_anggaran' => $jumlahAnggaran,
                    'anggaran_digunakan' => $anggaranDigunakan,
                    'sisa_anggaran' => $sisaAnggaran
                ];

                $totalJumlahAnggaran += $jumlahAnggaran;
                $totalAnggaranDigunakan += $anggaranDigunakan;
                $totalSisaAnggaran += $sisaAnggaran;
            }

            $totalProgramDanKegiatan = [
                'total_jumlah_anggaran' => $totalJumlahAnggaran,
                'total_anggaran_digunakan' => $totalAnggaranDigunakan,
                'total_sisa_anggaran' => $totalSisaAnggaran,
            ];
        }

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppGu->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppGu.show', compact(['sppGu', 'spjGu', 'totalProgramDanKegiatan', 'tipe', 'programDanKegiatan']));
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

        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.sppGu.edit', compact(['sppGu', 'daftarTahun', 'daftarSekretariatDaerah']));
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
            'surat_pengembalian' => $suratPenolakan . '|mimes:pdf',
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'tahun' => 'required',
            'nomor_surat' => 'required',
            'spj_gu' => 'required',
        ];

        $messages = [
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'surat_pengembalian.required' => 'Surat Pengembalian tidak boleh kosong',
            'surat_pengembalian.mimes' => 'Dokumen Harus Berupa File PDF',
            'nomor_surat.required' => 'Nomor Surat Pertanggungjawaban (SPJ) Tidak Boleh Kosong',
            'spj_gu.required' => 'Nomor Surat Permintaan Pembayaran (SPP) Tidak Boleh Kosong',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
        ];

        if ($request->fileDokumenUpdate) {
            foreach ($request->fileDokumenUpdate as $dokumen) {
                $dokumen = "'" . $dokumen . "'";
                $rules["$dokumen"] = $request["$dokumen"] ? 'required|mimes:pdf' : 'nullable';
                $messages["$dokumen.required"] = "File tidak boleh kosong";
                $messages["$dokumen.mimes"] = "File harus berupa file pdf";
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
                $rules["$dokumen"] = 'required|mimes:pdf';
                $messages["$dokumen.required"] = "File tidak boleh kosong";
                $messages["$dokumen.mimes"] = "File harus berupa file pdf";
            }
        }

        if ($request->program) {
            foreach ($request->program as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Program tidak boleh kosong";
            }
        }

        if ($request->kegiatan) {
            foreach ($request->kegiatan as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Kegiatan tidak boleh kosong";
            }
        }

        if ($request->anggaranDigunakan) {
            foreach ($request->anggaranDigunakan as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Anggaran digunakan tidak boleh kosong";
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

        $namaFileSuratPengembalian = '';

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, &$namaFileSuratPengembalian, $sppGu, $role) {
                if ($request->fileDokumenUpdate) {
                    $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->whereNotIn('id', $request->fileDokumenUpdate)->get();
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
                        $dokumenSppGu->spp_gu_id = $sppGu->id;
                        $dokumenSppGu->save();
                    }
                }

                if (($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2)) {
                    $riwayatSppGu = new RiwayatSppGu();

                    if ($request->file('surat_pengembalian')) {
                        $namaFileSuratPengembalian = "surat-pengembalian" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->file('surat_pengembalian')->storeAs(
                            'surat_pengembalian_spp_gu',
                            $namaFileSuratPengembalian
                        );
                        $riwayatSppGu->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppGu->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppGu->surat_penolakan = null;
                    }

                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = Auth::user()->id;
                    $riwayatSppGu->status = 'Diperbaiki';
                    $riwayatSppGu->save();
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

                $sppGu->spj_gu_id = $request->spj_gu;
                $sppGu->nomor_surat = $request->nomor_surat;
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

            if (Storage::exists('surat_pengembalian_spp_gu/' . $namaFileSuratPengembalian)) {
                Storage::delete('surat_pengembalian_spp_gu/' . $namaFileSuratPengembalian);
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
        if (!(Auth::user()->role == "Admin" || ($sppGu->status_validasi_asn == 0 && $sppGu->status_validasi_ppk == 0))) {
            return throw new Exception('Gagal Diproses');
        }

        $riwayatSppGu = RiwayatSppGu::where('spp_gu_id', $sppGu->id)->get();

        $arraySuratPenolakan = null;
        $arraySuratPengembalian = null;

        $arrayDokumen = $sppGu->dokumenSppGu->pluck('dokumen');
        if ($riwayatSppGu) {
            $arraySuratPenolakan = $riwayatSppGu->pluck('surat_penolakan');
            $arraySuratPengembalian = $riwayatSppGu->pluck('surat_pengembalian');
        }

        $spm = $sppGu->dokumen_spm;
        $sp2d = $sppGu->dokumen_sp2d;

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
                if (Storage::exists('surat_penolakan_spp_gu/' . $suratPenolakan)) {
                    Storage::delete('surat_penolakan_spp_gu/' . $suratPenolakan);
                }
            }
        }
        if (count($arraySuratPengembalian) > 0) {
            foreach ($arraySuratPengembalian as $suratPengembalian) {
                if (Storage::exists('surat_pengembalian_spp_gu/' . $suratPengembalian)) {
                    Storage::delete('surat_pengembalian_spp_gu/' . $suratPengembalian);
                }
            }
        }

        if (count($arrayDokumen) > 0) {
            foreach ($arrayDokumen as $dokumen) {
                if (Storage::exists('dokumen_spp_gu/' . $dokumen)) {
                    Storage::delete('dokumen_spp_gu/' . $dokumen);
                }
            }
        }

        if ($spm) {
            if (Storage::exists('dokumen_spm_spp_gu/' . $spm)) {
                Storage::delete('dokumen_spm_spp_gu/' . $spm);
            }
        }

        if ($sp2d) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_gu/' . $sp2d)) {
                Storage::delete('dokumen_arsip_sp2d_spp_gu/' . $sp2d);
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

        $namaFileSuratPenolakan = '';

        try {
            DB::transaction(
                function () use ($sppGu, $request, &$namaFileSuratPenolakan) {

                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppGu->status_validasi_asn = $request->verifikasi;
                        $sppGu->alasan_validasi_asn = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppGu->tanggal_validasi_asn = Carbon::now();
                        $riwayatTerakhir = RiwayatSppGu::where('role', 'ASN Sub Bagian Keuangan')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    } else {
                        $sppGu->status_validasi_ppk = $request->verifikasi;
                        $sppGu->alasan_validasi_ppk = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppGu->tanggal_validasi_ppk = Carbon::now();
                        $riwayatTerakhir = RiwayatSppGu::where('role', 'PPK')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    }
                    $sppGu->save();

                    $riwayatTerakhir = RiwayatSppGu::whereNotNull('nomor_surat')->where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->first();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = Auth::user()->id;
                    $riwayatSppGu->tahap_riwayat = $sppGu->tahap_riwayat;
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

                    if (($sppGu->status_validasi_asn == 2 || $sppGu->status_validasi_ppk == 2) && ($sppGu->status_validasi_asn != 0 && $sppGu->status_validasi_ppk != 0)) {
                        $totalJumlahAnggaran = 0;
                        $totalAnggaranDigunakan = 0;
                        $totalSisaAnggaran = 0;
                        $programDanKegiatan = [];
                        $totalProgramDanKegiatan = [];

                        foreach ($sppGu->spjGu->kegiatanSpjGu as $kegiatanSpjGu) {
                            $program = $kegiatanSpjGu->kegiatan->program->nama . ' (' . $kegiatanSpjGu->kegiatan->program->no_rek . ')';
                            $kegiatan = $kegiatanSpjGu->kegiatan->nama . ' (' . $kegiatanSpjGu->kegiatan->no_rek . ')';
                            $jumlahAnggaran = jumlah_anggaran($sppGu->spjGu->sekretariat_daerah_id, $kegiatanSpjGu->kegiatan_id, $sppGu->spjGu->bulan_id, $sppGu->spjGu->tahun_id, $sppGu->spjGu->id);
                            $anggaranDigunakan = $kegiatanSpjGu->anggaran_digunakan;
                            $sisaAnggaran = $jumlahAnggaran - $anggaranDigunakan;

                            $programDanKegiatan[] = [
                                'program' => $program,
                                'kegiatan' => $kegiatan,
                                'jumlah_anggaran' => $jumlahAnggaran,
                                'anggaran_digunakan' => $anggaranDigunakan,
                                'sisa_anggaran' => $sisaAnggaran
                            ];

                            $totalJumlahAnggaran += $jumlahAnggaran;
                            $totalAnggaranDigunakan += $anggaranDigunakan;
                            $totalSisaAnggaran += $sisaAnggaran;
                        }

                        $totalProgramDanKegiatan = [
                            'total_jumlah_anggaran' => $totalJumlahAnggaran,
                            'total_anggaran_digunakan' => $totalAnggaranDigunakan,
                            'total_sisa_anggaran' => $totalSisaAnggaran,
                        ];

                        $hariIni = Carbon::now()->translatedFormat('d F Y');
                        $riwayatSppGu = RiwayatSppGu::where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->where('status', 'Ditolak')->orderBy('updated_at', 'desc')->first();
                        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
                        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();

                        $pdf = Pdf::loadView('dashboard.pages.spp.sppGu.suratPenolakan', compact(['sppGu', 'riwayatSppGu', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran', 'programDanKegiatan', 'totalProgramDanKegiatan']))->setPaper('f4', 'portrait');
                        $namaFileSuratPenolakan = 'surat-penolakan-' . time() . '.pdf';
                        Storage::put('surat_penolakan_spp_gu/' . $namaFileSuratPenolakan, $pdf->output());

                        $riwayatSppGu = RiwayatSppGu::where('spp_gu_id', $sppGu->id)->where('tahap_riwayat', $sppGu->tahap_riwayat)->where('status', 'Ditolak')->get();
                        foreach ($riwayatSppGu as $riwayat) {
                            if (Storage::exists('surat_penolakan_spp_gu/' . $riwayat->surat_penolakan)) {
                                Storage::delete('surat_penolakan_spp_gu/' . $riwayat->surat_penolakan);
                            }

                            $riwayat->surat_penolakan = $namaFileSuratPenolakan;
                            $riwayat->save();
                        }

                        $sppGu = SppGu::where('id', $sppGu->id)->first();
                        $sppGu->surat_penolakan = $namaFileSuratPenolakan;
                        $sppGu->save();
                    }
                }
            );
        } catch (QueryException $error) {
            if (Storage::exists('surat_penolakan_spp_gu/' . $namaFileSuratPenolakan)) {
                Storage::delete('surat_penolakan_spp_gu/' . $namaFileSuratPenolakan);
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppGu $sppGu)
    {
        if (!($sppGu->status_validasi_ppk == 1 && $sppGu->status_validasi_akhir == 0 && $sppGu->status_validasi_asn == 1 && Auth::user()->is_aktif == 1)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        try {
            DB::transaction(
                function () use ($sppGu) {
                    $sppGu->status_validasi_akhir = 1;
                    $sppGu->surat_penolakan = NULL;
                    $sppGu->surat_pengembalian = NULL;
                    $sppGu->tanggal_validasi_akhir = Carbon::now();
                    $sppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
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
}
