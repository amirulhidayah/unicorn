<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppUpTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('spp_up')->delete();

        \DB::table('spp_up')->insert(array(
            0 =>
            array(
                'id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'kegiatan_spp_id' => 'f408e460-42a6-429c-92c6-73b3f8529939',
                'jumlah_anggaran' => 17250000,
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'nomor_surat' => '101/SPP-UP/06/2022',
                'tahap_riwayat' => 2,
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'surat_penolakan' => 'Surat Penolakan-2022062111513519.pdf',
                'status_validasi_asn' => 1,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 1,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => '2022-06-21',
                'tanggal_validasi_ppk' => '2022-06-21',
                'status_validasi_akhir' => 1,
                'tanggal_validasi_akhir' => '2022-06-21',
                'created_at' => '2022-06-21 11:43:58',
                'updated_at' => '2022-06-21 12:01:03',
            ),
        ));
    }
}
