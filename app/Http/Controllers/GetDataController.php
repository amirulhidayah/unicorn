<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Spd;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetDataController extends Controller
{
    public function spd(Request $request)
    {
        $role = Auth::user()->role;
        $kegiatan = $request->kegiatan;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $sekretariatDaerah = in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? $request->sekretariat_daerah : Auth::user()->profil->sekretariat_daerah_id;

        try {
            $jumlahAnggaran = jumlah_anggaran($sekretariatDaerah, $kegiatan, $bulan, $tahun);
            return response()->json([
                'status' => 'success',
                'jumlah_anggaran' => $jumlahAnggaran
            ]);
        } catch (QueryException $error) {
            return throw new Exception($error);
        }
    }
}
