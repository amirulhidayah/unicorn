<?php

namespace App\Imports;

use App\Models\SekretariatDaerah;
use App\Models\Kegiatan;
use App\Models\KegiatanSpp;
use App\Models\Program;
use App\Models\ProgramSpp;
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

        foreach ($rows as $row) {
            $program = Program::where('no_rek', preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek']))->first();
            if (!$program) {
                $program = new Program();
                $program->nama = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['Program']);
                $program->no_rek = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek']);
                $program->save();
            }

            $kegiatan = Kegiatan::where('no_rek', preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek. Keg.SKPD']))->first();
            if (!$kegiatan) {
                $kegiatan = new Kegiatan();
                $kegiatan->program_dpa_id = $program->id;
                $kegiatan->nama = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['Kegiatan']);
                $kegiatan->no_rek = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek. Keg.SKPD']);
                $kegiatan->save();
            }

            $programSpp = ProgramSpp::where('no_rek', preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek']))->first();
            if (!$programSpp) {
                $programSpp = new ProgramSpp();
                $programSpp->nama = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['Program']);
                $programSpp->no_rek = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek']);
                $programSpp->save();
            }

            $kegiatanSpp = KegiatanSpp::where('no_rek', preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek. Keg.SKPD']))->first();
            if (!$kegiatanSpp) {
                $kegiatanSpp = new KegiatanSpp();
                $kegiatanSpp->program_spp_id = $programSpp->id;
                $kegiatanSpp->nama = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['Kegiatan']);
                $kegiatanSpp->no_rek = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek. Keg.SKPD']);
                $kegiatanSpp->save();
            }

            $SekretariatDaerah = SekretariatDaerah::where('nama', $row['Biro Organisasi'])->first();

            // $tw1 = str_replace(['Rp', ','], '', $row['TW 1']);
            // $tw2 = str_replace(['Rp', ','], '', $row['TW 2']);
            // $tw3 = str_replace(['Rp', ','], '', $row['TW 3']);
            // $tw4 = str_replace(['Rp', ','], '', $row['TW 4']);
            $jumlah_anggaran = preg_replace("/[^0-9]/", "", $row['Jumlah Anggaran']);

            if ($SekretariatDaerah) {
                $cekSpd = Spd::where('kegiatan_dpa_id', $kegiatan->id)->where('sekretariat_daerah_id', $SekretariatDaerah->id)->where('tahun_id', $tahun)->first();
                if (!$cekSpd) {
                    $spd = new Spd();
                    $spd->kegiatan_dpa_id = $kegiatan->id;
                    $spd->sekretariat_daerah_id = $SekretariatDaerah->id;
                    $spd->tahun_id = $tahun;
                    $spd->jumlah_anggaran = $jumlah_anggaran;
                    // $spd->tw1 = $tw1 == '-' ? 0 : $tw1;
                    // $spd->tw2 = $tw2 == '-' ? 0 : $tw2;
                    // $spd->tw3 = $tw3 == '-' ? 0 : $tw3;
                    // $spd->tw4 = $tw4 == '-' ? 0 : $tw4;
                    $spd->save();
                }
            }
        }
    }
}
