<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TentangController extends Controller
{
    public function index()
    {
        $tentang = Tentang::first();
        return view('dashboard.pages.masterData.tentang.index', compact(['tentang']));
    }

    public function update(Tentang $tentang, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'isi' => 'required',
            ],
            [
                'isi.required' => 'Tentang Tidak Boleh Kosong'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $tentang->isi = $request->isi;
        $tentang->save();

        return response()->json(['status' => 'success']);
    }
}
