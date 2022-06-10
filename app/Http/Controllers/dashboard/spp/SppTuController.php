<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\BiroOrganisasi;
use App\Models\DaftarDokumenSppTu;
use App\Models\DokumenSppTu;
use App\Models\ProgramSpp;
use App\Models\RiwayatSppTu;
use App\Models\SppTu;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($request->ajax()) {
            $data = SppTu::orderBy('created_at', 'desc')->get();
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
                ->addColumn('biro_organisasi', function ($row) {

                    $biroOrganisasi = $row->biroOrganisasi->nama;
                    return $biroOrganisasi;
                })
                ->addColumn('jumlah_anggaran', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah_anggaran, 0, ',', '.');
                })
                ->addColumn('riwayat', function ($row) {
                    $actionBtn = '<a href="' . url('spp-tu/riwayat/' . $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';
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

                    if ($row->user_id == Auth::user()->id && $row->status_validasi_asn == 2) {
                        $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'ASN Sub Bagian Keuangan');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<div class="d-flex justify-content-center"><a href="' . url('/surat-penolakan/spp-tu/' . $riwayatSppTu->id) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-tu/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="asn" name="perbaiki"><button class="btn btn-primary text-light btn-sm mt-1" href="' . url('spp-tu/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form></div>';
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
                        $riwayatSppTu = RiwayatSppTu::where('spp_tu_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'PPK');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<div class="d-flex justify-content-center"><a href="' . url('/surat-penolakan/spp-tu/' . $riwayatSppTu->id) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-tu/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="ppk" name="perbaiki"><button class="btn btn-primary text-light btn-sm mt-1" href="' . url('spp-tu/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form></div>';
                    }
                    return $actionBtn;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';

                    if ($row->status_validasi_akhir == 1) {
                        $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-tu/' . $row->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                    }

                    if (in_array(Auth::user()->role, ["Admin", "Bendahara Pengeluaran"])) {
                        if (($row->status_validasi_akhir == 0 && Auth::user()->role == "Bendahara Pengeluaran") || Auth::user()->role == "Admin") {
                            $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                        }
                    }


                    if (Auth::user()->role == "ASN Sub Bagian Keuangan") {
                        if ((Auth::user()->role == "ASN Sub Bagian Keuangan" && $row->status_validasi_asn == 0)) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-tu/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                        }
                    }

                    if ((Auth::user()->role == "PPK")) {
                        if ($row->status_validasi_ppk == 0) {
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                        } else {
                            if (($row->status_validasi_ppk == 1) && ($row->status_validasi_akhir == 0) && ($row->status_validasi_asn == 1)) {
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
                ->rawColumns(['action', 'riwayat', 'verifikasi_asn', 'verifikasi_ppk', 'biro_organisasi', 'periode', 'program', 'nama', 'jumlah_anggaran', 'status_verifikasi_akhir'])
                ->make(true);
        }
        return view('dashboard.pages.spp.sppTu.index');
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
        $daftarBiroOrganisasi = BiroOrganisasi::orderBy('nama', 'asc')->get();

        return view('dashboard.pages.spp.sppTu.create', compact(['daftarDokumenSppTu', 'daftarTahun', 'daftarProgram', 'daftarBiroOrganisasi']));
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
                'jumlah_anggaran' => 'required',
                'tw' => 'required',
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
                'tw.required' => 'TW Tidak Boleh Kosong',
                'biro_organisasi.required' => 'Biro Organisasi Tidak Boleh Kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $sppTu = SppTu::count();

        if ($sppTu == 0) {
            $nomor_surat = 'SPP-TU/' . date('Y') . '/' . '1';
        } else {
            $nomor_surat = 'SPP-TU/' . date('Y') . '/' . ($sppTu + 1);
        }

        $sppTu = new SppTu();
        $sppTu->biro_organisasi_id = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;
        $sppTu->tahun_id = $request->tahun;
        $sppTu->kegiatan_spp_id = $request->kegiatan;
        $sppTu->user_id = Auth::user()->id;
        $sppTu->nomor_surat = $nomor_surat;
        $sppTu->tw = $request->tw;
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
        $tipe = 'spp_tu';
        $jumlahAnggaran = 'Rp. ' . number_format($sppTu->jumlah_anggaran, 0, ',', '.');
        return view('dashboard.pages.spp.sppTu.show', compact(['sppTu', 'tipe', 'jumlahAnggaran']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SppTu  $sppTu
     * @return \Illuminate\Http\Response
     */
    public function edit(SppTu $sppTu, Request $request)
    {
        return view('dashboard.pages.spp.sppTu.edit', compact(['sppTu', 'request']));
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
            for ($i = 0; $i < count($arrayDokumenUpdate); $i++) {
                $dokumenSppTu = DokumenSppTu::find($arrayDokumenUpdate[$i]);

                if (Storage::exists('dokumen_spp_tu/' . $dokumenSppTu->dokumen)) {
                    Storage::delete('dokumen_spp_tu/' . $dokumenSppTu->dokumen);
                }
                $namaFileBerkas = Str::slug($request->nama_file_update[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen_update')[$i]->storeAs(
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
            if ($request->perbaiki == 'ppk') {
                $sppTu->surat_penolakan_ppk = $namaFileBerkas;
            } else {
                $sppTu->surat_penolakan_asn = $namaFileBerkas;
            }
        }

        if ($request->perbaiki == 'ppk') {
            $sppTu->status_validasi_ppk = 0;
            $sppTu->alasan_validasi_ppk = null;
        } else {
            $sppTu->status_validasi_asn = 0;
            $sppTu->alasan_validasi_asn = null;
        }
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

        RiwayatSppTu::where('spp_tu_id', $sppTu->id)->delete();

        $dokumenSppTu = DokumenSppTu::where('spp_tu_id', $sppTu->id)->get();
        foreach ($dokumenSppTu as $dokumen) {
            Storage::delete('dokumen_spp_tu/' . $dokumen->dokumen);
            $dokumen->delete();
        }

        return response()->json(['status' => 'success']);
    }

    public function riwayat(SppTu $sppTu)
    {
        $tipeSuratPenolakan = 'spp-tu';
        $tipeSuratPengembalian = 'spp_tu';
        return view('dashboard.pages.spp.sppTu.riwayat', compact(['sppTu', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
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
            $sppTu->surat_penolakan_asn = null;
            $sppTu->tanggal_validasi_asn = Carbon::now();
        } else {
            $sppTu->status_validasi_ppk = $request->verifikasi;
            $sppTu->alasan_validasi_ppk = $request->alasan;
            $sppTu->surat_penolakan_ppk = null;
            $sppTu->tanggal_validasi_ppk = Carbon::now();
        }
        $sppTu->save();

        $riwayatSppTu = new RiwayatSppTu();
        $riwayatSppTu->spp_tu_id = $sppTu->id;
        $riwayatSppTu->user_id = Auth::user()->id;
        $riwayatSppTu->jumlah_anggaran = str_replace(".", "", $sppTu->jumlah_anggaran);
        $riwayatSppTu->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
        if ($request->verifikasi == 2) {
            $nomorSurat = RiwayatSppTu::whereNotNull('nomor_surat')->count();
            $riwayatSppTu->nomor_surat = "SPP-TU/P/" . Carbon::now()->format('Y') . "/" . ($nomorSurat + 1);
        }
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
