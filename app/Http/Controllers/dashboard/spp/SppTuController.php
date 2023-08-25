<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DaftarDokumenSppTu;
use App\Models\DokumenSppTu;
use App\Models\Kegiatan;
use App\Models\KegiatanSppTu;
use App\Models\Program;
use App\Models\RiwayatSppTu;
use App\Models\SppTu;
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
use Illuminate\Validation\Rule;

class SppTuController extends Controller
{
    public function index(Request $request)
    {
        $totalSpp = SppTu::where(function ($query) {
            if (!in_array(Auth::user()->role, ['Admin', 'Operator SPM'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }
        })->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->where('status_validasi_akhir', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();

        return view('dashboard.pages.spp.sppTu.index', compact('totalSpp'));
    }

    public function create()
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarDokumenSppTu = DaftarDokumenSppTu::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.sppTu.create', compact(['daftarTahun', 'daftarSekretariatDaerah', 'daftarDokumenSppTu']));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != "Admin") {
            $totalSpp = SppTu::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSpp > 0) {
                return throw new Exception('Terjadi Kesalahan');
            }
        }

        $role = Auth::user()->role;

        $rules = [
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'nomor_surat' => ['required', Rule::unique('spp_tu')->where(function ($query) use ($request) {
                return $query->where('nomor_surat', $request->nomor_surat);
            })],
            'tahun' => 'required',
            'bulan' => 'required',
        ];

        $messages = [
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'nomor_surat.required' => 'Nomor Surat Permintaan Pembayaran (SPP) Tidak Boleh Kosong',
            'nomor_surat.unique' => 'Nomor Surat Permintaan Pembayaran (SPP) Sudah Ada',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
            'bulan.required' => 'Bulan Tidak Boleh Kosong',
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

        if ($request->jumlahAnggaran) {
            foreach ($request->jumlahAnggaran as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Jumlah anggaran tidak boleh kosong";
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$request->fileDokumen) {
            $validator->after(function ($validator) {
                $validator->errors()->add('dokumenFileHitung', 'Dokumen Minimal 1');
            });
        }

        if (!$request->program) {
            $validator->after(function ($validator) {
                $validator->errors()->add('programDanKegiatanHitung', 'Program dan Kegiatan Minimal 1');
            });
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, $role) {
                $sppTu = new SppTu();
                $sppTu->user_id = Auth::user()->id;
                $sppTu->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppTu->tahun_id = $request->tahun;
                $sppTu->bulan = $request->bulan;
                $sppTu->nomor_surat = $request->nomor_surat;
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

                foreach ($request->jumlahAnggaran as $index => $nama) {
                    $jumlahAnggaran = str_replace('.', '', $request["$nama"]);
                    $kegiatanSppTu = new KegiatanSppTu();
                    $kegiatanSppTu->spp_tu_id = $sppTu->id;
                    $kegiatanSppTu->kegiatan_id = $request[$request->kegiatan[$index]];
                    $kegiatanSppTu->jumlah_anggaran = $jumlahAnggaran;
                    $kegiatanSppTu->save();
                }

                $riwayatSppTu = new RiwayatSppTu();
                $riwayatSppTu->spp_tu_id = $sppTu->id;
                $riwayatSppTu->user_id = Auth::user()->id;
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
        $tipe = 'spp_tu';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) {
            $totalJumlahAnggaran = 0;
            $programDanKegiatan = [];
            $totalProgramDanKegiatan = [];

            foreach ($sppTu->kegiatanSppTu as $kegiatanSppTu) {
                $program = $kegiatanSppTu->kegiatan->program->nama . ' (' . $kegiatanSppTu->kegiatan->program->no_rek . ')';
                $kegiatan = $kegiatanSppTu->kegiatan->nama . ' (' . $kegiatanSppTu->kegiatan->no_rek . ')';
                $jumlahAnggaran = $kegiatanSppTu->jumlah_anggaran;

                $programDanKegiatan[] = [
                    'program' => $program,
                    'kegiatan' => $kegiatan,
                    'jumlah_anggaran' => $jumlahAnggaran,
                ];

                $totalJumlahAnggaran += $jumlahAnggaran;
            }

            $totalProgramDanKegiatan = [
                'total_jumlah_anggaran' => $totalJumlahAnggaran,
            ];

            return view('dashboard.pages.spp.sppTu.show', compact(['sppTu', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function edit(SppTu $sppTu, Request $request)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) && ($sppTu->status_validasi_asn == 2 || $sppTu->status_validasi_ppk == 2)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarDokumenSppTu = DaftarDokumenSppTu::orderBy('nama', 'asc')->get();
        $daftarProgram = Program::orderBy('no_rek', 'asc')->whereHas('kegiatan')->orderBy('no_rek', 'asc')->get();

        $programDanKegiatan = null;
        $arrayJumlahAnggaran = [];
        foreach ($sppTu->kegiatanSppTu as $kegiatanSppTu) {
            $jumlahAnggaran = $kegiatanSppTu->jumlah_anggaran;

            $daftarKegiatan = Kegiatan::where('program_id', $kegiatanSppTu->kegiatan->program_id)->orderBy('no_rek', 'asc')->get();

            $dataKey = Str::random(5) . rand(111, 999) . Str::random(5);
            $programDanKegiatan .= view('dashboard.components.dynamicForm.sppTu', compact(['jumlahAnggaran', 'daftarProgram', 'daftarKegiatan', 'kegiatanSppTu', 'dataKey']))->render();

            $arrayJumlahAnggaran[] = [
                'key' => $dataKey,
                'jumlah_anggaran' => $jumlahAnggaran ?? 0
            ];
        }

        return view('dashboard.pages.spp.sppTu.edit', compact(['sppTu', 'daftarTahun', 'daftarSekretariatDaerah', 'daftarProgram', 'daftarDokumenSppTu',  'programDanKegiatan', 'arrayJumlahAnggaran']));
    }

    public function update(Request $request, SppTu $sppTu)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) && (($sppTu->status_validasi_asn == 0 && $sppTu->status_validasi_ppk == 0) || ($sppTu->status_validasi_asn == 2 || $sppTu->status_validasi_ppk == 2))) {
            return throw new Exception('Terjadi Kesalahan');
        }

        if (!($sppTu->status_validasi_asn == 2 || $sppTu->status_validasi_ppk == 2)) {
            $suratPenolakan = 'nullable';
        } else {
            $suratPenolakan = 'required';
        }

        $rules = [
            'surat_pengembalian' => $suratPenolakan . '|mimes:pdf',
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'nomor_surat' => ['required', Rule::unique('spp_tu')->where(function ($query) use ($request) {
                return $query->where('nomor_surat', $request->nomor_surat);
            })->ignore($sppTu->id)],
            'tahun' => 'required',
            'bulan' => 'required',
        ];

        $messages = [
            'surat_pengembalian.required' => 'Surat Pengembalian tidak boleh kosong',
            'surat_pengembalian.mimes' => 'Dokumen Harus Berupa File PDF',
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'nomor_surat.required' => 'Nomor Surat Permintaan Pembayaran (SPP) Tidak Boleh Kosong',
            'nomor_surat.unique' => 'Nomor Surat Permintaan Pembayaran (SPP) Sudah Ada',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
            'bulan.required' => 'Bulan Tidak Boleh Kosong',
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

        if ($request->jumlahAnggaran) {
            foreach ($request->jumlahAnggaran as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Jumlah anggaran tidak boleh kosong";
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

        if (!$request->program) {
            $validator->after(function ($validator) {
                $validator->errors()->add('programDanKegiatanHitung', 'Program dan Kegiatan Minimal 1');
            });
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];
        $arrayFileDokumenSebelumnya = [];
        $arrayFileDokumenUpdate = [];
        $arrayFileDokumenHapus = [];
        $arrayKegiatan = [];
        foreach ($request->kegiatan as $index => $nama) {
            $arrayKegiatan[] = $request["$nama"];
        }

        $namaFileSuratPengembalian = '';

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, &$namaFileSuratPengembalian, $arrayKegiatan, $sppTu, $role) {

                $kegiatanSppTu = KegiatanSppTu::whereNotIn('kegiatan_id', $arrayKegiatan)->where('spp_tu_id', $sppTu->id)->delete();

                foreach ($request->jumlahAnggaran as $index => $nama) {
                    $jumlahAnggaran = str_replace('.', '', $request["$nama"]);
                    $kegiatanSppTu = KegiatanSppTu::where('spp_tu_id', $sppTu->id)->where('kegiatan_id', $request[$request->kegiatan[$index]])->first();
                    if (!$kegiatanSppTu) {
                        $kegiatanSppTu = new KegiatanSppTu();
                    }
                    $kegiatanSppTu->spp_tu_id = $sppTu->id;
                    $kegiatanSppTu->kegiatan_id = $request[$request->kegiatan[$index]];
                    $kegiatanSppTu->jumlah_anggaran = $jumlahAnggaran;
                    $kegiatanSppTu->save();
                }

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

                if (($sppTu->status_validasi_asn == 2 || $sppTu->status_validasi_ppk == 2)) {
                    $riwayatSppTu = new RiwayatSppTu();

                    if ($request->file('surat_pengembalian')) {
                        $namaFileSuratPengembalian = "surat-pengembalian" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->file('surat_pengembalian')->storeAs(
                            'surat_pengembalian_spp_tu',
                            $namaFileSuratPengembalian
                        );
                        $riwayatSppTu->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppTu->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppTu->surat_penolakan = null;
                    }

                    $riwayatSppTu->spp_tu_id = $sppTu->id;
                    $riwayatSppTu->user_id = Auth::user()->id;
                    $riwayatSppTu->status = 'Diperbaiki';
                    $riwayatSppTu->save();
                    $sppTu->tahap_riwayat = $sppTu->tahap_riwayat + 1;
                }

                if ($sppTu->status_validasi_ppk == 2) {
                    $sppTu->status_validasi_ppk = 0;
                    $sppTu->alasan_validasi_ppk = null;
                }

                if ($sppTu->status_validasi_asn == 2) {
                    $sppTu->status_validasi_asn = 0;
                    $sppTu->alasan_validasi_asn = null;
                }

                $sppTu->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppTu->tahun_id = $request->tahun;
                $sppTu->bulan = $request->bulan;
                $sppTu->nomor_surat = $request->nomor_surat;
                $sppTu->save();
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

            if (Storage::exists('surat_pengembalian_spp_tu/' . $namaFileSuratPengembalian)) {
                Storage::delete('surat_pengembalian_spp_tu/' . $namaFileSuratPengembalian);
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
        if (!(Auth::user()->role == "Admin" || ($sppTu->status_validasi_asn == 0 && $sppTu->status_validasi_ppk == 0))) {
            return throw new Exception('Gagal Diproses');
        }

        $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $sppTu->id)->get();

        $arraySuratPenolakan = null;
        $arraySuratPengembalian = null;

        $arrayDokumen = $sppTu->dokumenSppTu->pluck('dokumen');
        if ($riwayatSppTu) {
            $arraySuratPenolakan = $riwayatSppTu->pluck('surat_penolakan');
            $arraySuratPengembalian = $riwayatSppTu->pluck('surat_pengembalian');
        }

        $spm = $sppTu->dokumen_spm;
        $sp2d = $sppTu->dokumen_sp2d;

        try {
            DB::transaction(
                function () use ($sppTu) {
                    $sppTu->delete();
                    $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $sppTu->id)->delete();
                    $dokumenSppLs = DokumenSppTu::where('spp_tu_id', $sppTu->id)->delete();
                    $kegiatanSppLs = KegiatanSppTu::where('spp_tu_id', $sppTu->id)->delete();
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
        if (count($arraySuratPengembalian) > 0) {
            foreach ($arraySuratPengembalian as $suratPengembalian) {
                Storage::delete('surat_pengembalian_spp_tu/' . $suratPengembalian);
            }
        }

        if (count($arrayDokumen) > 0) {
            foreach ($arrayDokumen as $dokumen) {
                Storage::delete('dokumen_spp_tu/' . $dokumen);
            }
        }

        if ($spm) {
            if (Storage::exists('dokumen_spm_spp_tu/' . $spm)) {
                Storage::delete('dokumen_spm_spp_tu/' . $spm);
            }
        }

        if ($sp2d) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_tu/' . $sp2d)) {
                Storage::delete('dokumen_arsip_sp2d_spp_tu/' . $sp2d);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppTu $sppTu)
    {
        $tipeSuratPenolakan = 'spp-tu';
        $tipeSuratPengembalian = 'spp_tu';
        $role = Auth::user()->role;

        if (!((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        return view('dashboard.pages.spp.sppTu.riwayat', compact(['sppTu', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
    }

    public function verifikasi(Request $request, SppTu $sppTu)
    {
        if (!(in_array(Auth::user()->role, ['ASN Sub Bagian Keuangan', 'PPK']) && $sppTu->status_validasi_akhir == 0 && Auth::user()->is_aktif == 1)) {
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
                function () use ($sppTu, $request, &$namaFileSuratPenolakan) {

                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppTu->status_validasi_asn = $request->verifikasi;
                        $sppTu->alasan_validasi_asn = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppTu->tanggal_validasi_asn = Carbon::now();
                        $riwayatTerakhir = RiwayatSppTu::where('role', 'ASN Sub Bagian Keuangan')->where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $sppTu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    } else {
                        $sppTu->status_validasi_ppk = $request->verifikasi;
                        $sppTu->alasan_validasi_ppk = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppTu->tanggal_validasi_ppk = Carbon::now();
                        $riwayatTerakhir = RiwayatSppTu::where('role', 'PPK')->where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $sppTu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    }
                    $sppTu->save();

                    $riwayatTerakhir = RiwayatSppTu::whereNotNull('nomor_surat')->where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $sppTu->tahap_riwayat)->first();

                    $riwayatSppTu = new RiwayatSppTu();
                    $riwayatSppTu->spp_tu_id = $sppTu->id;
                    $riwayatSppTu->user_id = Auth::user()->id;
                    $riwayatSppTu->tahap_riwayat = $sppTu->tahap_riwayat;
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
                    $riwayatSppTu->alasan = $request->alasan;
                    $riwayatSppTu->role = Auth::user()->role;
                    $riwayatSppTu->save();

                    if (($sppTu->status_validasi_asn == 2 || $sppTu->status_validasi_ppk == 2) && ($sppTu->status_validasi_asn != 0 && $sppTu->status_validasi_ppk != 0)) {
                        $programDanKegiatan = [];
                        $totalProgramDanKegiatan = [];
                        $totalJumlahAnggaran = 0;

                        foreach ($sppTu->kegiatanSppTu as $kegiatanSppTu) {
                            $program = $kegiatanSppTu->kegiatan->program->nama . ' (' . $kegiatanSppTu->kegiatan->program->no_rek . ')';
                            $kegiatan = $kegiatanSppTu->kegiatan->nama . ' (' . $kegiatanSppTu->kegiatan->no_rek . ')';
                            $jumlahAnggaran = $kegiatanSppTu->jumlah_anggaran;

                            $programDanKegiatan[] = [
                                'program' => $program,
                                'kegiatan' => $kegiatan,
                                'jumlah_anggaran' => $jumlahAnggaran,
                            ];

                            $totalJumlahAnggaran += $jumlahAnggaran;
                        }

                        $totalProgramDanKegiatan = [
                            'total_jumlah_anggaran' => $totalJumlahAnggaran,
                        ];

                        $hariIni = Carbon::now()->translatedFormat('d F Y');
                        $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $sppTu->tahap_riwayat)->where('status', 'Ditolak')->orderBy('updated_at', 'desc')->first();
                        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
                        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();

                        $pdf = Pdf::loadView('dashboard.pages.spp.sppTu.suratPenolakan', compact(['sppTu', 'riwayatSppTu', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran', 'programDanKegiatan', 'totalProgramDanKegiatan']))->setPaper('f4', 'portrait');
                        $namaFileSuratPenolakan = 'surat-penolakan-' . time() . '.pdf';
                        Storage::put('surat_penolakan_spp_tu/' . $namaFileSuratPenolakan, $pdf->output());

                        $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $sppTu->tahap_riwayat)->where('status', 'Ditolak')->get();
                        foreach ($riwayatSppTu as $riwayat) {
                            if (Storage::exists('surat_penolakan_spp_tu/' . $riwayat->surat_penolakan)) {
                                Storage::delete('surat_penolakan_spp_tu/' . $riwayat->surat_penolakan);
                            }

                            $riwayat->surat_penolakan = $namaFileSuratPenolakan;
                            $riwayat->save();
                        }

                        $sppTu = SppTu::where('id', $sppTu->id)->first();
                        $sppTu->surat_penolakan = $namaFileSuratPenolakan;
                        $sppTu->save();
                    }
                }
            );
        } catch (QueryException $error) {
            if (Storage::exists('surat_penolakan_spp_tu/' . $namaFileSuratPenolakan)) {
                Storage::delete('surat_penolakan_spp_tu/' . $namaFileSuratPenolakan);
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppTu $sppTu)
    {
        if (!($sppTu->status_validasi_ppk == 1 && $sppTu->status_validasi_akhir == 0 && $sppTu->status_validasi_asn == 1 && Auth::user()->is_aktif == 1)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        try {
            DB::transaction(
                function () use ($sppTu) {
                    $sppTu->surat_penolakan = NULL;
                    $sppTu->surat_pengembalian = NULL;
                    $sppTu->status_validasi_akhir = 1;
                    $sppTu->tanggal_validasi_akhir = Carbon::now();
                    $sppTu->save();

                    $riwayatSppTu = new RiwayatSppTu();
                    $riwayatSppTu->spp_tu_id = $sppTu->id;
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

    public function storeSpm(Request $request, SppTu $sppTu)
    {
        if (!(($sppTu->status_validasi_ppk == 1 && $sppTu->status_validasi_asn == 1 && $sppTu->status_validasi_akhir == 1 && !$sppTu->dokumen_arsip_sp2d))) {
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

        $namaDokumenSebelumnya = $sppTu->dokumen_spm ?? null;

        $namaDokumen = '';

        if ($request->dokumen_spm) {
            $namaDokumen = time() . '.' . $request->dokumen_spm->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppTu, $namaDokumen) {
                if ($request->dokumen_spm) {
                    $request->dokumen_spm->storeAs('dokumen_spm_spp_tu', $namaDokumen);
                }

                $sppTu->dokumen_spm = $namaDokumen;
                $sppTu->save();

                $riwayatSppTu = RiwayatSppTu::where('status', 'Upload SPM')->where('spp_tu_id', $sppTu->id)->delete();

                $riwayatSppTu = new RiwayatSppTu();
                $riwayatSppTu->spp_tu_id = $sppTu->id;
                $riwayatSppTu->user_id = Auth::user()->id;
                $riwayatSppTu->status = 'Upload SPM';
                $riwayatSppTu->role = Auth::user()->role;
                $riwayatSppTu->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_spm) {
                if (Storage::exists('dokumen_spm_spp_tu/' . $namaDokumen)) {
                    Storage::delete('dokumen_spm_spp_tu/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_spm_spp_tu/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_spm_spp_tu/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function storeSp2d(Request $request, SppTu $sppTu)
    {
        if (!((Auth::user()->role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) && ($sppTu->status_validasi_ppk == 1 && $sppTu->status_validasi_asn == 1 && $sppTu->status_validasi_akhir == 1 && $sppTu->dokumen_spm))) {
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

        $namaDokumenSebelumnya = $sppTu->dokumen_arsip_sp2d ?? null;

        $namaDokumen = '';

        if ($request->dokumen_arsip_sp2d) {
            $namaDokumen = time() . '.' . $request->dokumen_arsip_sp2d->extension();
        }

        try {
            DB::transaction(function () use ($request, $sppTu, $namaDokumen) {
                if ($request->dokumen_arsip_sp2d) {
                    $request->dokumen_arsip_sp2d->storeAs('dokumen_arsip_sp2d_spp_tu', $namaDokumen);
                }

                $sppTu->dokumen_arsip_sp2d = $namaDokumen;
                $sppTu->save();

                $riwayatSppTu = RiwayatSppTu::where('status', 'Upload Arsip SP2D')->where('spp_tu_id', $sppTu->id)->delete();

                $riwayatSppTu = new RiwayatSppTu();
                $riwayatSppTu->spp_tu_id = $sppTu->id;
                $riwayatSppTu->user_id = Auth::user()->id;
                $riwayatSppTu->status = 'Upload Arsip SP2D';
                $riwayatSppTu->role = Auth::user()->role;
                $riwayatSppTu->save();
            });
        } catch (QueryException $error) {
            if ($request->dokumen_arsip_sp2d) {
                if (Storage::exists('dokumen_arsip_sp2d_spp_tu/' . $namaDokumen)) {
                    Storage::delete('dokumen_arsip_sp2d_spp_tu/' . $namaDokumen);
                }
            }
            return throw new Exception($error);
        }

        if ($namaDokumenSebelumnya) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_tu/' . $namaDokumenSebelumnya)) {
                Storage::delete('dokumen_arsip_sp2d_spp_tu/' . $namaDokumenSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function cekSp2d()
    {
        if (Auth::user()->role != "Admin") {
            $totalSppTu = SppTu::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSppTu > 0) {
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
