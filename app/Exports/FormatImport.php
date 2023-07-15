<?php

namespace App\Exports;

use App\Models\SekretariatDaerah;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class FormatImport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $SekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.dpa.tabel.formatImport', compact(['SekretariatDaerah']));
    }
}
