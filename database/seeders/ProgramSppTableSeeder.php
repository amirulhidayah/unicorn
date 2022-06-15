<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProgramSppTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('program_spp')->delete();
        
        \DB::table('program_spp')->insert(array (
            0 => 
            array (
                'id' => '2ce27d47-68a7-43b1-b680-835d2a779d65',
                'nama' => 'PROGRAM KEBIJAKAN ADMINISTRASI PEMBANGUNAN',
                'no_rek' => '4.01.08',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            1 => 
            array (
                'id' => '49c49c80-d4c2-4c8b-a7af-f013006293a2',
                'nama' => 'PROGRAM KEBIJAKAN DAN PELAYANAN PENGADAAN BARANG DAN JASA',
                'no_rek' => '4.01.07',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            2 => 
            array (
                'id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI',
                'no_rek' => '4.01.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            3 => 
            array (
                'id' => '6d7c67a2-ff15-491e-b536-07ae6d15af00',
                'nama' => 'PROGRAM KESEJAHTERAAN RAKYAT',
                'no_rek' => '4.01.04',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            4 => 
            array (
                'id' => '73f2b481-1a56-445b-8bf5-6dfd86d6d590',
                'nama' => 'PROGRAM FASILITASI DAN KOORDINASI HUKUM',
                'no_rek' => '4.01.06',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            5 => 
            array (
                'id' => 'b2c4d604-3367-4af7-8cae-7b180b454334',
                'nama' => 'PROGRAM PEMERINTAHAN DAN OTONOMI DAERAH',
                'no_rek' => '4.01.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            6 => 
            array (
                'id' => 'bbb82ca8-a204-47e4-957f-9bb5860bb902',
                'nama' => 'PROGRAM FASILITASI DAN KOORDINASI HUKUM',
                'no_rek' => '4.01.05',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            7 => 
            array (
                'id' => 'f958b108-21cc-4806-9aca-b6bcd6aae21c',
                'nama' => 'PROGRAM PENATAAN ORGANISASI',
                'no_rek' => '4.01.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
        ));
        
        
    }
}