<?php

namespace App\Http\Controllers;

use App\Models\DaftarDokumenSppLs;
use Illuminate\Http\Request;

class AppendController extends Controller
{
    public function spp()
    {
        $nameFileDokumen = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $classDokumen = 'file_dokumen';
        $classNama = 'nama_file';
        $html = view('dashboard.components.dynamicForm.spp', compact(['nameFileDokumen', 'classDokumen', 'classNama']))->render();
        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function sppLs(Request $request)
    {
        $daftarDokumenSppLs = DaftarDokumenSppLs::where('kategori', $request->kategori)->get();

        if (!$daftarDokumenSppLs) {
            return response()->json([
                'status' => 'error',
            ]);
        }

        $nameFileDokumen = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $classDokumen = 'file_dokumen';
        $classNama = 'nama_file';
        $html = null;

        foreach ($daftarDokumenSppLs as $dokumen) {
            $labelNama = $dokumen->nama;
            $html .= view('dashboard.components.dynamicForm.spp', compact(['nameFileDokumen', 'classDokumen', 'classNama', 'labelNama']))->render();
        }

        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }
}
