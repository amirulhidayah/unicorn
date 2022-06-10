<?php

namespace App\Http\Controllers;

use App\Models\DaftarDokumenSppLs;
use App\Models\Kegiatan;
use App\Models\KegiatanSpp;
use App\Models\Program;
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

    public function program(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $tipe = $request->tipe;
        $id = $request->id;
        $biroOrganisasi = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;

        $program = Program::with(['kegiatan'])->whereHas('kegiatan', function ($query) use ($tahun, $biroOrganisasi) {
            $query->whereHas('spd', function ($query) use ($tahun, $biroOrganisasi) {
                if ($tahun) {
                    $query->where('tahun_id', $tahun);
                }
                if ($biroOrganisasi) {
                    $query->where('biro_organisasi_id', $biroOrganisasi);
                }
            });
        })->orderBy('no_rek', 'asc')->get();

        if ($tipe == 'ubah') {
            $programHapus = Program::where('id', $id)->withTrashed()->first();
            if ($programHapus) {
                $program->push($programHapus);
            }
        }

        return response()->json($program);
    }

    public function kegiatan(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $program = $request->program;
        $tipe = $request->tipe;
        $id = $request->id;
        $biroOrganisasi = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;

        $kegiatan = Kegiatan::with(['spd'])->whereHas('spd', function ($query) use ($tahun, $biroOrganisasi) {
            if ($tahun) {
                $query->where('tahun_id', $tahun);
            }
            if ($biroOrganisasi) {
                $query->where('biro_organisasi_id', $biroOrganisasi);
            }
        })->where('program_id', $program)
            ->orderBy('no_rek', 'asc')->get();

        if ($tipe == 'ubah') {
            $kegiatanHapus = Kegiatan::where('id', $id)->where('program_id', $program)->withTrashed()->first();
            if ($kegiatanHapus) {
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
