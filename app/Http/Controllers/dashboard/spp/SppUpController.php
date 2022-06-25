<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\BiroOrganisasi;
use App\Models\DaftarDokumenSppUp;
use App\Models\DokumenSppUp;
use App\Models\ProgramSpp;
use App\Models\RiwayatSppUp;
use App\Models\SppUp;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SppUpController extends Controller
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
            $data = SppUp::where(function ($query) use ($request, $biroOrganisasi, $role) {
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

                    $periode = $row->tahun->tahun;
                    return $periode;
                })
                ->addColumn('biro_organisasi', function ($row) {

                    $biroOrganisasi = $row->biroOrganisasi->nama;
                    return $biroOrganisasi;
                })
                ->addColumn('jumlah_anggaran', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah_anggaran, 0, ',', '.');
                })
                ->addColumn('riwayat', function ($row) {
                    $actionBtn = '<a href="' . url('spp-up/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';
                    return $actionBtn;
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

                    if ($row->biro_organisasi_id == Auth::user()->profil->biro_organisasi_id  || in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {

                        if (($row->status_validasi_asn != 0 && $row->status_validasi_ppk != 0) && (($row->status_validasi_asn == 2 || $row->status_validasi_ppk == 2))) {
                            $actionBtn .= '<div class="d-flex justify-content-center mb-1"><a href="' . url('/surat-penolakan/spp-up/' . $row->id . '/' . $row->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                            $actionBtn .= '<a href="' . url('spp-up/' . $row->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                        }
                    }


                    if ($row->status_validasi_akhir == 1) {
                        $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-up/' . $row->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                    }

                    if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';

                        if (($row->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $row->biro_organisasi_id == Auth::user()->profil->biro_organisasi_id) || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0 && Auth::user()->is_aktif  == 1)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-up/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0 && Auth::user()->is_aktif  == 1) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->status_validasi_asn == 1) && (Auth::user()->is_aktif  == 1)) {
                                $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $row->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                            }
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }


                    $actionBtn .= '<a href="' . url('spp-up/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';

                    return $actionBtn;
                })
                ->addColumn('status_verifikasi_akhir', function ($row) {
                    if ($row->status_validasi_akhir == 0) {
                        return '<span class="badge badge-primary text-light">Belum Di Proses</span>';
                    } else {
                        return '<span class="badge badge-success">Diverifikasi</span>';
                    }
                })
                ->rawColumns(['action', 'riwayat', 'verifikasi_asn', 'verifikasi_ppk', 'biro_organisasi', 'periode', 'program', 'nama', 'jumlah_anggaran', 'status_verifikasi_akhir'])
                ->make(true);
        }

        $daftarBiroOrganisasi = BiroOrganisasi::orderBy('nama', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();

        return view('dashboard.pages.spp.sppUp.index', compact('daftarBiroOrganisasi', 'daftarTahun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarDokumenSppUp = DaftarDokumenSppUp::orderBy('created_at', 'asc')->get();
        $daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $daftarProgram = ProgramSpp::orderBy('nama', 'asc')->get();
        $daftarBiroOrganisasi = BiroOrganisasi::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spp.sppUp.create', compact(['daftarDokumenSppUp', 'daftarTahun', 'daftarProgram', 'daftarBiroOrganisasi']));
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
                'tahun' => 'required',
                'biro_organisasi' => $role == "Admin" ? 'required' : 'nullable',
                'program' => 'required',
                'kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
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
                'biro_organisasi.required' => 'Biro Organisasi Tidak Boleh Kosong',
                'kegiatan.required' => 'Kegiatan Tidak Boleh Kosong',
                'jumlah_anggaran.required' => 'Jumlah Anggaran Tidak Boleh Kosong',
                'nomor_surat.required' => 'Nomor Surat Tidak Boleh Kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $sppUp = new SppUp();
        $sppUp->biro_organisasi_id = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;
        $sppUp->tahun_id = $request->tahun;
        $sppUp->kegiatan_spp_id = $request->kegiatan;
        $sppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $sppUp->user_id = Auth::user()->id;
        $sppUp->nomor_surat = $request->nomor_surat;
        $sppUp->save();

        $lengthBerkas = count($request->nama_file);
        for ($i = 0; $i < $lengthBerkas; $i++) {
            $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('file_dokumen')[$i]->storeAs(
                'dokumen_spp_up',
                $namaFileBerkas
            );

            $dokumenSppUp = new DokumenSppUp();
            $dokumenSppUp->nama_dokumen = $request->nama_file[$i];
            $dokumenSppUp->dokumen = $namaFileBerkas;
            $dokumenSppUp->spp_up_id = $sppUp->id;
            $dokumenSppUp->save();
        }

        $riwayatSppUp = new RiwayatSppUp();
        $riwayatSppUp->spp_up_id = $sppUp->id;
        $riwayatSppUp->user_id = Auth::user()->id;
        $riwayatSppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $riwayatSppUp->status = 'Dibuat';
        $riwayatSppUp->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SppUp  $sppUp
     * @return \Illuminate\Http\Response
     */
    public function show(SppUp $sppUp)
    {
        $tipe = 'spp_up';
        $jumlahAnggaran = 'Rp. ' . number_format($sppUp->jumlah_anggaran, 0, ',', '.');

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->biro_organisasi_id == $sppUp->biro_organisasi_id) {
            return view('dashboard.pages.spp.sppUp.show', compact(['sppUp', 'tipe', 'jumlahAnggaran']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SppUp  $sppUp
     * @return \Illuminate\Http\Response
     */
    public function edit(SppUp $sppUp, Request $request)
    {
        $role = Auth::user()->role;
        if (($role == "Admin" || Auth::user()->profil->biro_organisasi_id == $sppUp->biro_organisasi_id) && ($sppUp->status_validasi_asn == 2 || $sppUp->status_validasi_ppk == 2)) {
            return view('dashboard.pages.spp.sppUp.edit', compact(['sppUp', 'request']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SppUp  $sppUp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SppUp $sppUp)
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
                $dokumenSppUp = DokumenSppUp::where('id', $arrayDokumenHapus[$i])->first();
                if (Storage::exists('dokumen_spp_up/' . $dokumenSppUp->dokumen)) {
                    Storage::delete('dokumen_spp_up/' . $dokumenSppUp->dokumen);
                }
                $dokumenSppUp->delete();
            }
        }

        if ($arrayNamaFileUpdate) {
            for ($i = 0; $i < count($arrayNamaFileUpdate); $i++) {
                $dokumenSppUp = DokumenSppUp::where('id', $arrayNamaFileUpdate[$i])->first();
                $dokumenSppUp->nama_dokumen = $request->nama_file_update[$i];
                $dokumenSppUp->save();
            }
        }

        if ($arrayDokumenUpdate) {
            $file_dokumen_update = array_values($request->file('file_dokumen_update'));
            for ($i = 0; $i < count($arrayDokumenUpdate); $i++) {

                $indexNamaFileUpdate = array_search($arrayDokumenUpdate[$i], $arrayNamaFileUpdate);

                $dokumenSppUp = DokumenSppUp::where('id', $arrayDokumenUpdate[$i])->first();
                if (Storage::exists('dokumen_spp_up/' . $dokumenSppUp->dokumen)) {
                    Storage::delete('dokumen_spp_up/' . $dokumenSppUp->dokumen);
                }

                $namaFileBerkas = Str::slug($request->nama_file_update[$indexNamaFileUpdate], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $file_dokumen_update[$i]->storeAs(
                    'dokumen_spp_up',
                    $namaFileBerkas
                );

                $dokumenSppUp->dokumen = $namaFileBerkas;

                $dokumenSppUp->save();
            }
        }

        if ($request->file('file_dokumen')) {
            for ($i = 0; $i < count($request->file('file_dokumen')); $i++) {
                $namaFileBerkas = Str::slug($request->nama_file[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen')[$i]->storeAs(
                    'dokumen_spp_up',
                    $namaFileBerkas
                );
                $dokumenSppUp = new DokumenSppUp();
                $dokumenSppUp->nama_dokumen = $request->nama_file[$i];
                $dokumenSppUp->dokumen = $namaFileBerkas;
                $dokumenSppUp->spp_up_id = $sppUp->id;
                $dokumenSppUp->save();
            }
        }

        $riwayatSppUp = new RiwayatSppUp();

        if ($request->file('surat_penolakan')) {
            $namaFileBerkas = "Surat Penolakan" . "-"  . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
            $request->file('surat_penolakan')->storeAs(
                'surat_penolakan_spp_up',
                $namaFileBerkas
            );
            $riwayatSppUp->surat_penolakan = $namaFileBerkas;
            $sppUp->surat_penolakan = $namaFileBerkas;
        }

        if ($sppUp->status_validasi_ppk == 2) {
            $sppUp->status_validasi_ppk = 0;
            $sppUp->alasan_validasi_ppk = null;
        }

        if ($sppUp->status_validasi_asn == 2) {
            $sppUp->status_validasi_asn = 0;
            $sppUp->alasan_validasi_asn = null;
        }
        $sppUp->tahap_riwayat = $sppUp->tahap_riwayat + 1;
        $sppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $sppUp->save();


        $riwayatSppUp->spp_up_id = $sppUp->id;
        $riwayatSppUp->user_id = Auth::user()->id;
        $riwayatSppUp->status = 'Diperbaiki';
        $riwayatSppUp->jumlah_anggaran = str_replace(".", "", $request->jumlah_anggaran);
        $riwayatSppUp->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SppUp  $sppUp
     * @return \Illuminate\Http\Response
     */
    public function destroy(SppUp $sppUp)
    {
        $sppUp->delete();

        $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $sppUp->id)->whereNotNull('surat_penolakan')->get();
        if (count($riwayatSppUp) > 0) {
            foreach ($riwayatSppUp as $riwayat) {
                Storage::delete('surat_penolakan_spp_up/' . $riwayat->surat_penolakan);
            }
        }

        RiwayatSppUp::where('spp_up_id', $sppUp->id)->delete();

        $dokumenSppUp = DokumenSppUp::where('spp_up_id', $sppUp->id)->get();
        if (count($dokumenSppUp) > 0) {
            foreach ($dokumenSppUp as $dokumen) {
                Storage::delete('dokumen_spp_up/' . $dokumen->dokumen);
                $dokumen->delete();
            }
        }


        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppUp $sppUp)
    {
        $tipeSuratPenolakan = 'spp-up';
        $tipeSuratPengembalian = 'spp_up';

        $role = Auth::user()->role;
        if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->biro_organisasi_id == $sppUp->biro_organisasi_id) {
            return view('dashboard.pages.spp.sppUp.riwayat', compact(['sppUp', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
        } else {
            abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        }
    }

    public function verifikasi(Request $request, SppUp $sppUp)
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
            $sppUp->status_validasi_asn = $request->verifikasi;
            $sppUp->alasan_validasi_asn = $request->alasan;
            $sppUp->tanggal_validasi_asn = Carbon::now();
        } else {
            $sppUp->status_validasi_ppk = $request->verifikasi;
            $sppUp->alasan_validasi_ppk = $request->alasan;
            $sppUp->tanggal_validasi_ppk = Carbon::now();
        }
        $sppUp->save();

        $riwayatTerakhir = RiwayatSppUp::whereNotNull('nomor_surat')->where('spp_up_id', $sppUp->id)->where('tahap_riwayat', $sppUp->tahap_riwayat)->first();

        $riwayatSppUp = new RiwayatSppUp();
        $riwayatSppUp->spp_up_id = $sppUp->id;
        $riwayatSppUp->user_id = Auth::user()->id;
        $riwayatSppUp->tahap_riwayat = $sppUp->tahap_riwayat;
        $riwayatSppUp->jumlah_anggaran = str_replace(".", "", $sppUp->jumlah_anggaran);
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

        return response()->json(['status' => 'success']);
    }

    public function verifikasiAkhir(SppUp $sppUp)
    {
        $sppUp->status_validasi_akhir = 1;
        $sppUp->tanggal_validasi_akhir = Carbon::now();
        $sppUp->save();

        $riwayatSppLs = new RiwayatSppUp();
        $riwayatSppLs->spp_up_id = $sppUp->id;
        $riwayatSppLs->jumlah_anggaran = $sppUp->jumlah_anggaran;
        $riwayatSppLs->user_id = Auth::user()->id;
        $riwayatSppLs->status = 'Diselesaikan';
        $riwayatSppLs->save();

        return response()->json(['status' => 'success']);
    }
}
