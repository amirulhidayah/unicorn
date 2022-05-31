<?php

namespace App\Exports;

use App\Models\BiroOrganisasi;
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
        $biroOrganisasi = BiroOrganisasi::orderBy('nama', 'asc')->get();
        return view('dashboard.pages.spd.formatImport', compact(['biroOrganisasi']));
    }
}
