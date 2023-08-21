<?php

namespace App\Http\Controllers;

use App\Models\DaftarDokumenSppLs;
use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\SpjGu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function dokumenSppLs(Request $request)
    {
        $kategori = $request->kategori;
        $daftarDokumenSppLs = DaftarDokumenSppLs::where('kategori', $kategori)->orderBy('nama', 'asc')->get();
        return response()->json($daftarDokumenSppLs);
    }

    public function program(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $id = $request->id;
        $SekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;

        $program = Program::with(['kegiatan'])->orderBy('no_rek', 'asc')->get();

        if ($id) {
            $programHapus = Program::where('id', $id)->withTrashed()->first();
            if ($programHapus->trashed()) {
                $program->push($programHapus);
            }
        }

        return response()->json($program);
    }

    public function kegiatan(Request $request)
    {
        $program = $request->program;
        $id = $request->id;

        $kegiatan = Kegiatan::where('program_id', $program)->orderBy('no_rek', 'asc')->get();

        if ($id) {
            $kegiatanHapus = Kegiatan::where('id', $id)->where('program_id', $program)->withTrashed()->first();
            if ($kegiatanHapus->trashed()) {
                $kegiatan->push($kegiatanHapus);
            }
        }
        return response()->json($kegiatan);
    }

    public function kegiatanSpd(Request $request)
    {
        $program = $request->program;
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $id = $request->id;

        $kegiatan = Kegiatan::where('program_id', $program)->whereHas('spd', function ($query) use ($tahun, $sekretariatDaerah) {
            $query->where('tahun_id', $tahun);
            $query->where('sekretariat_daerah_id', $sekretariatDaerah);
        })->orderBy('no_rek', 'asc')->get();

        if ($id) {
            $kegiatanHapus = Kegiatan::where('id', $id)->where('program_id', $program)->withTrashed()->first();
            if ($kegiatanHapus->trashed()) {
                $kegiatan->push($kegiatanHapus);
            }
        }
        return response()->json($kegiatan);
    }

    public function spjGu(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $id = $request->id;

        $spjGu = SpjGu::where('tahun_id', $tahun)->where('sekretariat_daerah_id', $sekretariatDaerah)->where('status_validasi_akhir', 1)->get();

        if ($id) {
            $spjGuHapus = SpjGu::where('id', $id)->where('tahun_id', $tahun)->where('sekretariat_daerah_id')->where('status_validasi_akhir', 1)->withTrashed()->first();
            if ($spjGuHapus->trashed()) {
                $spjGu->push($spjGuHapus);
            }
        }
        return response()->json($spjGu);
    }
}
