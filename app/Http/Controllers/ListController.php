<?php

namespace App\Http\Controllers;

use App\Models\DaftarDokumenSppLs;
use App\Models\Kegiatan;
use App\Models\KegiatanDpa;
use App\Models\KegiatanSpp;
use App\Models\Program;
use App\Models\ProgramDpa;
use App\Models\ProgramSpp;
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

    public function programDpa(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $tipe = $request->tipe;
        $id = $request->id;
        $SekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;

        $program = ProgramDpa::with(['kegiatanDpa'])->whereHas('kegiatanDpa', function ($query) use ($tahun, $SekretariatDaerah) {
            $query->whereHas('spd', function ($query) use ($tahun, $SekretariatDaerah) {
                if ($tahun) {
                    $query->where('tahun_id', $tahun);
                }
                if ($SekretariatDaerah) {
                    $query->where('sekretariat_daerah_id', $SekretariatDaerah);
                }
            });
        })->orderBy('no_rek', 'asc')->get();

        if ($tipe == 'ubah') {
            $programHapus = ProgramDpa::where('id', $id)->withTrashed()->first();
            if ($programHapus->trashed()) {
                $program->push($programHapus);
            }
        }

        return response()->json($program);
    }

    public function kegiatanDpa(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $program = $request->program;
        $tipe = $request->tipe;
        $id = $request->id;
        $SekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;

        $kegiatan = KegiatanDpa::with(['spd'])->whereHas('spd', function ($query) use ($tahun, $SekretariatDaerah) {
            if ($tahun) {
                $query->where('tahun_id', $tahun);
            }
            if ($SekretariatDaerah) {
                $query->where('sekretariat_daerah_id', $SekretariatDaerah);
            }
        })->where('program_dpa_id', $program)
            ->orderBy('no_rek', 'asc')->get();

        if ($tipe == 'ubah') {
            $kegiatanHapus = KegiatanDpa::where('id', $id)->where('program_dpa_id', $program)->withTrashed()->first();
            if ($kegiatanHapus->trashed()) {
                $kegiatan->push($kegiatanHapus);
            }
        }
        return response()->json($kegiatan);
    }

    public function programSpp()
    {
        $program = ProgramSpp::orderBy('no_rek', 'asc')->get();

        return response()->json($program);
    }

    public function kegiatanSpp(Request $request)
    {
        $program = $request->program;
        $kegiatan = KegiatanSpp::where('program_spp_id', $program)->orderBy('no_rek', 'asc')->get();

        return response()->json($kegiatan);
    }
}
