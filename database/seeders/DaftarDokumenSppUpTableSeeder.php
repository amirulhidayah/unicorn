<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DaftarDokumenSppUpTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('daftar_dokumen_spp_up')->delete();
        
        \DB::table('daftar_dokumen_spp_up')->insert(array (
            0 => 
            array (
                'id' => '34e0309e-385f-46e6-ad1c-457c9f910718',
                'nama' => 'Draf Surat Pernyataan',
                'deleted_at' => NULL,
                'created_at' => '2022-05-04 05:53:38',
                'updated_at' => '2022-05-04 05:53:38',
            ),
            1 => 
            array (
                'id' => '487b5735-90d7-4c88-b510-bd43047fdae9',
                'nama' => 'Surat Permohonan Besaran UP',
                'deleted_at' => NULL,
                'created_at' => '2022-05-04 05:53:31',
                'updated_at' => '2022-05-04 05:53:31',
            ),
            2 => 
            array (
                'id' => '4e49bcb0-d330-4afe-8b9d-4ed368533bcd',
                'nama' => 'Salinan SPD',
                'deleted_at' => NULL,
                'created_at' => '2022-05-04 05:53:21',
                'updated_at' => '2022-05-04 05:53:21',
            ),
            3 => 
            array (
                'id' => '609f9fdf-33a8-451c-ad50-d7d481a6db1c',
                'nama' => 'Ringkasan',
                'deleted_at' => NULL,
                'created_at' => '2022-05-04 05:53:13',
                'updated_at' => '2022-05-04 05:53:13',
            ),
            4 => 
            array (
                'id' => 'f163804d-f582-4e98-97a3-9fd54d582e82',
                'nama' => 'Surat Pengantar',
                'deleted_at' => NULL,
                'created_at' => '2022-05-04 03:03:42',
                'updated_at' => '2022-05-04 03:06:36',
            ),
        ));
        
        
    }
}