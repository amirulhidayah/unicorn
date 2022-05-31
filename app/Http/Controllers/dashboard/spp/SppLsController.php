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
                        $actionBtn = '<span class="badge badge-success">Diterima</span>';
                    } else if ($row->status_validasi_asn == 2) {
                        $actionBtn = '<span class="badge badge-danger">Ditolak</span>';
                    }

                    if ($row->user_id == Auth::user()->id && $row->status_validasi_asn == 2) {
                        $riwayat = RiwayatSppLs::where('spp_ls_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'ASN Sub Bagian Keuangan');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<br><a href="' . url('/surat-penolakan/spp-ls/' . $riwayat->id) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-ls/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="asn" name="perbaiki"><button class="btn btn-primary text-light btn-sm mt-1" href="' . url('spp-ls/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form>';
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

                    if ($row->user_id == Auth::user()->id && $row->status_validasi_ppk == 2) {
                        $riwayat = RiwayatSppLs::where('spp_ls_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'PPK');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<br><a href="' . url('/surat-penolakan/spp-ls/' . $riwayat->id) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-ls/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="ppk" name="perbaiki"><button class="btn btn-primary text-light btn-sm mt-1" href="' . url('spp-ls/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form>';
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

                    if (Auth::user()->role == "Bendahara Pembantu") {
                        $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1 mt-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    }


                    if ((Auth::user()->role == "ASN Sub Bagian Keuangan" || Auth::user()->role == "PPK") && ($row->status_validasi_asn == 0 || $row->status_validasi_ppk == 0)) {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                    } else {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 mt-1" href="' . url('spp-ls/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                    }

                    $actionBtn .= '<a href="' . url('spp-ls/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-history"></i> Riwayat</a>';

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
            $nomor_surat = 'SPP-UP/' . date('Y') . '/' . '1';
        } else {
            $nomor_surat = 'SPP-UP/' . date('Y') . '/' . ($sppLs + 1);
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

        $riwayatSppUp = new RiwayatSppLs();
        $riwayatSppUp->spp_ls_id = $sppLs->id;
        $riwayatSppUp->user_id = Auth::user()->id;
        $riwayatSppUp->status = 'Dibuat';
        $riwayatSppUp->save();

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
    public function edit(SppLs $sppLs)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SppLs  $sppLs
     * @return \Illuminate\Http\Response
     */
    public function destroy(SppLs $sppLs)
    {
        //
    }
}
