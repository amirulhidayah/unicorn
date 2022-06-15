<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class TabelDpaExport implements FromView
{
    protected $daftarBiroOrganisasi;
    protected $tahun;

    function __construct($daftarBiroOrganisasi, $tahun)
    {
        $this->daftarBiroOrganisasi = $daftarBiroOrganisasi;
        $this->tahun = $tahun;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $daftarBiroOrganisasi = $this->daftarBiroOrganisasi;
        $tahun = $this->tahun;
        return view('dashboard.pages.dpa.tabel.exportSpd', compact(['daftarBiroOrganisasi', 'tahun']));
    }
}
