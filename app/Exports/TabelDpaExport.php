<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class TabelDpaExport implements FromView
{
    protected $daftarSekretariatDaerah;
    protected $tahun;

    function __construct($daftarSekretariatDaerah, $tahun)
    {
        $this->daftarSekretariatDaerah = $daftarSekretariatDaerah;
        $this->tahun = $tahun;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $daftarSekretariatDaerah = $this->daftarSekretariatDaerah;
        $tahun = $this->tahun;
        return view('dashboard.pages.dpa.tabel.exportSpd', compact(['daftarSekretariatDaerah', 'tahun']));
    }
}
