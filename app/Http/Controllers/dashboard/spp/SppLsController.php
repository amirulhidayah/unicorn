<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\DokumenSppLs;
use App\Models\RiwayatSppLs;
use App\Models\Spd;
use App\Models\SppLs;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($request->ajax()) {
            $data = SppLs::orderBy('created_at', 'desc')->get();
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
                    $periode = "TW " . $row->tw . " (" . $row->tahun->tahun . ")";
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
                ->addColumn('verifikasi_asn', function ($row) {
                    if ($row->status_validasi_asn == 0) {
                        $actionBtn = '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else if ($row->status_validasi_asn == 1) {
                        $actionBtn = '<span class="badge badge-success">Diverifikasi</span>';
                    } else if ($row->status_validasi_asn == 2) {
                        $actionBtn = '<span class="badge badge-danger">Ditolak</span>';
                    }

                    if ($row->user_id == Auth::user()->id && $row->status_validasi_asn == 2) {
                        $riwayat = RiwayatSppLs::where('spp_ls_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'ASN Sub Bagian Keuangan');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<br><a href="' . url('/surat-penolakan/spp-ls/' . $riwayat->id) . '" class="btn badge badge-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-ls/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="asn" name="perbaiki"><button class="btn badge badge-primary text-light btn-sm mt-1" href="' . url('spp-ls/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form>';
                    }
                    return $actionBtn;
                })
                ->addColumn('verifikasi_ppk', function ($row) {
                    if ($row->status_validasi_ppk == 0) {
                        $actionBtn = '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else if ($row->status_validasi_ppk == 1) {
                        $actionBtn = '<span class="badge badge-success">Diverifikasi</span>';
                    } else if ($row->status_validasi_ppk == 2) {
                        $actionBtn = '<span class="badge badge-danger">Ditolak</span>';
                    }

                    if ($row->user_id == Auth::user()->id && $row->status_validasi_ppk == 2) {
                        $riwayat = RiwayatSppLs::where('spp_ls_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'PPK');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<br><a href="' . url('/surat-penolakan/spp-ls/' . $riwayat->id) . '" class="btn badge badge-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-ls/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="ppk" name="perbaiki"><button class="btn badge badge-primary text-light btn-sm mt-1" href="' . url('spp-ls/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form>';
                    }
                    return $actionBtn;
                })
                ->addColumn('jumlah_anggaran', function ($row) {
                    $jumlah_anggaran = Spd::where('kegiatan_id', $row->kegiatan_id)->where('biro_organisasi_id', $row->biro_organisasi_id)->where('tahun_id', $row->tahun_id)->first();
                    if ($row->tw == 1) {
                        $jumlah_anggaran = $jumlah_anggaran->tw1;
                    } else if ($row->tw == 2) {
                        $jumlah_anggaran = $jumlah_anggaran->tw2;
                    } else if ($row->tw == 3) {
                        $jumlah_anggaran = $jumlah_anggaran->tw3;
                    } else if ($row->tw == 4) {
                        $jumlah_anggaran = $jumlah_anggaran->tw4;
                    }
                    return 'Rp. ' . number_format($jumlah_anggaran, 0, ',', '.');
                })
                ->addColumn('anggaran_digunakan', function ($row) {
                    return 'Rp. ' . number_format($row->anggaran_digunakan, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';

                    if (Auth::user()->role == "Bendahara Pengeluaran") {
                        $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
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
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0)) {
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
        return view('dashboard.pages.spp.sppLs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        return view('dashboard.pages.spp.sppLs.create', compact(['daftarTahun']));
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
                'tw' => 'required',
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
                'tw.required' => 'TW Tidak Boleh Kosong',
                'anggaran_digunakan.required' => 'Anggaran Digunakan Tidak Boleh Kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $sppLs = SppLs::count();

        if ($sppLs == 0) {
            $nomor_surat = 'SPP-LS/' . date('Y') . '/' . '1';
        } else {
            $nomor_surat = 'SPP-LS/' . date('Y') . '/' . ($sppLs + 1);
        }

        $sppLs = new SppLs();
        $sppLs->user_id = Auth::user()->id;
        $sppLs->biro_organisasi_id = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;
        $sppLs->tahun_id = $request->tahun;
        $sppLs->kegiatan_id = $request->kegiatan;
        $sppLs->tw = $request->tw;
        $sppLs->kategori = $request->kategori;
        $sppLs->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $sppLs->nomor_surat = $nomor_surat;
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
        $jumlahAnggaran = Spd::where('kegiatan_id', $sppLs->kegiatan_id)->where('biro_organisasi_id', $sppLs->biro_organisasi_id)->where('tahun_id', $sppLs->tahun_id)->first();
        if ($sppLs->tw == 1) {
            $jumlahAnggaran = $jumlahAnggaran->tw1;
        } else if ($sppLs->tw == 2) {
            $jumlahAnggaran = $jumlahAnggaran->tw2;
        } else if ($sppLs->tw == 3) {
            $jumlahAnggaran = $jumlahAnggaran->tw3;
        } else if ($sppLs->tw == 4) {
            $jumlahAnggaran = $jumlahAnggaran->tw4;
        }
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaran, 0, ',', '.');

        $anggaranDigunakan = 'Rp. ' . number_format($sppLs->anggaran_digunakan, 0, ',', '.');
        return view('dashboard.pages.spp.sppLs.show', compact(['sppLs', 'tipe', 'jumlahAnggaran', 'anggaranDigunakan']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SppLs  $sppLs
     * @return \Illuminate\Http\Response
     */
    public function edit(SppLs $sppLs, Request $request)
    {
        $jumlahAnggaran = Spd::where('kegiatan_id', $sppLs->kegiatan_id)->where('biro_organisasi_id', $sppLs->biro_organisasi_id)->where('tahun_id', $sppLs->tahun_id)->first();
        if ($sppLs->tw == 1) {
            $jumlahAnggaran = $jumlahAnggaran->tw1;
        } else if ($sppLs->tw == 2) {
            $jumlahAnggaran = $jumlahAnggaran->tw2;
        } else if ($sppLs->tw == 3) {
            $jumlahAnggaran = $jumlahAnggaran->tw3;
        } else if ($sppLs->tw == 4) {
            $jumlahAnggaran = $jumlahAnggaran->tw4;
        }
        $jumlahAnggaranHitung = $jumlahAnggaran;
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaran, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppLs->anggaran_digunakan, 0, ',', '.');
        return view('dashboard.pages.spp.sppLs.edit', compact(['sppLs', 'request', 'jumlahAnggaran', 'anggaranDigunakan', 'jumlahAnggaranHitung']));
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
            for ($i = 0; $i < count($arrayDokumenUpdate); $i++) {
                $dokumenSppLs = DokumenSppLs::find($arrayDokumenUpdate[$i]);

                if (Storage::exists('dokumen_spp_ls/' . $dokumenSppLs->dokumen)) {
                    Storage::delete('dokumen_spp_ls/' . $dokumenSppLs->dokumen);
                }
                $namaFileBerkas = Str::slug($request->nama_file_update[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen_update')[$i]->storeAs(
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
            if ($request->perbaiki == 'ppk') {
                $sppLs->surat_penolakan_ppk = $namaFileBerkas;
            } else {
                $sppLs->surat_penolakan_asn = $namaFileBerkas;
            }
        }

        if ($request->perbaiki == 'ppk') {
            $sppLs->status_validasi_ppk = 0;
            $sppLs->alasan_validasi_ppk = null;
        } else {
            $sppLs->status_validasi_asn = 0;
            $sppLs->alasan_validasi_asn = null;
        }
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
        return view('dashboard.pages.spp.sppLs.riwayat', compact(['sppLs', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
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
            $sppLs->surat_penolakan_asn = null;
            $sppLs->tanggal_validasi_asn = Carbon::now();
        } else {
            $sppLs->status_validasi_ppk = $request->verifikasi;
            $sppLs->alasan_validasi_ppk = $request->alasan;
            $sppLs->surat_penolakan_ppk = null;
            $sppLs->tanggal_validasi_ppk = Carbon::now();
        }
        $sppLs->save();

        $riwayatSppLs = new RiwayatSppLs();
        $riwayatSppLs->spp_ls_id = $sppLs->id;
        $riwayatSppLs->anggaran_digunakan = $sppLs->anggaran_digunakan;
        $riwayatSppLs->user_id = Auth::user()->id;
        $riwayatSppLs->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
        if ($request->verifikasi == 2) {
            $nomorSurat = RiwayatSppLs::whereNotNull('nomor_surat')->count();
            $riwayatSppLs->nomor_surat = "SPP-LS/P/" . Carbon::now()->format('Y') . "/" . ($nomorSurat + 1);
        }
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
