<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DaftarDokumenSppUp;
use App\Models\DokumenSppUp;
use App\Models\Program;
use App\Models\RiwayatSppUp;
use App\Models\SppUp;
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

class SppUpController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.spp.sppUp.index');
    }

    public function create()
    {
        if (Auth::user()->role != "Admin") {
            $totalSppUp = SppUp::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppUp > 0) {
                return redirect(url('spp-up'))->with('error', 'Selesaikan Terlebih Dahulu Arsip SP2D');
            }
        }

        $daftarDokumenSppUp = DaftarDokumenSppUp::orderBy('created_at', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarProgram = Program::orderBy('nama', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppUp.create', compact(['daftarDokumenSppUp', 'daftarTahun', 'daftarProgram', 'daftarSekretariatDaerah']));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != "Admin") {
            $totalSppUp = SppUp::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppUp > 0) {
                return throw new Exception('Terjadi Kesalahan');
            }
        }

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
                $rules["$dokumen"] = 'required|mimes:pdf';
                $messages["$dokumen.required"] = "File tidak boleh kosong";
                $messages["$dokumen.mimes"] = "File harus berupa file pdf";
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
                $sppUp->kegiatan_id = $request->kegiatan;
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
        if (!((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        return view('dashboard.pages.spp.sppUp.show', compact(['sppUp', 'tipe', 'jumlahAnggaran']));
    }

    public function edit(SppUp $sppUp, Request $request)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) && (($sppUp->status_validasi_asn == 0 && $sppUp->status_validasi_ppk == 0) || ($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2))) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        return view('dashboard.pages.spp.sppUp.edit', compact(['sppUp', 'request']));
    }

    public function update(Request $request, SppUp $sppUp)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) && (($sppUp->status_validasi_asn == 0 && $sppUp->status_validasi_ppk == 0) || ($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2))) {
            return throw new Exception('Terjadi Kesalahan');
        }

        if (!($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2)) {
            $suratPengembalian = 'nullable';
        } else {
            $suratPengembalian = 'required';
        }

        $rules = [
            'surat_pengembalian' => $suratPengembalian . '|mimes:pdf',
            'jumlah_anggaran' => 'required',
        ];

        $messages = [
            'surat_pengembalian.required' => 'Surat Pengembalian tidak boleh kosong',
            'surat_pengembalian.mimes' => 'Dokumen Harus Berupa File PDF',
            'jumlah_anggaran.required' => 'Jumlah Anggaran tidak boleh kosong',
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
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, &$namaFileSuratPengembalian, $sppUp) {
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

                if (($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2)) {
                    $riwayatSppUp = new RiwayatSppUp();

                    if ($request->file('surat_pengembalian')) {
                        $namaFileSuratPengembalian = "surat-pengembalian" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->file('surat_pengembalian')->storeAs(
                            'surat_pengembalian_spp_up',
                            $namaFileSuratPengembalian
                        );
                        $riwayatSppUp->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppUp->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppUp->surat_penolakan = null;
                    }

                    $riwayatSppUp->spp_up_id = $sppUp->id;
                    $riwayatSppUp->user_id = Auth::user()->id;
                    $riwayatSppUp->status = 'Diperbaiki';
                    $riwayatSppUp->save();
                }

                if (($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2)) {
                    $sppUp->tahap_riwayat = $sppUp->tahap_riwayat + 1;
                }

                if ($sppUp->status_validasi_ppk == 2) {
                    $sppUp->status_validasi_ppk = 0;
                    $sppUp->alasan_validasi_ppk = null;
                }

                if ($sppUp->status_validasi_asn == 2) {
                    $sppUp->status_validasi_asn = 0;
                    $sppUp->alasan_validasi_asn = null;
                }

                $sppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
                $sppUp->save();
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

            if (Storage::exists('surat_pengembalian_spp_up/' . $namaFileSuratPengembalian)) {
                Storage::delete('surat_pengembalian_spp_up/' . $namaFileSuratPengembalian);
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
        if (!(Auth::user()->role == "Admin" || ($sppUp->status_validasi_asn == 0 && $sppUp->status_validasi_ppk == 0))) {
            return throw new Exception('Gagal Diproses');
        }

        $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $sppUp->id)->get();

        $arraySuratPenolakan = null;
        $arraySuratPengembalian = null;

        $arrayDokumen = $sppUp->dokumenSppUp->pluck('dokumen');
        if ($riwayatSppUp) {
            $arraySuratPenolakan = $riwayatSppUp->pluck('surat_penolakan');
            $arraySuratPengembalian = $riwayatSppUp->pluck('surat_pengembalian');
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

        if (count($arraySuratPengembalian) > 0) {
            foreach ($arraySuratPengembalian as $suratPengembalian) {
                Storage::delete('surat_pengembalian_spp_up/' . $suratPengembalian);
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

        if (!((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        return view('dashboard.pages.spp.sppUp.riwayat', compact(['sppUp', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
    }

    public function verifikasi(Request $request, SppUp $sppUp)
    {
        if (!(in_array(Auth::user()->role, ['ASN Sub Bagian Keuangan', 'PPK']) && $sppUp->status_validasi_akhir == 0 && Auth::user()->is_aktif == 1)) {
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
                function () use ($sppUp, $request, &$namaFileSuratPenolakan) {
                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppUp->status_validasi_asn = $request->verifikasi;
                        $sppUp->alasan_validasi_asn = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppUp->tanggal_validasi_asn = Carbon::now();

                        $riwayatTerakhir = RiwayatSppUp::where('role', 'ASN Sub Bagian Keuangan')->where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $sppUp->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    } else {
                        $sppUp->status_validasi_ppk = $request->verifikasi;
                        $sppUp->alasan_validasi_ppk = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppUp->tanggal_validasi_ppk = Carbon::now();
                        $riwayatTerakhir = RiwayatSppUp::where('role', 'PPK')->where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $sppUp->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    }
                    $sppUp->save();

                    $riwayatTerakhir = RiwayatSppUp::whereNotNull('nomor_surat')->where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $sppUp->tahap_riwayat)->first();

                    $riwayatSppUp = new RiwayatSppUp();
                    $riwayatSppUp->spp_up_id = $sppUp->id;
                    $riwayatSppUp->user_id = Auth::user()->id;
                    $riwayatSppUp->tahap_riwayat = $sppUp->tahap_riwayat;
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

                    if (($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2) && ($sppUp->status_validasi_asn != 0 && $sppUp->status_validasi_ppk != 0)) {
                        $tahapRiwayat = $sppUp->tahap_riwayat;
                        $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $sppUp->tahap_riwayat)->where('status', 'Ditolak')->orderBy('updated_at', 'desc')->first();
                        $hariIni = Carbon::now()->translatedFormat('d F Y');
                        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
                        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();
                        $pdf = Pdf::loadView('dashboard.pages.spp.sppUp.suratPenolakan', compact(['sppUp', 'riwayatSppUp', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran']))->setPaper('f4', 'portrait');

                        $namaFileSuratPenolakan = 'surat-penolakan-' . time() . '.pdf';
                        Storage::put('surat_penolakan_spp_up/' . $namaFileSuratPenolakan, $pdf->output());

                        $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $sppUp->tahap_riwayat)->where('status', 'Ditolak')->get();
                        foreach ($riwayatSppUp as $riwayat) {
                            if (Storage::exists('surat_penolakan_spp_up/' . $riwayat->surat_penolakan)) {
                                Storage::delete('surat_penolakan_spp_up/' . $riwayat->surat_penolakan);
                            }

                            $riwayat->surat_penolakan = $namaFileSuratPenolakan;
                            $riwayat->save();
                        }

                        $sppUp = SppUp::where('id', $sppUp->id)->first();
                        $sppUp->surat_penolakan = $namaFileSuratPenolakan;
                        $sppUp->save();
                    }
                }
            );
        } catch (QueryException $error) {
            if (Storage::exists('surat_penolakan_spp_up/' . $namaFileSuratPenolakan)) {
                Storage::delete('surat_penolakan_spp_up/' . $namaFileSuratPenolakan);
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppUp $sppUp)
    {
        if (!($sppUp->status_validasi_ppk == 1 && $sppUp->status_validasi_akhir == 0 && $sppUp->status_validasi_asn == 1 && Auth::user()->is_aktif == 1)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        try {
            DB::transaction(
                function () use ($sppUp) {
                    $sppUp->status_validasi_akhir = 1;
                    $sppUp->surat_penolakan = NULL;
                    $sppUp->surat_pengembalian = NULL;
                    $sppUp->tanggal_validasi_akhir = Carbon::now();
                    $sppUp->save();

                    $riwayatSppLs = new RiwayatSppUp();
                    $riwayatSppLs->spp_up_id = $sppUp->id;
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

    public function storeSpm(Request $request, SppUp $sppUp)
    {
        if (!(($sppUp->status_validasi_ppk == 1 && $sppUp->status_validasi_asn == 1 && $sppUp->status_validasi_akhir == 1 && !$sppUp->dokumen_arsip_sp2d))) {
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

        $namaDokumenSebelumnya = $sppUp->dokumen_spm ?? null;

        $namaDokumen = '';

        if ($request->dokumen_spm) {
            $namaDokumen = time() . '.' . $request->dokumen_spm->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppUp, $namaDokumen) {
                if ($request->dokumen_spm) {
                    $request->dokumen_spm->storeAs('dokumen_spm_spp_up', $namaDokumen);
                }

                $sppUp->dokumen_spm = $namaDokumen;
                $sppUp->save();

                $riwayatSppUp = RiwayatSppUp::where('status', 'Upload SPM')->where('spp_up_id', $sppUp->id)->delete();

                $riwayatSppUp = new RiwayatSppUp();
                $riwayatSppUp->spp_up_id = $sppUp->id;
                $riwayatSppUp->user_id = Auth::user()->id;
                $riwayatSppUp->status = 'Upload SPM';
                $riwayatSppUp->role = Auth::user()->role;
                $riwayatSppUp->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_spm) {
                if (Storage::exists('dokumen_spm_spp_up/' . $namaDokumen)) {
                    Storage::delete('dokumen_spm_spp_up/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_spm_spp_up/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_spm_spp_up/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function storeSp2d(Request $request, SppUp $sppUp)
    {
        if (!((Auth::user()->role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppUp->sekretariat_daerah_id) && ($sppUp->status_validasi_ppk == 1 && $sppUp->status_validasi_asn == 1 && $sppUp->status_validasi_akhir == 1 && $sppUp->dokumen_spm))) {
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

        $namaDokumenSebelumnya = $sppUp->dokumen_arsip_sp2d ?? null;

        $namaDokumen = '';

        if ($request->dokumen_arsip_sp2d) {
            $namaDokumen = time() . '.' . $request->dokumen_arsip_sp2d->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppUp, $namaDokumen) {
                if ($request->dokumen_arsip_sp2d) {
                    $request->dokumen_arsip_sp2d->storeAs('dokumen_arsip_sp2d_spp_up', $namaDokumen);
                }

                $sppUp->dokumen_arsip_sp2d = $namaDokumen;
                $sppUp->save();

                $riwayatSppUp = RiwayatSppUp::where('status', 'Upload Arsip SP2D')->where('spp_up_id', $sppUp->id)->delete();

                $riwayatSppUp = new RiwayatSppUp();
                $riwayatSppUp->spp_up_id = $sppUp->id;
                $riwayatSppUp->user_id = Auth::user()->id;
                $riwayatSppUp->status = 'Upload Arsip SP2D';
                $riwayatSppUp->role = Auth::user()->role;
                $riwayatSppUp->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_arsip_sp2d) {
                if (Storage::exists('dokumen_arsip_sp2d_spp_up/' . $namaDokumen)) {
                    Storage::delete('dokumen_arsip_sp2d_spp_up/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_up/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_arsip_sp2d_spp_up/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function cekSp2d()
    {
        if (Auth::user()->role != "Admin") {
            $totalSppUp = SppUp::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppUp > 0) {
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
