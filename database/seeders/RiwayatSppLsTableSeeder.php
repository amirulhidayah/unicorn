<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RiwayatSppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('riwayat_spp_ls')->delete();
        
        \DB::table('riwayat_spp_ls')->insert(array (
            0 => 
            array (
                'id' => 'c1a82177-3e3e-48d5-b21f-dff75d5591e2',
                'spp_ls_id' => '35df1ca2-c222-40a7-aba7-9cf1f010b966',
                'user_id' => 'bd7db183-61f5-40b0-b1f2-75de801596ea',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-19 07:47:48',
                'updated_at' => '2023-08-19 07:47:48',
            ),
        ));
        
        
    }
}