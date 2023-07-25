<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DokumenSppGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dokumen_spp_gu')->delete();
        
        \DB::table('dokumen_spp_gu')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-12023072406552582.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'created_at' => '2023-07-24 06:02:55',
                'updated_at' => '2023-07-24 06:02:55',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-12023072406196057.pdf',
                'tahap' => 'SPP',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'created_at' => '2023-07-24 06:06:19',
                'updated_at' => '2023-07-24 06:06:19',
            ),
        ));
        
        
    }
}