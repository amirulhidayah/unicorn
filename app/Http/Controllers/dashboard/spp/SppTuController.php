<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\DaftarDokumenSppTu;
use App\Models\DokumenSppTu;
use App\Models\ProgramSpp;
use App\Models\RiwayatSppTu;
use App\Models\SppTu;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SppTuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $SekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah_id : Auth::user()->profil->sekretariat_daerah_id;
        if ($request->ajax()) {
            $data = SppTu::where(function ($query) use ($request, $SekretariatDaerah, $role) {
                if ($SekretariatDaerah && $SekretariatDaerah != 'Semua') {
                    $query->where('sekretariat_daerah_id', $SekretariatDaerah);
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
                    $nama = $row->kegiatanSpp->nama . " (" . $row->kegiatanSpp->no_rek . ")";
                    return $nama;
                })
                ->addColumn('program', function ($row) {
                    $nama = $row->kegiatanSpp->programSpp->nama . " (" . $row->kegiatanSpp->programSpp->no_rek . ")";
                    return $nama;
                })
                ->addColumn('periode', function ($row) {
                    $periode = $row->bulan . ", " . $row->tahun->tahun;
                    return $periode;
                })
                ->addColumn('sekretariat_daerah', function ($row) {

                    $SekretariatDaerah = $row->SekretariatDaerah->nama;
                    return $SekretariatDaerah;
                })
                ->addColumn('jumlah_anggaran', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah_anggaran, 0, ',', '.');
                })
                ->addColumn('verifikasi_asn', function ($row) {
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

                    if ($row->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id  || in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {

                        if (($row->status_validasi_asn != 0 && $row->status_validasi_ppk != 0) && (($row->status_validasi_asn == 2 || $row->status_validasi_ppk == 2))) {
                            $actionBtn .= '<div class="d-flex justify-content-center mb-1"><a href="' . url('/surat-penolakan/spp-tu/' . $row->id . '/' . $row->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                            $actionBtn .= '<a href="' . url('spp-tu/' . $row->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                        }
                    }


                    if ($row->status_validasi_akhir == 1) {
                        $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-tu/' . $row->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                    }

                    if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';

                        if (($row->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $row->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id) || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0 && Auth::user()->is_aktif  == 1)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-tu/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0 && Auth::user()->is_aktif  == 1) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->status_validasi_asn == 1) && (Auth::user()->is_aktif  == 1)) {
                                $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $row->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                            }
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }


                    $actionBtn .= '<a href="' . url('spp-tu/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';

                    return $actionBtn;
                })
                ->addColumn('status_verifikasi_akhir', function ($row) {
                    if ($row->status_validasi_akhir == 0) {
                        return '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else {
                        return '<span class="badge badge-success">Diverifikasi</span>';
                    }
                })
                ->rawColumns(['action', 'riwayat', 'verifikasi_asn', 'verifikasi_ppk', 'sekretariat_daerah', 'periode', 'program', 'nama', 'jumlah_anggaran', 'status_verifikasi_akhir'])
                ->make(true);
        }
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();

        return view('dashboard.pages.spp.sppTu.index', compact('daftarSekretariatDaerah', 'daftarTahun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarDokumenSppTu = DaftarDokumenSppTu::orderBy('created_at', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarProgram = ProgramSpp::orderBy('nama', 'asc')->get();
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.sppTu.create', compact(['daftarDokumenSppTu', 'daftarTahun', 'daftarProgram', 'daftarSekretariatDaerah']));
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
                'sekretariat_daerah' => $role == "Admin" ? 'required' : 'nullable',
                'tahun' => 'required',
                'program' => 'required',
                'kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
                'bulan' => 'required',
                'nomor_surat' => 'required',
            ],
            [
                'nama_file.required' => 'Nama file tidak boleh kosong',
                'nama_file.*.required' => 'Nama file tidak boleh kosong',
                'file_dokumen.*.mimes' => "Dokumen Harus Berupa File PDF",
                'file_dokumen.*.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
                'nama_kegiatan.required' => 'Nama Kegiatan Tidak Boleh Kosong',
                'tahun.required' => 'Tahun Tidak Boleh Kosong',
                'program.required' => 'Program Tidak Boleh Kosong',
                'kegiatan.required' => 'Kegiatan Tidak Boleh Kosong',
                'jumlah_anggaran.required' => 'Jumlah Anggaran Tidak Boleh Kosong',
                'bulan.required' => 'Bulan Tidak Boleh Kosong',
                'sekretariat_daerah.required' => 'Biro Organisasi Tidak Boleh Kosong',
                'nomor_surat.required' => 'Nomor Surat Tidak Boleh Kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $sppTu = new SppTu();
        $sppTu->sekretariat_daerah_id = $role == "Admin" ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $sppTu->tahun_id = $request->tahun;
        $sppTu->kegiatan_spp_id = $request->kegiatan;
        $sppTu->user_id = Auth::user()->id;
        $sppTu->nomor_surat = $request->nomor_surat;
        $sppTu->bulan = $request->bulan;
        $sppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $sppTu->save();

        $lengthBerkas = count($request->nama_file);
        for ($i = 0; $i < $lengthBerkas; $i++) {
            $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('file_dokumen')[$i]->storeAs(
                'dokumen_spp_tu',
                $namaFileBerkas
            );

            $dokumenSppTu = new DokumenSppTu();
            $dokumenSppTu->nama_dokumen = $request->nama_file[$i];
            $dokumenSppTu->dokumen = $namaFileBerkas;
            $dokumenSppTu->spp_tu_id = $sppTu->id;
            $dokumenSppTu->save();
        }

        $riwayatSppTu = new RiwayatSppTu();
        $riwayatSppTu->spp_tu_id = $sppTu->id;
        $riwayatSppTu->user_id = Auth::user()->id;
        $riwayatSppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $riwayatSppTu->status = 'Dibuat';
        $riwayatSppTu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SppTu  $sppTu
     * @return \Illuminate\Http\Response
     */
    public function show(SppTu $sppTu)
    {
        $role = Auth::user()->role;
        $tipe = 'spp_tu';
        $jumlahAnggaran = 'Rp. ' . number_format($sppTu->jumlah_anggaran, 0, ',', '.');
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppTu.show', compact(['sppTu', 'tipe', 'jumlahAnggaran']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        };
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SppTu  $sppTu
     * @return \Illuminate\Http\Response
     */
    public function edit(SppTu $sppTu, Request $request)
    {
        $role = Auth::user()->role;
        if (($role == "Admin" || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) && ($sppTu->status_validasi_asn == 2 || $sppTu->status_validasi_ppk == 2)) {
            return view('dashboard.pages.spp.sppTu.edit', compact(['sppTu', 'request']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SppTu  $sppTu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SppTu $sppTu)
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
                'jumlah_anggaran' => 'required',
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
                'jumlah_anggaran.required' => 'Jumlah Anggaran tidak boleh kosong',
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
                $dokumenSppTu = DokumenSppTu::find($arrayDokumenHapus[$i]);
                if (Storage::exists('dokumen_spp_tu/' . $dokumenSppTu->dokumen)) {
                    Storage::delete('dokumen_spp_tu/' . $dokumenSppTu->dokumen);
                }
                $dokumenSppTu->delete();
            }
        }

        if ($arrayNamaFileUpdate) {
            for ($i = 0; $i < count($arrayNamaFileUpdate); $i++) {
                $dokumenSppTu = DokumenSppTu::find($arrayNamaFileUpdate[$i]);
                $dokumenSppTu->nama_dokumen = $request->nama_file_update[$i];
                $dokumenSppTu->save();
            }
        }

        if ($arrayDokumenUpdate) {
            $file_dokumen_update = array_values($request->file('file_dokumen_update'));
            for ($i = 0; $i < count($arrayDokumenUpdate); $i++) {

                $indexNamaFileUpdate = array_search($arrayDokumenUpdate[$i], $arrayNamaFileUpdate);

                $dokumenSppTu = DokumenSppTu::where('id', $arrayDokumenUpdate[$i])->first();
                if (Storage::exists('dokumen_spp_tu/' . $dokumenSppTu->dokumen)) {
                    Storage::delete('dokumen_spp_tu/' . $dokumenSppTu->dokumen);
                }

                $namaFileBerkas = Str::slug($request->nama_file_update[$indexNamaFileUpdate], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $file_dokumen_update[$i]->storeAs(
                    'dokumen_spp_tu',
                    $namaFileBerkas
                );

                $dokumenSppTu->dokumen = $namaFileBerkas;

                $dokumenSppTu->save();
            }
        }

        if ($request->file('file_dokumen')) {
            for ($i = 0; $i < count($request->file('file_dokumen')); $i++) {
                $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen')[$i]->storeAs(
                    'dokumen_spp_tu',
                    $namaFileBerkas
                );
                $dokumenSppTu = new DokumenSppTu();
                $dokumenSppTu->nama_dokumen = $request->nama_file[$i];
                $dokumenSppTu->dokumen = $namaFileBerkas;
                $dokumenSppTu->spp_tu_id = $sppTu->id;
                $dokumenSppTu->save();
            }
        }

        $riwayatSppTu = new RiwayatSppTu();

        if ($request->file('surat_penolakan')) {
            $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('surat_penolakan')->storeAs(
                'surat_penolakan_spp_tu',
                $namaFileBerkas
            );
            $riwayatSppTu->surat_penolakan = $namaFileBerkas;
            $sppTu->surat_penolakan = $namaFileBerkas;
        }

        if ($sppTu->status_validasi_ppk == 2) {
            $sppTu->status_validasi_ppk = 0;
            $sppTu->alasan_validasi_ppk = null;
        }

        if ($sppTu->status_validasi_asn == 2) {
            $sppTu->status_validasi_asn = 0;
            $sppTu->alasan_validasi_asn = null;
        }
        $sppTu->tahap_riwayat = $sppTu->tahap_riwayat + 1;
        $sppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $sppTu->save();


        $riwayatSppTu->spp_tu_id = $sppTu->id;
        $riwayatSppTu->user_id = Auth::user()->id;
        $riwayatSppTu->status = 'Diperbaiki';
        $riwayatSppTu->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $riwayatSppTu->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SppTu  $sppTu
     * @return \Illuminate\Http\Response
     */
    public function destroy(SppTu $sppTu)
    {
        $sppTu->delete();

        $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $sppTu->id)->whereNotNull('surat_penolakan')->get();
        if (count($riwayatSppTu) > 0) {
            foreach ($riwayatSppTu as $riwayat) {
                Storage::delete('surat_penolakan_spp_tu/' . $riwayat->surat_penolakan);
            }
        }

        RiwayatSppTu::where('spp_tu_id', $sppTu->id)->delete();

        $dokumenSppTu = DokumenSppTu::where('spp_tu_id', $sppTu->id)->get();
        if (count($dokumenSppTu) > 0) {
            foreach ($dokumenSppTu as $dokumen) {
                Storage::delete('dokumen_spp_tu/' . $dokumen->dokumen);
                $dokumen->delete();
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppTu $sppTu)
    {
        $tipeSuratPenolakan = 'spp-tu';
        $tipeSuratPengembalian = 'spp_tu';
        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->sekretariat_daerah_id == $sppTu->sekretariat_daerah_id) {
            return view('dashboard.pages.spp.sppTu.riwayat', compact(['sppTu', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function verifikasi(Request $request, SppTu $sppTu)
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
            $sppTu->status_validasi_asn = $request->verifikasi;
            $sppTu->alasan_validasi_asn = $request->alasan;
            $sppTu->tanggal_validasi_asn = Carbon::now();
        } else {
            $sppTu->status_validasi_ppk = $request->verifikasi;
            $sppTu->alasan_validasi_ppk = $request->alasan;
            $sppTu->tanggal_validasi_ppk = Carbon::now();
        }
        $sppTu->save();

        $riwayatTerakhir = RiwayatSppTu::whereNotNull('nomor_surat')->where('spp_tu_id', $sppTu->id)->where('tahap_riwayat', $sppTu->tahap_riwayat)->first();

        $riwayatSppTu = new RiwayatSppTu();
        $riwayatSppTu->spp_tu_id = $sppTu->id;
        $riwayatSppTu->user_id = Auth::user()->id;
        $riwayatSppTu->tahap_riwayat = $sppTu->tahap_riwayat;
        $riwayatSppTu->jumlah_anggaran = str_replace(".", "", $sppTu->jumlah_anggaran);
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
        $riwayatSppTu->role = Auth::user()->role;
        $riwayatSppTu->alasan = $request->alasan;
        $riwayatSppTu->save();

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppTu $sppTu)
    {
        $sppTu->status_validasi_akhir = 1;
        $sppTu->tanggal_validasi_akhir = Carbon::now();
        $sppTu->save();

        $riwayatSppTu = new RiwayatSppTu();
        $riwayatSppTu->spp_tu_id = $sppTu->id;
        $riwayatSppTu->jumlah_anggaran = $sppTu->jumlah_anggaran;
        $riwayatSppTu->user_id = Auth::user()->id;
        $riwayatSppTu->status = 'Diselesaikan';
        $riwayatSppTu->save();

        return response()->json(['status' => 'success']);
    }
}
