<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppendController extends Controller
{
    public function spp()
    {
        $nameFileDokumen = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $classDokumen = 'file_dokumen';
        $classNama = 'nama_file';
        $class = 'col-4';
        $html = view('dashboard.components.dynamicForm.spp', compact(['nameFileDokumen', 'classDokumen', 'classNama', 'class']))->render();
        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function sppLs(Request $request)
    {
        $role = Auth::user()->role;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;
        $id = $request->id;
        $daftarProgram = [];

        try {
            if ($tahun && $sekretariatDaerah && $bulan) {
                $daftarProgram = Program::with(['kegiatan'])->whereHas('kegiatan', function ($query) use ($tahun, $sekretariatDaerah) {
                    $query->whereHas('spd', function ($query) use ($tahun, $sekretariatDaerah) {
                        if ($tahun) {
                            $query->where('tahun_id', $tahun);
                        }
                        if ($sekretariatDaerah) {
                            $query->where('sekretariat_daerah_id', $sekretariatDaerah);
                        }
                    });
                })->orderBy('no_rek', 'asc')->get();
            }

            $dataKey = Str::random(5) . rand(111, 999) . Str::random(5);
            $html = view('dashboard.components.appends.sppLs', compact(['daftarProgram', 'dataKey']))->render();

            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
        } catch (QueryException $error) {
            return throw new Exception($error);
        }
    }
}
