<?php

namespace App\Imports;

use App\Models\Kegiatan;
use App\Models\SekretariatDaerah;
use App\Models\Program;
use App\Models\Spd;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

        try {
            DB::transaction(function () use ($rows, $tahun) {
                foreach ($rows as $row) {
                    if ($row['No.Rek'] && $row['Program'] && $row['No.Rek. Keg.SKPD'] && $row['Kegiatan'] && $row['Biro Organisasi'] && $row['Jumlah Anggaran']) {
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
                            $kegiatan->program_id = $program->id;
                            $kegiatan->nama = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['Kegiatan']);
                            $kegiatan->no_rek = preg_replace("/[^A-Za-z0-9.,`~!@#$%^&*)(-_+=}{\;:? ]/", " ", $row['No.Rek. Keg.SKPD']);
                            $kegiatan->save();
                        }

                        $SekretariatDaerah = SekretariatDaerah::where('nama', $row['Biro Organisasi'])->first();

                        $jumlah_anggaran = preg_replace("/[^0-9]/", "", $row['Jumlah Anggaran']);

                        if ($SekretariatDaerah) {
                            $cekSpd = Spd::where('kegiatan_id', $kegiatan->id)->where('sekretariat_daerah_id', $SekretariatDaerah->id)->where('tahun_id', $tahun)->first();
                            if (!$cekSpd) {
                                $spd = new Spd();
                                $spd->kegiatan_id = $kegiatan->id;
                                $spd->sekretariat_daerah_id = $SekretariatDaerah->id;
                                $spd->tahun_id = $tahun;
                                $spd->jumlah_anggaran = $jumlah_anggaran;
                                $spd->save();
                            }
                        }
                    }
                }
            });
        } catch (Exception $error) {
            throw new Exception($error);
        }
    }
}
