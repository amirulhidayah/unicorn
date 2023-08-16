<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('spp_gu')->delete();

        \DB::table('spp_gu')->insert(array(
            0 =>
            array(
                'id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 2,
                'bulan' => 'Januari',
                'perencanaan_anggaran' => 89000000,
                'anggaran_digunakan' => 80000000,
                'nomor_surat' => '101-02-2003-SPP-GU',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'tahap' => 'Selesai',
                'surat_penolakan' => NULL,
                'status_validasi_asn' => 1,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 1,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => '2023-07-24',
                'tanggal_validasi_ppk' => '2023-07-24',
                'status_validasi_akhir' => 1,
                'tanggal_validasi_akhir' => '2023-07-24',
                'dokumen_spm' => '1690178937.pdf',
                'dokumen_arsip_sp2d' => '1690178943.pdf',
                'created_at' => '2023-07-24 06:02:55',
                'updated_at' => '2023-07-24 06:09:03',
            ),
        ));
    }
}
