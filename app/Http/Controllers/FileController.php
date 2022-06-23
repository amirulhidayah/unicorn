<?php

namespace App\Http\Controllers;

use App\Models\DokumenSppGu;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function dokumenSppGu(Request $request)
    {
        $role = Auth::user()->role;
        $dokumen = DokumenSppGu::where('dokumen', $request->dokumen)->first();


        if ($dokumen) {
            if ((in_array($role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) || Auth::user()->profil->biro_organisasi_id == $dokumen->sppGu->biro_organisasi_id) {
                return Storage::download('dokumen_spp_gu/' . $dokumen->dokumen);
            }
        }

        abort(403, 'Anda tidak memiliki akses halaman tersebut!');
    }
}
