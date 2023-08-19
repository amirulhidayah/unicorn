<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KegiatanSppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kegiatan_spp_ls')->delete();
        
        \DB::table('kegiatan_spp_ls')->insert(array (
            0 => 
            array (
                'id' => 'e2ed2d77-b074-4c0c-bf7a-fab5ff5396b9',
                'spp_ls_id' => '35df1ca2-c222-40a7-aba7-9cf1f010b966',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 9100000,
                'created_at' => '2023-08-19 07:47:48',
                'updated_at' => '2023-08-19 07:47:48',
            ),
        ));
        
        
    }
}