<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TabelDpaExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $jenisSpp;
    protected $array;

    function __construct($jenisSpp, $array)
    {
        $this->jenisSpp = $jenisSpp;
        $this->array = $array;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $array = $this->array;
        $export = true;
        if ($this->jenisSpp == "SPP-GU") {
            return view('dashboard.components.widgets.tabelSpdDebug', compact(['array', 'export']));
        } else {
            return view('dashboard.components.widgets.tabelSpdSppLsDebug', compact(['array', 'export']));
        }
    }

    public function styles(Worksheet $sheet)
    {
        $allBordersStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Black color
                ],
            ],
        ];

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
            ->applyFromArray($allBordersStyle);
    }
}
