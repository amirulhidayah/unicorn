<?php

namespace App\Http\Controllers\dashboard\spp;

use App\Http\Controllers\Controller;
use App\Models\DaftarDokumenSppUp;
use App\Models\DokumenSppUp;
use App\Models\RiwayatSppUp;
use App\Models\SppUp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($request->ajax()) {
            $data = SppUp::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
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

                    if ($row->user_id == Auth::user()->id && $row->status_validasi_asn == 2) {
                        $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'ASN Sub Bagian Keuangan');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<br><a href="' . url('/surat-penolakan/spp-up/' . $riwayatSppUp->id) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-up/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="asn" name="perbaiki"><button class="btn btn-primary text-light btn-sm mt-1" href="' . url('spp-up/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form>';
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
                        $riwayatSppUp = RiwayatSppUp::where('spp_up_id', $row->id)->whereHas('user', function ($query) {
                            $query->where('role', 'PPK');
                        })->orderBy('created_at', 'desc')->first();

                        $actionBtn .= '<br><a href="' . url('/surat-penolakan/spp-up/' . $riwayatSppUp->id) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';

                        $actionBtn .= '<form action="' . url('spp-up/' . $row->id . '/edit') . '" method="POST">' . csrf_field() . '<input type="hidden" value="ppk" name="perbaiki"><button class="btn btn-primary text-light btn-sm mt-1" href="' . url('spp-up/' . $row->id . '/edit') . '"><i class="fas fa-file-pdf"></i> Perbaiki</button></form>';
                    }
                    return $actionBtn;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';

                    if (Auth::user()->role == "Bendahara Pembantu") {
                        $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1 mt-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    }


                    if ((Auth::user()->role == "ASN Sub Bagian Keuangan" || Auth::user()->role == "PPK") && ($row->status_validasi_asn == 0 || $row->status_validasi_ppk == 0)) {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-up/' . $row->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                    } else {
                        $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 mt-1" href="' . url('spp-up/' . $row->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                    }

                    return $actionBtn;
                })
                ->rawColumns(['action', 'riwayat', 'verifikasi_asn', 'verifikasi_ppk'])
                ->make(true);
        }
        return view('dashboard.pages.spp.sppUp.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarDokumenSppUp = DaftarDokumenSppUp::orderBy('created_at', 'asc')->get();
        return view('dashboard.pages.spp.sppUp.create', compact(['daftarDokumenSppUp']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_file' => 'required',
                'nama_file.*' => 'required',
                'file_dokumen.*' => 'mimes:pdf|max:5120',
                'nama_kegiatan' => 'required',
            ],
            [
                'nama_file.required' => 'Nama file tidak boleh kosong',
                'nama_file.*.required' => 'Nama file tidak boleh kosong',
                'file_dokumen.*.mimes' => "Dokumen Harus Berupa File PDF",
                'file_dokumen.*.max' => "Dokumen Tidak Boleh Lebih Dari 5 Mb",
                'nama_kegiatan.required' => 'Nama Kegiatan Tidak Boleh Kosong'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $sppUp = SppUp::count();

        if ($sppUp == 0) {
            $nomor_surat = 'SPP-UP/' . date('Y') . '/' . '1';
        } else {
            $nomor_surat = 'SPP-UP/' . date('Y') . '/' . ($sppUp + 1);
        }

        $sppUp = new SppUp();
        $sppUp->nama = $request->nama_kegiatan;
        $sppUp->user_id = Auth::user()->id;
        $sppUp->nomor_surat = $nomor_surat;
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
        return view('dashboard.pages.spp.sppUp.show', compact(['sppUp', 'tipe']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SppUp  $sppUp
     * @return \Illuminate\Http\Response
     */
    public function edit(SppUp $sppUp, Request $request)
    {
        return view('dashboard.pages.spp.sppUp.edit', compact(['sppUp', 'request']));
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
                'nama_kegiatan' => 'required',
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
                'nama_kegiatan.required' => 'Nama Kegiatan Tidak Boleh Kosong'
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
                $dokumenSppUp = DokumenSppUp::find($arrayDokumenHapus[$i]);
                if (Storage::exists('dokumen_spp_up/' . $dokumenSppUp->dokumen)) {
                    Storage::delete('dokumen_spp_up/' . $dokumenSppUp->dokumen);
                }
                $dokumenSppUp->delete();
            }
        }

        if ($arrayNamaFileUpdate) {
            for ($i = 0; $i < count($arrayNamaFileUpdate); $i++) {
                $dokumenSppUp = DokumenSppUp::find($arrayNamaFileUpdate[$i]);
                $dokumenSppUp->nama_dokumen = $request->nama_file_update[$i];
                $dokumenSppUp->save();
            }
        }

        if ($arrayDokumenUpdate) {
            for ($i = 0; $i < count($arrayDokumenUpdate); $i++) {
                $dokumenSppUp = DokumenSppUp::find($arrayDokumenUpdate[$i]);

                if (Storage::exists('dokumen_spp_up/' . $dokumenSppUp->dokumen)) {
                    Storage::delete('dokumen_spp_up/' . $dokumenSppUp->dokumen);
                }
                $namaFileBerkas = Str::slug($request->nama_file_update[$i], '-') . "-" . $i . Carbon::now()->format('YmdHs') . rand(1, 9999) . ".pdf";
                $request->file('file_dokumen_update')[$i]->storeAs(
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
            if ($request->perbaiki == 'ppk') {
                $sppUp->surat_penolakan_ppk = $namaFileBerkas;
            } else {
                $sppUp->surat_penolakan_asn = $namaFileBerkas;
            }
        }

        if ($request->perbaiki == 'ppk') {
            $sppUp->status_validasi_ppk = 0;
            $sppUp->alasan_validasi_ppk = null;
        } else {
            $sppUp->status_validasi_asn = 0;
            $sppUp->alasan_validasi_asn = null;
        }
        $sppUp->nama = $request->nama_kegiatan;
        $sppUp->save();


        $riwayatSppUp->spp_up_id = $sppUp->id;
        $riwayatSppUp->user_id = Auth::user()->id;
        $riwayatSppUp->status = 'Diperbaiki';
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
        //
    }

    public function riwayat(SppUp $sppUp)
    {
        $tipeSuratPenolakan = 'spp-up';
        $tipeSuratPengembalian = 'spp_up';
        return view('dashboard.pages.spp.sppUp.riwayat', compact(['sppUp', 'tipeSuratPenolakan', 'tipeSuratPengembalian']));
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
            $sppUp->surat_penolakan_asn = null;
            $sppUp->tanggal_validasi_asn = Carbon::now();
        } else {
            $sppUp->status_validasi_ppk = $request->verifikasi;
            $sppUp->alasan_validasi_ppk = $request->alasan;
            $sppUp->surat_penolakan_ppk = null;
            $sppUp->tanggal_validasi_ppk = Carbon::now();
        }
        $sppUp->save();

        $riwayatSppUp = new RiwayatSppUp();
        $riwayatSppUp->spp_up_id = $sppUp->id;
        $riwayatSppUp->user_id = Auth::user()->id;
        $riwayatSppUp->status = $request->verifikasi == '1' ? 'Disetujui' : 'Ditolak';
        if ($request->verifikasi == 2) {
            $nomorSurat = RiwayatSppUp::whereNotNull('nomor_surat')->count();
            $riwayatSppUp->nomor_surat = "SPP-UP/P/" . Carbon::now()->format('Y') . "/" . ($nomorSurat + 1);
        }
        $riwayatSppUp->alasan = $request->alasan;
        $riwayatSppUp->save();

        return response()->json(['status' => 'success']);
    }
}
