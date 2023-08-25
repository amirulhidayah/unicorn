<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppLs;
use App\Models\SekretariatDaerah;
use App\Models\DokumenSppLs;
use App\Models\KategoriSppLs;
use App\Models\Kegiatan;
use App\Models\KegiatanSppLs;
use App\Models\Program;
use App\Models\RiwayatSppLs;
use App\Models\Spd;
use App\Models\SppGu;
use App\Models\SppLs;
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

class SppLsController extends Controller
{
    public function index(Request $request)
    {
        $totalSpp = SppLs::where(function ($query) {
            if (!in_array(Auth::user()->role, ['Admin', 'Operator SPM'])) {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }
        })->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->where('status_validasi_akhir', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();

        return view('dashboard.pages.spp.sppLs.index', compact('totalSpp'));
    }

    public function create()
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarDokumenSppLs = DaftarDokumenSppLs::orderBy('nama', 'asc')->get();
        $daftarKategori = KategoriSppLs::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.sppLs.create', compact(['daftarTahun', 'daftarSekretariatDaerah', 'daftarDokumenSppLs', 'daftarKategori']));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != "Admin") {
            $totalSpp = SppLs::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSpp > 0) {
                return throw new Exception('Terjadi Kesalahan');
            }
        }

        $role = Auth::user()->role;

        $rules = [
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'kategori' => 'required',
            'nomor_surat' => ['required', Rule::unique('spp_ls')->where(function ($query) use ($request) {
                return $query->where('nomor_surat', $request->nomor_surat);
            })],
            'tahun' => 'required',
            'bulan' => 'required',
        ];

        $messages = [
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'kategori.required' => 'Kategori Tidak Boleh Kosong',
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
                $sppLs->bulan = $request->bulan;
                $sppLs->kategori_spp_ls_id = $request->kategori;
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

                foreach ($request->anggaranDigunakan as $index => $nama) {
                    $anggaranDigunakan = str_replace('.', '', $request["$nama"]);
                    $kegiatanSppLs = new KegiatanSppLs();
                    $kegiatanSppLs->spp_ls_id = $sppLs->id;
                    $kegiatanSppLs->kegiatan_id = $request[$request->kegiatan[$index]];
                    $kegiatanSppLs->anggaran_digunakan = $anggaranDigunakan;
                    $kegiatanSppLs->save();
                }

                $riwayatSppLs = new RiwayatSppLs();
                $riwayatSppLs->spp_ls_id = $sppLs->id;
                $riwayatSppLs->user_id = Auth::user()->id;
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

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppLs->sekretariat_daerah_id) {
            $totalJumlahAnggaran = 0;
            $totalAnggaranDigunakan = 0;
            $totalSisaAnggaran = 0;
            $programDanKegiatan = [];
            $totalProgramDanKegiatan = [];

            foreach ($sppLs->kegiatanSppLs as $kegiatanSppLs) {
                $program = $kegiatanSppLs->kegiatan->program->nama . ' (' . $kegiatanSppLs->kegiatan->program->no_rek . ')';
                $kegiatan = $kegiatanSppLs->kegiatan->nama . ' (' . $kegiatanSppLs->kegiatan->no_rek . ')';
                $jumlahAnggaran = jumlah_anggaran($sppLs->sekretariat_daerah_id, $kegiatanSppLs->kegiatan_id, $sppLs->bulan_id, $sppLs->tahun_id, $sppLs->id);
                $anggaranDigunakan = $kegiatanSppLs->anggaran_digunakan;
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

            return view('dashboard.pages.spp.sppLs.show', compact(['sppLs', 'tipe', 'programDanKegiatan', 'totalProgramDanKegiatan']));
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

        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarDokumenSppLs = DaftarDokumenSppLs::orderBy('nama', 'asc')->get();
        $daftarKategori = KategoriSppLs::orderBy('nama', 'asc')->get();

        $daftarProgram = Program::orderBy('no_rek', 'asc')->whereHas('kegiatan', function ($query) use ($sppLs) {
            $query->whereHas('spd', function ($query) use ($sppLs) {
                $query->where('sekretariat_daerah_id', $sppLs->sekretariat_daerah_id);
            });
        })->orderBy('no_rek', 'asc')->get();

        $programDanKegiatan = null;
        $arrayJumlahAnggaran = [];
        foreach ($sppLs->kegiatanSppLs as $kegiatanSppLs) {
            $jumlahAnggaran = jumlah_anggaran($sppLs->sekretariat_daerah_id, $kegiatanSppLs->kegiatan_id, $sppLs->bulan_id, $sppLs->tahun_id, $sppLs->id);

            $daftarKegiatan = Kegiatan::where('program_id', $kegiatanSppLs->kegiatan->program_id)->whereHas('spd', function ($query) use ($sppLs) {
                $query->where('tahun_id', $sppLs->tahun_id);
                $query->where('sekretariat_daerah_id', $sppLs->sekretariat_daerah_id);
            })->orderBy('no_rek', 'asc')->get();

            $dataKey = Str::random(5) . rand(111, 999) . Str::random(5);
            $programDanKegiatan .= view('dashboard.components.dynamicForm.sppLs', compact(['jumlahAnggaran', 'daftarProgram', 'daftarKegiatan', 'kegiatanSppLs', 'dataKey']))->render();

            $arrayJumlahAnggaran[] = [
                'key' => $dataKey,
                'jumlah_anggaran' => $jumlahAnggaran ?? 0
            ];
        }

        return view('dashboard.pages.spp.sppLs.edit', compact(['sppLs', 'daftarTahun', 'daftarSekretariatDaerah', 'daftarProgram', 'daftarDokumenSppLs', 'daftarKategori', 'programDanKegiatan', 'arrayJumlahAnggaran']));
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
            'surat_pengembalian' => $suratPenolakan . '|mimes:pdf',
            'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
            'kategori' => 'required',
            'nomor_surat' => ['required', Rule::unique('spp_ls')->where(function ($query) use ($request) {
                return $query->where('nomor_surat', $request->nomor_surat);
            })->ignore($sppLs->id)],
            'tahun' => 'required',
            'bulan' => 'required',
        ];

        $messages = [
            'surat_pengembalian.required' => 'Surat Pengembalian tidak boleh kosong',
            'surat_pengembalian.mimes' => 'Dokumen Harus Berupa File PDF',
            'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
            'kategori.required' => 'Kategori Tidak Boleh Kosong',
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
        $arrayFileDokumenSebelumnya = [];
        $arrayFileDokumenUpdate = [];
        $arrayFileDokumenHapus = [];
        $arrayKegiatan = [];
        foreach ($request->kegiatan as $index => $nama) {
            $arrayKegiatan[] = $request["$nama"];
        }

        $namaFileSuratPengembalian = '';

        try {
            DB::transaction(function () use ($request, &$arrayFileDokumen, &$arrayFileDokumenSebelumnya, &$arrayFileDokumenUpdate, &$arrayFileDokumenHapus, &$namaFileSuratPengembalian, $arrayKegiatan, $sppLs, $role) {

                $kegiatanSppLs = KegiatanSppLs::whereNotIn('kegiatan_id', $arrayKegiatan)->where('spp_ls_id', $sppLs->id)->delete();

                foreach ($request->anggaranDigunakan as $index => $nama) {
                    $anggaranDigunakan = str_replace('.', '', $request["$nama"]);
                    $kegiatanSppLs = KegiatanSppLs::where('spp_ls_id', $sppLs->id)->where('kegiatan_id', $request[$request->kegiatan[$index]])->first();
                    if (!$kegiatanSppLs) {
                        $kegiatanSppLs = new KegiatanSppLs();
                    }
                    $kegiatanSppLs->spp_ls_id = $sppLs->id;
                    $kegiatanSppLs->kegiatan_id = $request[$request->kegiatan[$index]];
                    $kegiatanSppLs->anggaran_digunakan = $anggaranDigunakan;
                    $kegiatanSppLs->save();
                }

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
                    $riwayatSppLs = new RiwayatSppLs();

                    if ($request->file('surat_pengembalian')) {
                        $namaFileSuratPengembalian = "surat-pengembalian" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                        $request->file('surat_pengembalian')->storeAs(
                            'surat_pengembalian_spp_ls',
                            $namaFileSuratPengembalian
                        );
                        $riwayatSppLs->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppLs->surat_pengembalian = $namaFileSuratPengembalian;
                        $sppLs->surat_penolakan = null;
                    }

                    $riwayatSppLs->spp_ls_id = $sppLs->id;
                    $riwayatSppLs->user_id = Auth::user()->id;
                    $riwayatSppLs->status = 'Diperbaiki';
                    $riwayatSppLs->save();
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

                $sppLs->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
                $sppLs->tahun_id = $request->tahun;
                $sppLs->bulan = $request->bulan;
                $sppLs->kategori_spp_ls_id = $request->kategori;
                $sppLs->nomor_surat = $request->nomor_surat;
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

            if (Storage::exists('surat_pengembalian_spp_ls/' . $namaFileSuratPengembalian)) {
                Storage::delete('surat_pengembalian_spp_ls/' . $namaFileSuratPengembalian);
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

        $riwayatSppLs = RiwayatSppLs::where('spp_ls_id', $sppLs->id)->get();

        $arraySuratPenolakan = null;
        $arraySuratPengembalian = null;

        $arrayDokumen = $sppLs->dokumenSppLs->pluck('dokumen');
        if ($riwayatSppLs) {
            $arraySuratPenolakan = $riwayatSppLs->pluck('surat_penolakan');
            $arraySuratPengembalian = $riwayatSppLs->pluck('surat_pengembalian');
        }

        $spm = $sppLs->dokumen_spm;
        $sp2d = $sppLs->dokumen_sp2d;

        try {
            DB::transaction(
                function () use ($sppLs) {
                    $sppLs->delete();
                    $riwayatSppLs = RiwayatSppLs::where('spp_ls_id', $sppLs->id)->delete();
                    $dokumenSppLs = DokumenSppLs::where('spp_ls_id', $sppLs->id)->delete();
                    $kegiatanSppLs = KegiatanSppLs::where('spp_ls_id', $sppLs->id)->delete();
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
        if (count($arraySuratPengembalian) > 0) {
            foreach ($arraySuratPengembalian as $suratPengembalian) {
                Storage::delete('surat_pengembalian_spp_ls/' . $suratPengembalian);
            }
        }

        if (count($arrayDokumen) > 0) {
            foreach ($arrayDokumen as $dokumen) {
                Storage::delete('dokumen_spp_ls/' . $dokumen);
            }
        }

        if ($spm) {
            if (Storage::exists('dokumen_spm_spp_ls/' . $spm)) {
                Storage::delete('dokumen_spm_spp_ls/' . $spm);
            }
        }

        if ($sp2d) {
            if (Storage::exists('dokumen_arsip_sp2d_spp_ls/' . $sp2d)) {
                Storage::delete('dokumen_arsip_sp2d_spp_ls/' . $sp2d);
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

        $namaFileSuratPenolakan = '';

        try {
            DB::transaction(
                function () use ($sppLs, $request, &$namaFileSuratPenolakan) {

                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        $sppLs->status_validasi_asn = $request->verifikasi;
                        $sppLs->alasan_validasi_asn = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppLs->tanggal_validasi_asn = Carbon::now();
                        $riwayatTerakhir = RiwayatSppLs::where('role', 'ASN Sub Bagian Keuangan')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    } else {
                        $sppLs->status_validasi_ppk = $request->verifikasi;
                        $sppLs->alasan_validasi_ppk = $request->verifikasi != '1' ? $request->alasan : null;
                        $sppLs->tanggal_validasi_ppk = Carbon::now();
                        $riwayatTerakhir = RiwayatSppLs::where('role', 'PPK')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->orderBy('created_at', 'desc')->delete();
                    }
                    $sppLs->save();

                    $riwayatTerakhir = RiwayatSppLs::whereNotNull('nomor_surat')->where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->first();

                    $riwayatSppLs = new RiwayatSppLs();
                    $riwayatSppLs->spp_ls_id = $sppLs->id;
                    $riwayatSppLs->user_id = Auth::user()->id;
                    $riwayatSppLs->tahap_riwayat = $sppLs->tahap_riwayat;
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

                    if (($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2) && ($sppLs->status_validasi_asn != 0 && $sppLs->status_validasi_ppk != 0)) {
                        $spd = Spd::where('kegiatan_id', $sppLs->kegiatan_id)->where('tahun_id', $sppLs->tahun_id)->where('sekretariat_daerah_id', $sppLs->sekretariat_daerah_id)->first();

                        $totalJumlahAnggaran = 0;
                        $totalAnggaranDigunakan = 0;
                        $totalSisaAnggaran = 0;
                        $programDanKegiatan = [];
                        $totalProgramDanKegiatan = [];

                        foreach ($sppLs->kegiatanSppLs as $kegiatanSppLs) {
                            $program = $kegiatanSppLs->kegiatan->program->nama . ' (' . $kegiatanSppLs->kegiatan->program->no_rek . ')';
                            $kegiatan = $kegiatanSppLs->kegiatan->nama . ' (' . $kegiatanSppLs->kegiatan->no_rek . ')';
                            $jumlahAnggaran = jumlah_anggaran($sppLs->sekretariat_daerah_id, $kegiatanSppLs->kegiatan_id, $sppLs->bulan_id, $sppLs->tahun_id, $sppLs->id);
                            $anggaranDigunakan = $kegiatanSppLs->anggaran_digunakan;
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
                        $riwayatSppLs = RiwayatSppLs::where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->where('status', 'Ditolak')->orderBy('updated_at', 'desc')->first();
                        $ppk = User::where('role', 'PPK')->where('is_aktif', 1)->first();
                        $kuasaPenggunaAnggaran = User::where('role', 'Kuasa Pengguna Anggaran')->where('is_aktif', 1)->first();

                        $pdf = Pdf::loadView('dashboard.pages.spp.sppLs.suratPenolakan', compact(['sppLs', 'riwayatSppLs', 'hariIni', 'ppk', 'kuasaPenggunaAnggaran', 'spd', 'programDanKegiatan', 'totalProgramDanKegiatan']))->setPaper('f4', 'portrait');
                        $namaFileSuratPenolakan = 'surat-penolakan-' . time() . '.pdf';
                        Storage::put('surat_penolakan_spp_ls/' . $namaFileSuratPenolakan, $pdf->output());

                        $riwayatSppLs = RiwayatSppLs::where('spp_ls_id', $sppLs->id)->where('tahap_riwayat', $sppLs->tahap_riwayat)->where('status', 'Ditolak')->get();
                        foreach ($riwayatSppLs as $riwayat) {
                            if (Storage::exists('surat_penolakan_spp_ls/' . $riwayat->surat_penolakan)) {
                                Storage::delete('surat_penolakan_spp_ls/' . $riwayat->surat_penolakan);
                            }

                            $riwayat->surat_penolakan = $namaFileSuratPenolakan;
                            $riwayat->save();
                        }

                        $sppLs = SppLs::where('id', $sppLs->id)->first();
                        $sppLs->surat_penolakan = $namaFileSuratPenolakan;
                        $sppLs->save();
                    }
                }
            );
        } catch (QueryException $error) {
            if (Storage::exists('surat_penolakan_spp_ls/' . $namaFileSuratPenolakan)) {
                Storage::delete('surat_penolakan_spp_ls/' . $namaFileSuratPenolakan);
            }

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
                    $sppLs->surat_penolakan = NULL;
                    $sppLs->surat_pengembalian = NULL;
                    $sppLs->tanggal_validasi_akhir = Carbon::now();
                    $sppLs->save();

                    $riwayatSppLs = new RiwayatSppLs();
                    $riwayatSppLs->spp_ls_id = $sppLs->id;
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
                'dokumen_spm' => 'required|mimes:pdf',
            ],
            [
                'dokumen_spm.required' => "File tidak boleh kosong",
                'dokumen_spm.mimes' => "File harus berupa file pdf",
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
                'dokumen_arsip_sp2d' => 'required|mimes:pdf',
            ],
            [
                'dokumen_arsip_sp2d.required' => "File tidak boleh kosong",
                'dokumen_arsip_sp2d.mimes' => "File harus berupa file pdf",
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
            $totalSpp = SppLs::where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id)->where('status_validasi_ppk', 1)->where('status_validasi_asn', 1)->whereNotNull('dokumen_spm')->whereNull('dokumen_arsip_sp2d')->count();
            if ($totalSpp > 0) {
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

        $daftarSppLs = SppLs::where('sekretariat_daerah_id', $sekretariatDaerah)
            ->orderBy('created_at', 'asc')
            ->where('tahun_id', $tahun)
            ->where('kegiatan_id', $kegiatan);

        if ($bulan == 'Januari') {
            $daftarSppLs = $daftarSppLs->where('bulan', 'Januari');
        } else if ($bulan == 'Februari') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari']);
        } else if ($bulan == 'Maret') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret']);
        } else if ($bulan == 'April') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April']);
        } else if ($bulan == 'Mei') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei']);
        } else if ($bulan == 'Juni') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni']);
        } else if ($bulan == 'Juli') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli']);
        } else if ($bulan == 'Agustus') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus']);
        } else if ($bulan == 'September') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September']);
        } else if ($bulan == 'Oktober') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober']);
        } else if ($bulan == 'November') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November']);
        } else if ($bulan == 'Desember') {
            $daftarSppLs = $daftarSppLs->whereIn('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
        }

        $daftarSppLs = $daftarSppLs->sum('anggaran_digunakan');
        $anggaranDigunakan = 0;
        foreach ($daftarSppLs as $sppLs) {
            $anggaranDigunakan += $sppLs->kegiatanSppLs->sum('anggaran_digunakan');
        }
        $totalSpp = $anggaranDigunakan;
        $jumlahAnggaran = (($spd->jumlah_anggaran ?? 0) - $totalSpp);

        return $jumlahAnggaran;
    }
}
