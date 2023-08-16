<?php

namespace App\Http\Controllers;

use App\Models\DaftarDokumenSppLs;
use App\Models\Kegiatan;
use App\Models\Program;
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

        $program = Program::with(['kegiatan'])->whereHas('kegiatan', function ($query) use ($tahun, $SekretariatDaerah) {
            $query->whereHas('spd', function ($query) use ($tahun, $SekretariatDaerah) {
                if ($tahun) {
                    $query->where('tahun_id', $tahun);
                }
                if ($SekretariatDaerah) {
                    $query->where('sekretariat_daerah_id', $SekretariatDaerah);
                }
            });
        })->orderBy('no_rek', 'asc')->get();

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
}
