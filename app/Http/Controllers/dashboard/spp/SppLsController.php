<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\BiroOrganisasi;
use App\Models\DokumenSppLs;
use App\Models\RiwayatSppLs;
use App\Models\Spd;
use App\Models\SppLs;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SppLsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $biroOrganisasi = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->biro_organisasi_id : Auth::user()->profil->biro_organisasi_id;
        if ($request->ajax()) {
            $data = SppLs::where(function ($query) use ($request, $biroOrganisasi, $role) {
                if ($biroOrganisasi && $biroOrganisasi != 'Semua') {
                    $query->where('biro_organisasi_id', $biroOrganisasi);
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
                    $nama = $row->kegiatan->nama . " (" . $row->kegiatan->no_rek . ")";
                    return $nama;
                })
                ->addColumn('program', function ($row) {
                    $nama = $row->kegiatan->program->nama . " (" . $row->kegiatan->program->no_rek . ")";
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
                ->addColumn('biro_organisasi', function ($row) {
                    $biroOrganisasi = $row->biroOrganisasi->nama;
                    return $biroOrganisasi;
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

                    if ($row->biro_organisasi_id == Auth::user()->profil->biro_organisasi_id  || in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {

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

                        if (($row->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $row->biro_organisasi_id == Auth::user()->profil->biro_organisasi_id) || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-ls/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->status_validasi_asn == 1)) {
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
                ->rawColumns(['action', 'biro_organisasi', 'tanggal_dibuat', 'riwayat', 'periode', 'verifikasi_asn', 'verifikasi_ppk', 'anggaran_digunakan', 'status_verifikasi_akhir'])
                ->make(true);
        }

        $daftarBiroOrganisasi = BiroOrganisasi::orderBy('nama', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();

        return view('dashboard.pages.spp.sppLs.index', compact('daftarBiroOrganisasi', 'daftarTahun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarBiroOrganisasi = BiroOrganisasi::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppLs.create', compact(['daftarTahun', 'daftarBiroOrganisasi']));
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

        $validator = Validator::make(
            $request->all(),
            [
                'nama_file' => 'required',
                'nama_file.*' => 'required',
                'file_dokumen.*' => 'mimes:pdf|max:5120',
                'biro_organisasi' => $role == "Admin" ? 'required' : 'nullable',
                'kategori' => 'required',
                'tahun' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'bulan' => 'required',
                'anggaran_digunakan' => 'required',
            ],
            [
                'nama_file.required' => 'Nama file tidak boleh kosong',
                'nama_file.*.required' => 'Nama file tidak boleh kosong',
                'file_dokumen.*.mimes' => "Dokumen Harus Berupa File PDF",
                'file_dokumen.*.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
                'biro_organisasi.required' => 'Biro Organisasi Tidak Boleh Kosong',
                'kategori.required' => 'Kategori Tidak Boleh Kosong',
                'tahun.required' => 'Tahun Tidak Boleh Kosong',
                'program.required' => 'Program Tidak Boleh Kosong',
                'kegiatan.required' => 'Kegiatan Tidak Boleh Kosong',
                'bulan.required' => 'Bulan Tidak Boleh Kosong',
                'anggaran_digunakan.required' => 'Anggaran Digunakan Tidak Boleh Kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $sppLs = new SppLs();
        $sppLs->user_id = Auth::user()->id;
        $sppLs->biro_organisasi_id = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;
        $sppLs->tahun_id = $request->tahun;
        $sppLs->kegiatan_id = $request->kegiatan;
        $sppLs->bulan = $request->bulan;
        $sppLs->kategori = $request->kategori;
        $sppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $sppLs->nomor_surat = $request->nomor_surat;
        $sppLs->save();

        $lengthBerkas = count($request->nama_file);
        for ($i = 0; $i < $lengthBerkas; $i++) {
            $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('file_dokumen')[$i]->storeAs(
                'dokumen_spp_ls',
                $namaFileBerkas
            );

            $dokumenSppLs = new DokumenSppLs();
            $dokumenSppLs->nama_dokumen = $request->nama_file[$i];
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

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SppLs  $sppLs
     * @return \Illuminate\Http\Response
     */
    public function show(SppLs $sppLs)
    {
        $tipe = 'spp_ls';

        $anggaranDigunakan = 'Rp. ' . number_format($sppLs->anggaran_digunakan, 0, ',', '.');

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->biro_organisasi_id == $sppLs->biro_organisasi_id) {
            return view('dashboard.pages.spp.sppLs.show', compact(['sppLs', 'tipe', 'anggaranDigunakan']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SppLs  $sppLs
     * @return \Illuminate\Http\Response
     */
    public function edit(SppLs $sppLs, Request $request)
    {
        $spd = Spd::where('kegiatan_id', $sppLs->kegiatan_id)->where('biro_organisasi_id', $sppLs->biro_organisasi_id)->where('tahun_id', $sppLs->tahun_id)->first();
        $jumlahAnggaranHitung = $spd->jumlah_anggaran;
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaranHitung, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppLs->anggaran_digunakan, 0, ',', '.');
        $role = Auth::user()->role;
        if (($role == "Admin" || Auth::user()->profil->biro_organisasi_id == $sppLs->biro_organisasi_id) && ($sppLs->status_validasi_asn == 2 || $sppLs->status_validasi_ppk == 2)) {
            return view('dashboard.pages.spp.sppLs.edit', compact(['sppLs', 'request', 'jumlahAnggaran', 'anggaranDigunakan', 'jumlahAnggaranHitung']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SppLs  $sppLs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SppLs $sppLs)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_file' => $request->nama_file ? 'required' : 'nullable',
                'nama_file.*' => $request->nama_file ? 'required' : 'nullable',
                'nama_file_update' => 'required',
                'nama_file_update.*' => 'required',
                'file_dokumen.*' => 'mimes:pdf|max:5120',
                'file_dokumen_update.*' => 'mimes:pdf|max:5120',
                'surat_penolakan' => 'required|mimes:pdf|max:5120',
                'anggaran_digunakan' => 'required'
            ],
            [
                'nama_file.required' => 'Nama file tidak boleh kosong',
                'nama_file.*.required' => 'Nama file tidak boleh kosong',
                'nama_file_update.required' => 'Nama file tidak boleh kosong',
                'nama_file_update.*.required' => 'Nama file tidak boleh kosong',
                'file_dokumen.*.mimes' => "Dokumen Harus Berupa File PDF",
                'file_dokumen.*.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
                'file_dokumen_update.*.mimes' => "Dokumen Harus Berupa File PDF",
                'file_dokumen_update.*.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
                'surat_penolakan.required' => 'Surat Penolakan tidak boleh kosong',
                'surat_penolakan.mimes' => 'Dokumen Harus Berupa File PDF',
                'surat_penolakan.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
                'anggaran_digunakan.required' => 'Anggaran Digunakan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }


        $arrayDokumenUpdate = json_decode($request->arrayDokumenUpdate);
        $arrayNamaFileUpdate = json_decode($request->arrayNamaFileUpdate);
        $arrayDokumenHapus = json_decode($request->arrayDokumenHapus);
        // return response()->json($arrayDokumenUpdate);

        if ($arrayDokumenHapus) {
            for ($i = 0; $i < count($arrayDokumenHapus); $i++) {
                $dokumenSppLs = DokumenSppLs::find($arrayDokumenHapus[$i]);
                if (Storage::exists('dokumen_spp_ls/' . $dokumenSppLs->dokumen)) {
                    Storage::delete('dokumen_spp_ls/' . $dokumenSppLs->dokumen);
                }
                $dokumenSppLs->delete();
            }
        }

        if ($arrayNamaFileUpdate) {
            for ($i = 0; $i < count($arrayNamaFileUpdate); $i++) {
                $dokumenSppLs = DokumenSppLs::find($arrayNamaFileUpdate[$i]);
                $dokumenSppLs->nama_dokumen = $request->nama_file_update[$i];
                $dokumenSppLs->save();
            }
        }

        if ($arrayDokumenUpdate) {
            $file_dokumen_update = array_values($request->file('file_dokumen_update'));
            for ($i = 0; $i < count($arrayDokumenUpdate); $i++) {

                $indexNamaFileUpdate = array_search($arrayDokumenUpdate[$i], $arrayNamaFileUpdate);

                $dokumenSppLs = DokumenSppLs::where('id', $arrayDokumenUpdate[$i])->first();
                if (Storage::exists('dokumen_spp_ls/' . $dokumenSppLs->dokumen)) {
                    Storage::delete('dokumen_spp_ls/' . $dokumenSppLs->dokumen);
                }

                $namaFileBerkas = Str::slug($request->nama_file_update[$indexNamaFileUpdate], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $file_dokumen_update[$i]->storeAs(
                    'dokumen_spp_ls',
                    $namaFileBerkas
                );

                $dokumenSppLs->dokumen = $namaFileBerkas;

                $dokumenSppLs->save();
            }
        }

        if ($request->file('file_dokumen')) {
            for ($i = 0; $i < count($request->file('file_dokumen')); $i++) {
                $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen')[$i]->storeAs(
                    'dokumen_spp_ls',
                    $namaFileBerkas
                );
                $dokumenSppLs = new DokumenSppLs();
                $dokumenSppLs->nama_dokumen = $request->nama_file[$i];
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
        $riwayatSppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $riwayatSppLs->status = 'Diperbaiki';
        $riwayatSppLs->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SppLs  $sppLs
     * @return \Illuminate\Http\Response
     */
    public function destroy(SppLs $sppLs)
    {
        $sppLs->delete();

        $riwayatSppLs = RiwayatSppLs::where('spp_ls_id', $sppLs->id)->whereNotNull('surat_penolakan')->get();
        if (count($riwayatSppLs) > 0) {
            foreach ($riwayatSppLs as $riwayat) {
                Storage::delete('surat_penolakan_spp_ls/' . $riwayat->surat_penolakan);
            }
        }

        RiwayatSppLs::where('spp_ls_id', $sppLs->id)->delete();

        $dokumenSppLs = DokumenSppLs::where('spp_ls_id', $sppLs->id)->get();
        foreach ($dokumenSppLs as $dokumen) {
            Storage::delete('dokumen_spp_ls/' . $dokumen->dokumen);
            $dokumen->delete();
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppLs $sppLs)
    {
        $tipeSuratPenolakan = 'spp-ls';
        $tipeSuratPengembalian = 'spp_ls';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->biro_organisasi_id == $sppLs->biro_organisasi_id) {
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

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppLs $sppLs)
    {
        $sppLs->status_validasi_akhir = 1;
        $sppLs->tanggal_validasi_akhir = Carbon::now();
        $sppLs->save();

        $riwayatSppLs = new RiwayatSppLs();
        $riwayatSppLs->spp_ls_id = $sppLs->id;
        $riwayatSppLs->anggaran_digunakan = $sppLs->anggaran_digunakan;
        $riwayatSppLs->user_id = Auth::user()->id;
        $riwayatSppLs->status = 'Diselesaikan';
        $riwayatSppLs->save();

        return response()->json(['status' => 'success']);
    }
}
