<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('spp_ls')->delete();
        
        \DB::table('spp_ls')->insert(array (
            0 => 
            array (
                'id' => '35df1ca2-c222-40a7-aba7-9cf1f010b966',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'kategori_spp_ls_id' => '518dc47f-32fb-4304-960f-942780d2f092',
                'bulan' => 'Januari',
                'nomor_surat' => '01-Januari-2021',
                'user_id' => 'bd7db183-61f5-40b0-b1f2-75de801596ea',
                'surat_penolakan' => NULL,
                'status_validasi_asn' => 0,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 0,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => NULL,
                'tanggal_validasi_ppk' => NULL,
                'status_validasi_akhir' => 0,
                'tanggal_validasi_akhir' => NULL,
                'dokumen_spm' => NULL,
                'dokumen_arsip_sp2d' => NULL,
                'created_at' => '2023-08-19 07:47:48',
                'updated_at' => '2023-08-19 07:47:48',
            ),
        ));
        
        
    }
}