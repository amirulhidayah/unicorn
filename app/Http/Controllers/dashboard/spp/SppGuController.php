<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\BiroOrganisasi;
use App\Models\DaftarDokumenSppGu;
use App\Models\DokumenSppGu;
use App\Models\RiwayatSppGu;
use App\Models\Spd;
use App\Models\SppGu;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SppGuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SppGu::orderBy('created_at', 'desc')->get();
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
                    $actionBtn = '<a href="' . url('spp-gu/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';
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
                        $riwayat = RiwayatSppGu::where('spp_gu_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'ASN Sub Bagian Keuangan');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<div class="d-flex justify-content-center "><a href="' . url('/surat-penolakan/spp-gu/' . $riwayat->id) . '" class="btn badge badge-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-gu/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="asn" name="perbaiki"><button class="btn badge badge-primary text-light btn-sm mt-1" href="' . url('spp-gu/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form></div>';
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
                        $riwayat = RiwayatSppGu::where('spp_gu_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'PPK');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<div class="d-flex justify-content-center "><a href="' . url('/surat-penolakan/spp-gu/' . $riwayat->id) . '" class="btn badge badge-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-gu/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="ppk" name="perbaiki"><button class="btn badge badge-primary text-light btn-sm mt-1" href="' . url('spp-gu/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form></div>';
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
                ->addColumn('tahap', function ($row) {
                    if ($row->tahap == "Awal") {
                        return '<span class="badge badge-primary">Awal</span>';
                    } else if ($row->tahap == "Akhir" && $row->status_validasi_akhir == 0) {
                        return '<span class="badge badge-success">Akhir</span>';
                    } else {
                        return '<span class="badge badge-success">Selesai</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';

                    if ($row->status_validasi_akhir == 1) {
                        $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-tu/' . $row->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                    }

                    if (Auth::user()->role == "Bendahara Pengeluaran" && $row->status_validasi_asn == 1 && $row->status_validasi_ppk == 1 && $row->tahap == "Awal") {
                        $actionBtn .= '<a href="' . url('spp-gu/create/' . $row->id) . '" class="btn btn-primary btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-plus-circle"></i> Upload Tahap Akhir</a>';
                    }

                    if (in_array(Auth::user()->role, ["Admin", "Bendahara Pengeluaran"])) {
                        if (($row->status_validasi_akhir == 0 && Auth::user()->role == "Bendahara Pengeluaran") || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-gu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-gu/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-gu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->tahap == "Akhir") && ($row->status_validasi_asn == 1)) {
                                $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $row->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                            }
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-gu/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }


                    $actionBtn .= '<a href="' . url('spp-gu/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';

                    return $actionBtn;
                })
                ->addColumn('status_verifikasi_akhir', function ($row) {
                    if ($row->status_validasi_akhir == 0) {
                        return '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else {
                        return '<span class="badge badge-success">Diverifikasi</span>';
                    }
                })
                ->rawColumns(['action', 'biro_organisasi', 'tanggal_dibuat', 'riwayat', 'periode', 'verifikasi_asn', 'verifikasi_ppk', 'anggaran_digunakan', 'status_verifikasi_akhir', 'nama', 'tahap'])
                ->make(true);
        }
        return view('dashboard.pages.spp.sppGu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarDokumenSppGu = DaftarDokumenSppGu::where('kategori', 'Awal')->get();
        $daftarBiroOrganisasi = BiroOrganisasi::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppGu.create', compact(['daftarTahun', 'daftarDokumenSppGu', 'daftarBiroOrganisasi']));
    }

    public function createTahapAkhir(SppGu $sppGu)
    {
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarDokumenSppGu = DaftarDokumenSppGu::where('kategori', 'Akhir')->get();

        $jumlahAnggaran = Spd::where('kegiatan_id', $sppGu->kegiatan_id)->where('biro_organisasi_id', $sppGu->biro_organisasi_id)->where('tahun_id', $sppGu->tahun_id)->first();
        if ($sppGu->tw == 1) {
            $jumlahAnggaran = $jumlahAnggaran->tw1;
        } else if ($sppGu->tw == 2) {
            $jumlahAnggaran = $jumlahAnggaran->tw2;
        } else if ($sppGu->tw == 3) {
            $jumlahAnggaran = $jumlahAnggaran->tw3;
        } else if ($sppGu->tw == 4) {
            $jumlahAnggaran = $jumlahAnggaran->tw4;
        }
        $jumlahAnggaranHitung = $jumlahAnggaran;
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaran, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.');

        return view('dashboard.pages.spp.sppGu.createTahapAkhir', compact(['daftarTahun', 'daftarDokumenSppGu', 'sppGu', 'jumlahAnggaran', 'jumlahAnggaranHitung', 'anggaranDigunakan']));
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

        $sppGu = SppGu::count();

        if ($sppGu == 0) {
            $nomor_surat = 'SPP-GU/' . date('Y') . '/' . '1';
        } else {
            $nomor_surat = 'SPP-GU/' . date('Y') . '/' . ($sppGu + 1);
        }

        $sppGu = new SppGu();
        $sppGu->user_id = Auth::user()->id;
        $sppGu->biro_organisasi_id = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;
        $sppGu->tahun_id = $request->tahun;
        $sppGu->kegiatan_id = $request->kegiatan;
        $sppGu->tw = $request->tw;
        $sppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $sppGu->nomor_surat = $nomor_surat;
        $sppGu->save();

        $lengthBerkas = count($request->nama_file);
        for ($i = 0; $i < $lengthBerkas; $i++) {
            $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('file_dokumen')[$i]->storeAs(
                'dokumen_spp_gu',
                $namaFileBerkas
            );

            $dokumenSppGu = new DokumenSppGu();
            $dokumenSppGu->nama_dokumen = $request->nama_file[$i];
            $dokumenSppGu->dokumen = $namaFileBerkas;
            $dokumenSppGu->spp_gu_id = $sppGu->id;
            $dokumenSppGu->tahap = "Awal";
            $dokumenSppGu->save();
        }

        $riwayatSppGu = new RiwayatSppGu();
        $riwayatSppGu->spp_gu_id = $sppGu->id;
        $riwayatSppGu->user_id = Auth::user()->id;
        $riwayatSppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $riwayatSppGu->status = 'Dibuat Tahap Awal';
        $riwayatSppGu->save();

        return response()->json(['status' => 'success']);
    }

    public function storeTahapAkhir(SppGu $sppGu, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_file' => 'required',
                'nama_file.*' => 'required',
                'file_dokumen.*' => 'mimes:pdf|max:5120',
                'anggaran_digunakan' => 'required',
            ],
            [
                'nama_file.required' => 'Nama file tidak boleh kosong',
                'nama_file.*.required' => 'Nama file tidak boleh kosong',
                'file_dokumen.*.mimes' => "Dokumen Harus Berupa File PDF",
                'file_dokumen.*.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
                'anggaran_digunakan.required' => 'Anggaran Digunakan Tidak Boleh Kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $sppGu->tahap = "Akhir";
        $sppGu->status_validasi_asn = 0;
        $sppGu->status_validasi_ppk = 0;
        $sppGu->save();

        $lengthBerkas = count($request->nama_file);
        for ($i = 0; $i < $lengthBerkas; $i++) {
            $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('file_dokumen')[$i]->storeAs(
                'dokumen_spp_gu',
                $namaFileBerkas
            );

            $dokumenSppGu = new DokumenSppGu();
            $dokumenSppGu->nama_dokumen = $request->nama_file[$i];
            $dokumenSppGu->dokumen = $namaFileBerkas;
            $dokumenSppGu->spp_gu_id = $sppGu->id;
            $dokumenSppGu->tahap = "Akhir";
            $dokumenSppGu->save();
        }

        $riwayatSppGu = new RiwayatSppGu();
        $riwayatSppGu->spp_gu_id = $sppGu->id;
        $riwayatSppGu->user_id = Auth::user()->id;
        $riwayatSppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $riwayatSppGu->status = 'Dibuat Tahap Akhir';
        $riwayatSppGu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SppGu  $sppGu
     * @return \Illuminate\Http\Response
     */
    public function show(SppGu $sppGu)
    {
        $tipe = 'spp_gu';
        $jumlahAnggaran = Spd::where('kegiatan_id', $sppGu->kegiatan_id)->where('biro_organisasi_id', $sppGu->biro_organisasi_id)->where('tahun_id', $sppGu->tahun_id)->first();
        if ($sppGu->tw == 1) {
            $jumlahAnggaran = $jumlahAnggaran->tw1;
        } else if ($sppGu->tw == 2) {
            $jumlahAnggaran = $jumlahAnggaran->tw2;
        } else if ($sppGu->tw == 3) {
            $jumlahAnggaran = $jumlahAnggaran->tw3;
        } else if ($sppGu->tw == 4) {
            $jumlahAnggaran = $jumlahAnggaran->tw4;
        }
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaran, 0, ',', '.');

        $anggaranDigunakan = 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.');

        if ($sppGu->tahap == "Awal") {
            $tahap = '<span class="badge badge-primary">Awal</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'Awal')->get();
        } else if ($sppGu->tahap == "Akhir" && $sppGu->status_validasi_akhir == 0) {
            $tahap = '<span class="badge badge-success">Akhir</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'Akhir')->get();
        } else {
            $tahap = '<span class="badge badge-success">Selesai</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->get();
        }

        return view('dashboard.pages.spp.sppGu.show', compact(['sppGu', 'tipe', 'jumlahAnggaran', 'anggaranDigunakan', 'tahap', 'daftarDokumenSppGu']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SppGu  $sppGu
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SppGu $sppGu)
    {
        $jumlahAnggaran = Spd::where('kegiatan_id', $sppGu->kegiatan_id)->where('biro_organisasi_id', $sppGu->biro_organisasi_id)->where('tahun_id', $sppGu->tahun_id)->first();
        if ($sppGu->tw == 1) {
            $jumlahAnggaran = $jumlahAnggaran->tw1;
        } else if ($sppGu->tw == 2) {
            $jumlahAnggaran = $jumlahAnggaran->tw2;
        } else if ($sppGu->tw == 3) {
            $jumlahAnggaran = $jumlahAnggaran->tw3;
        } else if ($sppGu->tw == 4) {
            $jumlahAnggaran = $jumlahAnggaran->tw4;
        }
        $jumlahAnggaranHitung = $jumlahAnggaran;
        $jumlahAnggaran = 'Rp. ' . number_format($jumlahAnggaran, 0, ',', '.');
        $anggaranDigunakan = 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.');

        if ($sppGu->tahap == "Awal") {
            $tahap = '<span class="badge badge-primary">Awal</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'Awal')->get();
        } else if ($sppGu->tahap == "Akhir") {
            $tahap = '<span class="badge badge-success">Akhir</span>';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->where('tahap', 'Akhir')->get();
        } else {
            $tahap = '';
            $daftarDokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->get();
        }

        return view('dashboard.pages.spp.sppGu.edit', compact(['sppGu', 'request', 'jumlahAnggaran', 'anggaranDigunakan', 'jumlahAnggaranHitung', 'daftarDokumenSppGu', 'tahap']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SppGu  $sppGu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SppGu $sppGu)
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
                $dokumenSppGu = DokumenSppGu::find($arrayDokumenHapus[$i]);
                if (Storage::exists('dokumen_spp_gu/' . $dokumenSppGu->dokumen)) {
                    Storage::delete('dokumen_spp_gu/' . $dokumenSppGu->dokumen);
                }
                $dokumenSppGu->delete();
            }
        }

        if ($arrayNamaFileUpdate) {
            for ($i = 0; $i < count($arrayNamaFileUpdate); $i++) {
                $dokumenSppGu = DokumenSppGu::find($arrayNamaFileUpdate[$i]);
                $dokumenSppGu->nama_dokumen = $request->nama_file_update[$i];
                $dokumenSppGu->save();
            }
        }

        if ($arrayDokumenUpdate) {
            for ($i = 0; $i < count($arrayDokumenUpdate); $i++) {
                $dokumenSppGu = DokumenSppGu::find($arrayDokumenUpdate[$i]);

                if (Storage::exists('dokumen_spp_gu/' . $dokumenSppGu->dokumen)) {
                    Storage::delete('dokumen_spp_gu/' . $dokumenSppGu->dokumen);
                }
                $namaFileBerkas = Str::slug($request->nama_file_update[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen_update')[$i]->storeAs(
                    'dokumen_spp_gu',
                    $namaFileBerkas
                );
                $dokumenSppGu->dokumen = $namaFileBerkas;
                $dokumenSppGu->save();
            }
        }

        if ($request->file('file_dokumen')) {
            for ($i = 0; $i < count($request->file('file_dokumen')); $i++) {
                $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen')[$i]->storeAs(
                    'dokumen_spp_gu',
                    $namaFileBerkas
                );
                $dokumenSppGu = new DokumenSppGu();
                $dokumenSppGu->nama_dokumen = $request->nama_file[$i];
                $dokumenSppGu->dokumen = $namaFileBerkas;
                $dokumenSppGu->tahap = $sppGu->tahap;
                $dokumenSppGu->spp_gu_id = $sppGu->id;
                $dokumenSppGu->save();
            }
        }

        $riwayatSppGu = new RiwayatSppGu();

        if ($request->file('surat_penolakan')) {
            $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('surat_penolakan')->storeAs(
                'surat_penolakan_spp_gu',
                $namaFileBerkas
            );
            $riwayatSppGu->surat_penolakan = $namaFileBerkas;
            if ($request->perbaiki == 'ppk') {
                $sppGu->surat_penolakan_ppk = $namaFileBerkas;
            } else {
                $sppGu->surat_penolakan_asn = $namaFileBerkas;
            }
        }

        if ($request->perbaiki == 'ppk') {
            $sppGu->status_validasi_ppk = 0;
            $sppGu->alasan_validasi_ppk = null;
        } else {
            $sppGu->status_validasi_asn = 0;
            $sppGu->alasan_validasi_asn = null;
        }
        $sppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $sppGu->save();


        $riwayatSppGu->spp_gu_id = $sppGu->id;
        $riwayatSppGu->user_id = Auth::user()->id;
        $riwayatSppGu->anggaran_digunakan = str_replace(".", "", $request->anggaran_digunakan);
        $riwayatSppGu->status = 'Diperbaiki';
        $riwayatSppGu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SppGu  $sppGu
     * @return \Illuminate\Http\Response
     */
    public function destroy(SppGu $sppGu)
    {
        $sppGu->delete();

        RiwayatSppGu::where('spp_gu_id', $sppGu->id)->delete();

        $dokumenSppGu = DokumenSppGu::where('spp_gu_id', $sppGu->id)->get();
        foreach ($dokumenSppGu as $dokumen) {
            Storage::delete('dokumen_spp_gu/' . $dokumen->dokumen);
            $dokumen->delete();
        }

        return response()->json(['status' => 'success']);
    }

    public function verifikasi(Request $request, SppGu $sppGu)
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
            $sppGu->status_validasi_asn = $request->verifikasi;
            $sppGu->alasan_validasi_asn = $request->alasan;
            $sppGu->surat_penolakan_asn = null;
            $sppGu->tanggal_validasi_asn = Carbon::now();
        } else {
            $sppGu->status_validasi_ppk = $request->verifikasi;
            $sppGu->alasan_validasi_ppk = $request->alasan;
            $sppGu->surat_penolakan_ppk = null;
            $sppGu->tanggal_validasi_ppk = Carbon::now();
        }
        $sppGu->save();

        $riwayatSppGu = new RiwayatSppGu();
        $riwayatSppGu->spp_gu_id = $sppGu->id;
        $riwayatSppGu->anggaran_digunakan = $sppGu->anggaran_digunakan;
        $riwayatSppGu->user_id = Auth::user()->id;
        $riwayatSppGu->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
        if ($request->verifikasi == 2) {
            $nomorSurat = RiwayatSppGu::whereNotNull('nomor_surat')->count();
            $riwayatSppGu->nomor_surat = "SPP-GU/P/" . Carbon::now()->format('Y') . "/" . ($nomorSurat + 1);
        }
        $riwayatSppGu->alasan = $request->alasan;
        $riwayatSppGu->save();

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppGu $sppGu)
    {
        $sppGu->status_validasi_akhir = 1;
        $sppGu->tanggal_validasi_akhir = Carbon::now();
        $sppGu->save();

        $riwayatSppLs = new RiwayatSppGu();
        $riwayatSppLs->spp_gu_id = $sppGu->id;
        $riwayatSppLs->anggaran_digunakan = $sppGu->anggaran_digunakan;
        $riwayatSppLs->user_id = Auth::user()->id;
        $riwayatSppLs->status = 'Diselesaikan';
        $riwayatSppLs->save();

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppGu $sppGu)
    {
        $tipeSuratPenolakan = 'spp-gu';
        $tipeSuratPengembalian = 'spp_gu';
        return view('dashboard.pages.spp.sppGu.riwayat', compact(['sppGu', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
    }
}
