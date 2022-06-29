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

        $content = $request->isi;
        $dom = new \DomDocument();
        $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imageFile = $dom->getElementsByTagName('img');

        foreach ($imageFile as $item => $image) {
            $data = $image->getAttribute('src');
            $extension = explode('/', mime_content_type($data))[1];
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $imgeData = base64_decode($data);

            $image_name = 'tentang/' . time() . '.' . $extension;
            $path = $image_name;
            Storage::put($path, $imgeData);

            $urlSrc = Storage::url($image_name);

            $image->removeAttribute('src');
            $image->setAttribute('src', $urlSrc);
        }

        $content = $dom->saveHTML();

        $tentang->isi = $content;
        $tentang->save();

        return response()->json(['status' => 'success']);
    }
}
