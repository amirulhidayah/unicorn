<?php

namespace App\Imports;

use App\Models\BiroOrganisasi;
use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\Spd;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ImportSpd implements ToCollection, WithHeadingRow
{
    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $tahun = $this->tahun;
        // Cek No. Rek Program, kalau belum ada tambahkan ke program, kalau sudah ambil datanya
        // Cek Kegiatan, kalau belum ada tambahkan ke kegiatan berdasarkan program, kalau belum ambil datanya
        // Tambahkan ke SPD, sebelum itu str_replace tw1-4 untuk menghilangkan ("Rp",",")
        foreach ($rows as $row) {
            $program = Program::where('no_rek', $row['No.Rek'])->first();
            if (!$program) {
                $program = new Program();
                $program->nama = $row['Program'];
                $program->no_rek = $row['No.Rek'];
                $program->save();
            }

            $kegiatan = Kegiatan::where('no_rek', $row['No.Rek. Keg.SKPD'])->first();
            if (!$kegiatan) {
                $kegiatan = new Kegiatan();
                $kegiatan->program_id = $program->id;
                $kegiatan->nama = $row['Kegiatan'];
                $kegiatan->no_rek = $row['No.Rek. Keg.SKPD'];
                $kegiatan->save();
            }

            $biroOrganisasi = BiroOrganisasi::where('nama', $row['Biro Organisasi'])->first();

            $tw1 = str_replace(['Rp', ','], '', $row['TW 1']);
            $tw2 = str_replace(['Rp', ','], '', $row['TW 2']);
            $tw3 = str_replace(['Rp', ','], '', $row['TW 3']);
            $tw4 = str_replace(['Rp', ','], '', $row['TW 4']);

            $spd = new Spd();
            $spd->kegiatan_id = $kegiatan->id;
            $spd->biro_organisasi_id = $biroOrganisasi->id;
            $spd->tahun_id = $tahun;
            $spd->tw1 = $tw1 == '-' ? 0 : $tw1;
            $spd->tw2 = $tw2 == '-' ? 0 : $tw2;
            $spd->tw3 = $tw3 == '-' ? 0 : $tw3;
            $spd->tw4 = $tw4 == '-' ? 0 : $tw4;
            $spd->save();
        }
    }
}
