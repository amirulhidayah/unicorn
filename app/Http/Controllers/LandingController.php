<?php

namespace App\Http\Controllers;

use App\Models\Tentang;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landingPage.pages.landingPage');
    }

    public function tentang()
    {
        $tentang = Tentang::first()->isi;
        return view('landingPage.pages.tentang', compact(['tentang']));
    }
}
