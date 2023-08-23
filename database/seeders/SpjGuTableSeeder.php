<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SpjGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('spj_gu')->delete();
        
        \DB::table('spj_gu')->insert(array (
            0 => 
            array (
                'alasan_validasi_asn' => NULL,
                'alasan_validasi_ppk' => NULL,
                'bulan' => 'Januari',
                'created_at' => '2023-08-23 01:46:00',
                'id' => '039155d1-37cc-4d24-8b19-5bc6fad7e9a4',
                'nomor_surat' => 'SPJ 2 2022',
                'sekretariat_daerah_id' => '68d47270-c3fd-44a0-9033-0d5c4259a756',
                'status_validasi_akhir' => 1,
                'status_validasi_asn' => 1,
                'status_validasi_ppk' => 1,
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'tanggal_validasi_akhir' => '2023-08-23',
                'tanggal_validasi_asn' => '2023-08-23',
                'tanggal_validasi_ppk' => '2023-08-23',
                'updated_at' => '2023-08-23 01:46:42',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
            1 => 
            array (
                'alasan_validasi_asn' => NULL,
                'alasan_validasi_ppk' => NULL,
                'bulan' => 'Januari',
                'created_at' => '2023-08-23 01:45:15',
                'id' => 'a30b869c-2300-4c98-9bc3-95c5dcccbb77',
                'nomor_surat' => 'SPJ 1 2022',
                'sekretariat_daerah_id' => '68d47270-c3fd-44a0-9033-0d5c4259a756',
                'status_validasi_akhir' => 1,
                'status_validasi_asn' => 1,
                'status_validasi_ppk' => 1,
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'tanggal_validasi_akhir' => '2023-08-23',
                'tanggal_validasi_asn' => '2023-08-23',
                'tanggal_validasi_ppk' => '2023-08-23',
                'updated_at' => '2023-08-23 01:46:50',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
        ));
        
        
    }
}