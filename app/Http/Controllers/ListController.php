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
        $biroOrganisasi = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;

        $program = Program::with(['kegiatan'])->whereHas('kegiatan', function ($query) use ($tahun, $biroOrganisasi) {
            $query->whereHas('spd', function ($query) use ($tahun, $biroOrganisasi) {
                $query->where('tahun_id', $tahun);
                $query->where('biro_organisasi_id', $biroOrganisasi);
            });
        })->orderBy('no_rek', 'asc')->get();

        return response()->json($program);
    }

    public function kegiatan(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $program = $request->program;
        $biroOrganisasi = $role == "Admin" ? $request->biro_organisasi : Auth::user()->profil->biro_organisasi_id;

        $kegiatan = Kegiatan::with(['spd'])->whereHas('spd', function ($query) use ($tahun, $biroOrganisasi) {
            $query->where('tahun_id', $tahun);
            $query->where('biro_organisasi_id', $biroOrganisasi);
        })->where('program_id', $program)
            ->orderBy('no_rek', 'asc')->get();

        return response()->json($kegiatan);
    }
}
