<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DaftarDokumenSppTuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('daftar_dokumen_spp_tu')->delete();
        
        \DB::table('daftar_dokumen_spp_tu')->insert(array (
            0 => 
            array (
                'id' => '3e754940-80a3-412a-89cc-70c24a00f740',
                'nama' => 'Surat Pengantar',
                'deleted_at' => NULL,
                'created_at' => '2022-05-15 11:23:50',
                'updated_at' => '2022-05-15 11:23:50',
            ),
            1 => 
            array (
                'id' => '6d8cb387-d085-447f-94ef-66fc09f47735',
                'nama' => 'Surat Keterangan',
                'deleted_at' => NULL,
                'created_at' => '2022-05-15 11:25:02',
                'updated_at' => '2022-05-15 13:02:18',
            ),
            2 => 
            array (
                'id' => '85c44b16-d34d-43d5-9bd0-6f067932e46c',
                'nama' => 'Salinan SPD',
                'deleted_at' => NULL,
                'created_at' => '2022-05-15 11:24:08',
                'updated_at' => '2022-05-15 11:24:08',
            ),
            3 => 
            array (
                'id' => 'd3b7c212-961a-4cc5-84c3-77e032c7d711',
                'nama' => 'Surat Pengesahan',
                'deleted_at' => NULL,
                'created_at' => '2022-05-15 11:24:16',
                'updated_at' => '2022-05-15 11:24:16',
            ),
            4 => 
            array (
                'id' => 'dcf69ad3-a765-40b3-8379-4beca7053d0d',
                'nama' => 'Ringkasan',
                'deleted_at' => NULL,
                'created_at' => '2022-05-15 11:23:59',
                'updated_at' => '2022-05-15 11:23:59',
            ),
            5 => 
            array (
                'id' => 'e1631507-d467-4630-a93b-49dc81baa57c',
                'nama' => 'Draf Surat Pernyataan',
                'deleted_at' => NULL,
                'created_at' => '2022-05-15 11:24:33',
                'updated_at' => '2022-05-15 11:24:33',
            ),
        ));
        
        
    }
}