<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanSpjGu;
use App\Models\Program;
use App\Models\RiwayatSpjGu;
use App\Models\SekretariatDaerah;
use App\Models\Spd;
use App\Models\SpjGu;
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
use Illuminate\Support\Str;

class SpjGuController extends Controller
{
    public function index()
    {
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();

        return view('dashboard.pages.spp.spjGu.index', compact('daftarSekretariatDaerah', 'daftarTahun'));
    }

    public function create()
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.spjGu.create', compact(['daftarTahun', 'daftarSekretariatDaerah']));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $rules = [
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'nomor_surat' => 'required',
            'tahun' => 'required',
            'bulan' => 'required',
        ];

        $messages = [
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'nomor_surat.required' => 'Nomor Surat Permintaan Pembayaran (SPP) Tidak Boleh Kosong',
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

        if ($request->anggaranDigunakan) {
            foreach ($request->anggaranDigunakan as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Anggaran digunakan tidak boleh kosong";
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

        $arrayJumlahAnggaran = json_decode($request->arrayJumlahAnggaran, true);
        if ($request->anggaranDigunakan) {
            foreach ($request->anggaranDigunakan as $index => $nama) {
                if (isset($arrayJumlahAnggaran[$index]['jumlah_anggaran'])) {
                    $anggaranDigunakan = str_replace('.', '', $request["$nama"]);
                    $jumlahAnggaran = $arrayJumlahAnggaran[$index]['jumlah_anggaran'];
                    if ($anggaranDigunakan > $jumlahAnggaran) {
                        $validator->after(function ($validator) use ($nama) {
                            $validator->errors()->add($nama, 'Anggaran Digunakan Melebihi Jumlah Anggaran');
                        });
                    }
                }
            }
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, $role) {
                $spjGu = new SpjGu();
                $spjGu->user_id = Auth::user()->id;
                $spjGu->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $spjGu->tahun_id = $request->tahun;
                $spjGu->bulan = $request->bulan;
                $spjGu->nomor_surat = $request->nomor_surat;
                $spjGu->save();

                foreach ($request->anggaranDigunakan as $index => $nama) {
                    $namaFileBerkas = time() . rand(1, 9999) . '.' . $request[$request->fileDokumen[$index]]->extension();
                    $request[$request->fileDokumen[$index]]->storeAs('dokumen_spj_gu', $namaFileBerkas);
                    $arrayFileDokumen[] = $namaFileBerkas;

                    $anggaranDigunakan = str_replace('.', '', $request["$nama"]);
                    $kegiatanSpjGu = new KegiatanSpjGu();
                    $kegiatanSpjGu->spj_gu_id = $spjGu->id;
                    $kegiatanSpjGu->kegiatan_id = $request[$request->kegiatan[$index]];
                    $kegiatanSpjGu->anggaran_digunakan = $anggaranDigunakan;
                    $kegiatanSpjGu->dokumen = $namaFileBerkas;
                    $kegiatanSpjGu->save();
                }

                $riwayatSpjGu = new RiwayatSpjGu();
                $riwayatSpjGu->spj_gu_id = $spjGu->id;
                $riwayatSpjGu->user_id = Auth::user()->id;
                $riwayatSpjGu->status = 'Dibuat';
                $riwayatSpjGu->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spj_gu/' . $nama)) {
                    Storage::delete('dokumen_spj_gu/' . $nama);
                }
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(SpjGu $spjGu)
    {
        $tipe = 'spj_gu';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $spjGu->sekretariat_daerah_id) {
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

            return view('dashboard.pages.spp.spjGu.show', compact(['spjGu', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function edit(SpjGu $spjGu)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $spjGu->sekretariat_daerah_id) && ($spjGu->status_validasi_asn == 2 || $spjGu->status_validasi_ppk == 2)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        $daftarProgram = Program::orderBy('no_rek', 'asc')->whereHas('kegiatan', function ($query) use ($spjGu) {
            $query->whereHas('spd', function ($query) use ($spjGu) {
                $query->where('sekretariat_daerah_id', $spjGu->sekretariat_daerah_id);
            });
        })->orderBy('no_rek', 'asc')->get();

        $programDanKegiatan = null;
        $arrayJumlahAnggaran = [];
        foreach ($spjGu->kegiatanSpjGu as $kegiatanSpjGu) {
            $jumlahAnggaran = jumlah_anggaran($spjGu->sekretariat_daerah_id, $kegiatanSpjGu->kegiatan_id, $spjGu->bulan_id, $spjGu->tahun_id, $spjGu->id);

            $daftarKegiatan = Kegiatan::where('program_id', $kegiatanSpjGu->kegiatan->program_id)->whereHas('spd', function ($query) use ($spjGu) {
                $query->where('tahun_id', $spjGu->tahun_id);
                $query->where('sekretariat_daerah_id', $spjGu->sekretariat_daerah_id);
            })->orderBy('no_rek', 'asc')->get();

            $dataKey = Str::random(5) . rand(111, 999) . Str::random(5);
            $programDanKegiatan .= view('dashboard.components.dynamicForm.spjGu', compact(['jumlahAnggaran', 'daftarProgram', 'daftarKegiatan', 'kegiatanSpjGu', 'dataKey']))->render();

            $arrayJumlahAnggaran[] = [
                'key' => $dataKey,
                'jumlah_anggaran' => $jumlahAnggaran ?? 0
            ];
        }

        return view('dashboard.pages.spp.spjGu.edit', compact(['spjGu', 'daftarTahun', 'daftarSekretariatDaerah', 'daftarProgram',  'programDanKegiatan', 'arrayJumlahAnggaran']));
    }

    public function update(Request $request, SpjGu $spjGu)
    {
        $role = Auth::user()->role;
        if (!($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $spjGu->sekretariat_daerah_id) && (($spjGu->status_validasi_asn == 0 && $spjGu->status_validasi_ppk == 0) || ($spjGu->status_validasi_asn == 2 || $spjGu->status_validasi_ppk == 2))) {
            return throw new Exception('Terjadi Kesalahan');
        }

        if (!($spjGu->status_validasi_asn == 2 || $spjGu->status_validasi_ppk == 2)) {
            $suratPengembalian = 'nullable';
        } else {
            $suratPengembalian = 'required';
        }

        $rules = [
            'surat_pengembalian' => $suratPengembalian . '|mimes:pdf',
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'nomor_surat' => 'required',
            'tahun' => 'required',
            'bulan' => 'required',
        ];

        $messages = [
            'surat_pengembalian.required' => 'Surat Penolakan tidak boleh kosong',
            'surat_pengembalian.mimes' => 'Dokumen Harus Berupa File PDF',
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'nomor_surat.required' => 'Nomor Surat Permintaan Pembayaran (SPP) Tidak Boleh Kosong',
            'tahun.required' => 'Tahun Tidak Boleh Kosong',
            'bulan.required' => 'Bulan Tidak Boleh Kosong',
        ];

        if ($request->program) {
            foreach ($request->program as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Program tidak boleh kosong";
            }
        }

        if ($request->kegiatan) {
            foreach ($request->kegiatan as $index => $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Kegiatan tidak boleh kosong";

                $namaDokumen = $request->fileDokumen[$index];
                if ($request["$nama"]) {
                    $kegiatanSpjGu = KegiatanSpjGu::where('spj_gu_id', $spjGu->id)->where('kegiatan_id', $request["$nama"])->first();
                    if ($kegiatanSpjGu) {
                        $rules["$namaDokumen"] = 'mimes:pdf';
                        $messages["$namaDokumen.required"] = "File tidak boleh kosong";
                        $messages["$namaDokumen.mimes"] = "File harus berupa file pdf";
                    } else {
                        $rules["$namaDokumen"] = 'required|mimes:pdf';
                        $messages["$namaDokumen.required"] = "File tidak boleh kosong";
                        $messages["$namaDokumen.mimes"] = "File harus berupa file pdf";
                    }
                } else {
                    $rules["$namaDokumen"] = 'required|mimes:pdf';
                    $messages["$namaDokumen.required"] = "File tidak boleh kosong";
                    $messages["$namaDokumen.mimes"] = "File harus berupa file pdf";
                }
            }
        }

        if ($request->anggaranDigunakan) {
            foreach ($request->anggaranDigunakan as $nama) {
                $rules["$nama"] = 'required';
                $messages["$nama.required"] = "Anggaran digunakan tidak boleh kosong";
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);


        if (!$request->program) {
            $validator->after(function ($validator) {
                $validator->errors()->add('programDanKegiatanHitung', 'Program dan Kegiatan Minimal 1');
            });
        }

        $arrayJumlahAnggaran = json_decode($request->arrayJumlahAnggaran, true);
        if ($request->anggaranDigunakan) {
            foreach ($request->anggaranDigunakan as $index => $nama) {
                if ($request["$nama"]) {
                    $anggaranDigunakan = str_replace('.', '', $request["$nama"]);
                    $jumlahAnggaran = $arrayJumlahAnggaran[$index]['jumlah_anggaran'];
                    if ($anggaranDigunakan > $jumlahAnggaran) {
                        $validator->after(function ($validator) use ($nama) {
                            $validator->errors()->add($nama, 'Anggaran Digunakan Melebihi Jumlah Anggaran');
                        });
                    }
                }
            }
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $arrayFileDokumen = [];
        $arrayFileDokumenHapus = [];
        $arrayKegiatan = [];
        foreach ($request->kegiatan as $index => $nama) {
            $arrayKegiatan[] = $request["$nama"];
        }

        $namaFileSuratPengembalian = '';

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, $role, $spjGu, $arrayKegiatan, &$arrayFileDokumenHapus, &$namaFileSuratPengembalian) {
                $kegiatanSpjGu = KegiatanSpjGu::whereNotIn('kegiatan_id', $arrayKegiatan)->where('spj_gu_id', $spjGu->id)->get();
                $arrayFileDokumenHapus = $kegiatanSpjGu->pluck('dokumen');
                foreach ($kegiatanSpjGu as $kegiatan) {
                    $kegiatan->delete();
                }

                foreach ($request->anggaranDigunakan as $index => $nama) {
                    $anggaranDigunakan = str_replace('.', '', $request["$nama"]);
                    $namaDokumen = $request->fileDokumen[$index];
                    $kegiatanSpjGu = KegiatanSpjGu::where('spj_gu_id', $spjGu->id)->where('kegiatan_id', $request[$request->kegiatan[$index]])->first();
                    if ($request["$namaDokumen"]) {
                        $namaFileBerkas = time() . rand(1, 9999) . '.' . $request[$request->fileDokumen[$index]]->extension();
                        $request[$request->fileDokumen[$index]]->storeAs('dokumen_spj_gu', $namaFileBerkas);
                        $arrayFileDokumen[] = $namaFileBerkas;
                    }

                    if ($kegiatanSpjGu) {
                        if ($request["$namaDokumen"]) {
                            $arrayFileDokumenHapus[] = $kegiatanSpjGu->dokumen;
                        }
                    } else {
                        $kegiatanSpjGu = new KegiatanSpjGu();
                    }

                    $kegiatanSpjGu->spj_gu_id = $spjGu->id;
                    $kegiatanSpjGu->kegiatan_id = $request[$request->kegiatan[$index]];
                    $kegiatanSpjGu->anggaran_digunakan = $anggaranDigunakan;
                    if ($request["$namaDokumen"]) {
                        $kegiatanSpjGu->dokumen =  $namaFileBerkas;
                    }
                    $kegiatanSpjGu->save();
                }

                if (($spjGu->status_validasi_asn == 2 || $spjGu->status_validasi_ppk == 2)) {
                    $riwayatSpjGu = new RiwayatSpjGu();

                    if ($request->file('surat_pengembalian')) {
                        $namaFileSuratPengembalian = "surat-pengembalian" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->file('surat_pengembalian')->storeAs(
                            'surat_pengembalian_spj_gu',
                            $namaFileSuratPengembalian
                        );
                        $riwayatSpjGu->surat_pengembalian = $namaFileSuratPengembalian;
                        $spjGu->surat_pengembalian = $namaFileSuratPengembalian;
                        $spjGu->surat_penolakan = null;
                    }

                    $riwayatSpjGu->spj_gu_id = $spjGu->id;
                    $riwayatSpjGu->user_id = Auth::user()->id;
                    $riwayatSpjGu->status = 'Diperbaiki';
                    $riwayatSpjGu->save();
                    $spjGu->tahap_riwayat = $spjGu->tahap_riwayat + 1;
                }

                if ($spjGu->status_validasi_ppk == 2) {
                    $spjGu->status_validasi_ppk = 0;
                    $spjGu->alasan_validasi_ppk = null;
                }

                if ($spjGu->status_validasi_asn == 2) {
                    $spjGu->status_validasi_asn = 0;
                    $spjGu->alasan_validasi_asn = null;
                }

                $spjGu->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $spjGu->tahun_id = $request->tahun;
                $spjGu->bulan = $request->bulan;
                $spjGu->nomor_surat = $request->nomor_surat;
                $spjGu->save();
            });
        } catch (QueryException $error) {
            foreach ($arrayFileDokumen as $nama) {
                if (Storage::exists('dokumen_spj_gu/' . $nama)) {
                    Storage::delete('dokumen_spj_gu/' . $nama);
                }
            }

            if (Storage::exists('surat_pengembalian_spj_gu/' . $namaFileSuratPengembalian)) {
                Storage::delete('surat_pengembalian_spj_gu/' . $namaFileSuratPengembalian);
            }
            return throw new Exception($error);
        }

        foreach ($arrayFileDokumenHapus as $nama) {
            if (Storage::exists('dokumen_spj_gu/' . $nama)) {
                Storage::delete('dokumen_spj_gu/' . $nama);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(SpjGu $spjGu)
    {
        if (!(Auth::user()->role == "Admin" || ($spjGu->status_validasi_asn == 0 && $spjGu->status_validasi_ppk == 0))) {
            return throw new Exception('Gagal Diproses');
        }

        $riwayatSpjGu = RiwayatSpjGu::where('spj_gu_id', $spjGu->id)->get();
        $kegiatanSpjGu = KegiatanSpjGu::where('spj_gu_id', $spjGu->id)->get();

        $arraySuratPenolakan = null;
        $arraySuratPengembalian = null;

        $arrayDokumenKegiatan = $kegiatanSpjGu->pluck('dokumen');
        if ($riwayatSpjGu) {
            $arraySuratPenolakan = $riwayatSpjGu->pluck('surat_penolakan');
            $arraySuratPengembalian = $riwayatSpjGu->pluck('surat_pengembalian');
        }

        try {
            DB::transaction(
                function () use ($spjGu) {
                    $spjGu->delete();
                    $riwayatSpjGu = RiwayatSpjGu::where('spj_gu_id', $spjGu->id)->delete();
                    $kegiatanSpjGu = KegiatanSpjGu::where('spj_gu_id', $spjGu->id)->delete();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        if (count($arraySuratPenolakan) > 0) {
            foreach ($arraySuratPenolakan as $suratPenolakan) {
                Storage::delete('surat_penolakan_spj_gu/' . $suratPenolakan);
            }
        }
        if (count($arraySuratPengembalian) > 0) {
            foreach ($arraySuratPengembalian as $suratPengembalian) {
                Storage::delete('surat_pengembalian_spj_gu/' . $suratPengembalian);
            }
        }

        if (count($arrayDokumenKegiatan) > 0) {
            foreach ($arrayDokumenKegiatan as $dokumen) {
                Storage::delete('dokumen_spj_gu/' . $dokumen);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SpjGu $spjGu)
    {
        $tipeSuratPenolakan = 'spj-gu';
        $tipeSuratPengembalian = 'spj_gu';

        $role = Auth::user()->role;
        if (!((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran', 'Operator SPM'])) || Auth::user()->profil->sekretariat_daerah_id == $spjGu->sekretariat_daerah_id)) {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }

        return view('dashboard.pages.spp.spjGu.riwayat', compact(['spjGu', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
    }

    public function verifikasi(Request $request, SpjGu $spjGu)
    {
        if (!(in_array(Auth::user()->role, ['ASN Sub Bagian Keuangan', 'PPK']) && $spjGu->status_validasi_akhir == 0 && Auth::user()->is_aktif == 1)) {
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
                function () use ($spjGu, $request, &$namaFileSuratPenolakan) {
                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $spjGu->status_validasi_asn = $request->verifikasi;
                        $spjGu->alasan_validasi_asn = $request->verifikasi != '1' ? $request->alasan : null;
                        $spjGu->tanggal_validasi_asn = Carbon::now();
                        $riwayatTerakhir = RiwayatSpjGu::where('role', 'ASN Sub Bagian Keuangan')->where('spj_gu_id', $spjGu->id)->where('tahap_riwayat', $spjGu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    } else {
                        $spjGu->status_validasi_ppk = $request->verifikasi;
                        $spjGu->alasan_validasi_ppk = $request->verifikasi != '1' ? $request->alasan : null;
                        $spjGu->tanggal_validasi_ppk = Carbon::now();
                        $riwayatTerakhir = RiwayatSpjGu::where('role', 'PPK')->where('spj_gu_id', $spjGu->id)->where('tahap_riwayat', $spjGu->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    }
                    $spjGu->save();

                    $riwayatTerakhir = RiwayatSpjGu::whereNotNull('nomor_surat')->where('spj_gu_id', $spjGu->id)->where('tahap_riwayat', $spjGu->tahap_riwayat)->first();
                    $riwayatSpjGu = new RiwayatSpjGu();
                    $riwayatSpjGu->spj_gu_id = $spjGu->id;
                    $riwayatSpjGu->user_id = Auth::user()->id;
                    $riwayatSpjGu->tahap_riwayat = $spjGu->tahap_riwayat;
                    $riwayatSpjGu->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
                    if ($request->verifikasi == 2) {
                        $nomorSurat = DB::table('riwayat_spj_gu')
                            ->select(['spj_gu_id', 'tahap_riwayat'], DB::raw('count(*) as total'))
                            ->groupBy(['spj_gu_id', 'tahap_riwayat'])
                            ->whereNotNull('nomor_surat')
                            ->get()
                            ->count();
                        $riwayatSpjGu->nomor_surat = $riwayatTerakhir ? $riwayatTerakhir->nomor_surat : ($nomorSurat + 1) . "/SPJ-GU/P/" . Carbon::now()->format('m') . "/" . Carbon::now()->format('Y');
                    }
                    $riwayatSpjGu->alasan = $request->alasan;
                    $riwayatSpjGu->role = Auth::user()->role;
                    $riwayatSpjGu->save();

                    if (($spjGu->status_validasi_asn == 2 || $spjGu->status_validasi_ppk == 2) && ($spjGu->status_validasi_asn != 0 && $spjGu->status_validasi_ppk != 0)) {
                        $spd = Spd::where('kegiatan_id', $spjGu->kegiatan_id)->where('tahun_id', $spjGu->tahun_id)->where('sekretariat_daerah_id', $spjGu->sekretariat_daerah_id)->first();

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
                        $riwayatSpjGu = RiwayatSpjGu::where('spj_gu_id', $spjGu->id)->where('tahap_riwayat', $spjGu->tahap_riwayat)->where('status', 'Ditolak')->orderBy('updated_at', 'desc')->first();
                        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
                        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();

                        $pdf = Pdf::loadView('dashboard.pages.spp.spjGu.suratPenolakan', compact(['spjGu', 'riwayatSpjGu', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran', 'spd', 'programDanKegiatan', 'totalProgramDanKegiatan']))->setPaper('f4', 'portrait');
                        $namaFileSuratPenolakan = 'surat-penolakan-' . time() . '.pdf';
                        Storage::put('surat_penolakan_spj_gu/' . $namaFileSuratPenolakan, $pdf->output());

                        $riwayatSpjGu = RiwayatSpjGu::where('spj_gu_id', $spjGu->id)->where('tahap_riwayat', $spjGu->tahap_riwayat)->where('status', 'Ditolak')->get();
                        foreach ($riwayatSpjGu as $riwayat) {
                            if (Storage::exists('surat_penolakan_spj_gu/' . $riwayat->surat_penolakan)) {
                                Storage::delete('surat_penolakan_spj_gu/' . $riwayat->surat_penolakan);
                            }

                            $riwayat->surat_penolakan = $namaFileSuratPenolakan;
                            $riwayat->save();
                        }

                        $spjGu = SpjGu::where('id', $spjGu->id)->first();
                        $spjGu->surat_penolakan = $namaFileSuratPenolakan;
                        $spjGu->save();
                    }
                }
            );
        } catch (QueryException $error) {
            if (Storage::exists('surat_penolakan_spj_gu/' . $namaFileSuratPenolakan)) {
                Storage::delete('surat_penolakan_spj_gu/' . $namaFileSuratPenolakan);
            }

            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SpjGu $spjGu)
    {
        if (!($spjGu->status_validasi_ppk == 1 && $spjGu->status_validasi_akhir == 0 && $spjGu->status_validasi_asn == 1 && Auth::user()->is_aktif == 1)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        try {
            DB::transaction(
                function () use ($spjGu) {
                    $spjGu->status_validasi_akhir = 1;
                    $spjGu->surat_penolakan = NULL;
                    $spjGu->surat_pengembalian = NULL;
                    $spjGu->tanggal_validasi_akhir = Carbon::now();
                    $spjGu->save();

                    $riwayatSpjGu = new RiwayatSpjGu();
                    $riwayatSpjGu->spj_gu_id = $spjGu->id;
                    $riwayatSpjGu->user_id = Auth::user()->id;
                    $riwayatSpjGu->status = 'Diselesaikan';
                    $riwayatSpjGu->save();
                }
            );
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
