<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KegiatanSpjGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kegiatan_spj_gu')->delete();
        
        \DB::table('kegiatan_spj_gu')->insert(array (
            0 => 
            array (
                'anggaran_digunakan' => 300000000,
                'created_at' => '2023-08-23 01:45:15',
                'dokumen' => '16927551154329.pdf',
                'id' => '1af02d5b-b218-435e-9ec5-4a60f1ecc448',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'spj_gu_id' => 'a30b869c-2300-4c98-9bc3-95c5dcccbb77',
                'updated_at' => '2023-08-23 01:45:15',
            ),
            1 => 
            array (
                'anggaran_digunakan' => 170000000,
                'created_at' => '2023-08-23 01:46:00',
                'dokumen' => '16927551601034.pdf',
                'id' => '6460740c-e9c9-4535-8034-6edb620d5de6',
                'kegiatan_id' => '675131ca-9136-42de-bf0b-54eb5ebca75a',
                'spj_gu_id' => '039155d1-37cc-4d24-8b19-5bc6fad7e9a4',
                'updated_at' => '2023-08-23 01:46:00',
            ),
            2 => 
            array (
                'anggaran_digunakan' => 20000000,
                'created_at' => '2023-08-23 01:46:00',
                'dokumen' => '16927551606467.pdf',
                'id' => 'c98fc6a1-8575-4dca-80bd-e9333ba62916',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'spj_gu_id' => '039155d1-37cc-4d24-8b19-5bc6fad7e9a4',
                'updated_at' => '2023-08-23 01:46:00',
            ),
            3 => 
            array (
                'anggaran_digunakan' => 200000,
                'created_at' => '2023-08-23 01:45:15',
                'dokumen' => '16927551153918.pdf',
                'id' => 'e51ed6d7-31ea-44f6-81ca-a411a5b95214',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'spj_gu_id' => 'a30b869c-2300-4c98-9bc3-95c5dcccbb77',
                'updated_at' => '2023-08-23 01:45:15',
            ),
        ));
        
        
    }
}