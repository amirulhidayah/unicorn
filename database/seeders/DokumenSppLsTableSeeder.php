<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DokumenSppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dokumen_spp_ls')->delete();
        
        \DB::table('dokumen_spp_ls')->insert(array (
            0 => 
            array (
                'created_at' => '2023-07-26 06:12:34',
                'dokumen' => 'kwitansi-bermaterai-cukup-12023072606349023.pdf',
                'id' => 1,
                'nama_dokumen' => 'Kwitansi Bermaterai Cukup',
                'spp_ls_id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'updated_at' => '2023-07-26 06:12:34',
            ),
        ));
        
        
    }
}