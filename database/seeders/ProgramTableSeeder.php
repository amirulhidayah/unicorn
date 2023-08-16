<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProgramTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('program')->delete();

        \DB::table('program')->insert(array(
            0 =>
            array(
                'id' => '23f59723-7c80-4e1f-8f3e-fc569ebdbbfd',
                'nama' => 'PROGRAM KESEJAHTERAAN RAKYAT',
                'no_rek' => '4.01.04',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            1 =>
            array(
                'id' => '30c590e0-18a8-47a0-bb23-2b455361470a',
                'nama' => 'PROGRAM KEBIJAKAN DAN PELAYANAN PENGADAAN BARANG DAN JASA',
                'no_rek' => '4.01.07',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            2 =>
            array(
                'id' => '524719e3-13b9-4228-9409-22093e3e3b9e',
                'nama' => 'PROGRAM PENATAAN ORGANISASI',
                'no_rek' => '4.01.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            3 =>
            array(
                'id' => '987af6cd-4e2a-487a-9752-4e97ce4d554c',
                'nama' => 'PROGRAM FASILITASI DAN KOORDINASI HUKUM',
                'no_rek' => '4.01.06',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            4 =>
            array(
                'id' => 'b8c90913-1be3-4dfa-832c-06f68f03400c',
                'nama' => 'PROGRAM FASILITASI DAN KOORDINASI HUKUM',
                'no_rek' => '4.01.05',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            5 =>
            array(
                'id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI',
                'no_rek' => '4.01.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            6 =>
            array(
                'id' => 'eda52000-a895-4ebb-9e17-9952b2c084f3',
                'nama' => 'PROGRAM PEMERINTAHAN DAN OTONOMI DAERAH',
                'no_rek' => '4.01.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            7 =>
            array(
                'id' => 'f81866f9-c407-4e4d-9da4-e3d50c69d48d',
                'nama' => 'PROGRAM KEBIJAKAN ADMINISTRASI PEMBANGUNAN',
                'no_rek' => '4.01.08',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
        ));
    }
}
