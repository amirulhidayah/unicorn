<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SekretariatDaerahTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('sekretariat_daerah')->delete();

        \DB::table('sekretariat_daerah')->insert(array(
            0 =>
            array(
                'id' => '00c190c9-9c8a-429d-b5f8-5196cba3b260',
                'nama' => 'Tata Usaha Pimpinan',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:09:23',
                'updated_at' => '2022-04-19 08:09:23',
            ),
            1 =>
            array(
                'id' => '3477982b-90f4-4309-984d-0e8e871b0557',
                'nama' => 'Biro Organisasi',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:09:07',
                'updated_at' => '2022-04-19 08:09:07',
            ),
            2 =>
            array(
                'id' => '5201a844-655a-4287-8812-de0702f36ddb',
                'nama' => 'Biro Administrasi Pembangunan',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:08:48',
                'updated_at' => '2022-04-19 08:08:48',
            ),
            3 =>
            array(
                'id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'nama' => 'Biro Umum',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:08:39',
                'updated_at' => '2022-04-19 08:08:39',
            ),
            4 =>
            array(
                'id' => '68d47270-c3fd-44a0-9033-0d5c4259a756',
                'nama' => 'Biro Administrasi Kesejahteraan Rakyat',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:08:58',
                'updated_at' => '2022-04-19 08:08:58',
            ),
            5 =>
            array(
                'id' => '805d61ed-9a79-43d5-90c1-68bbf1753925',
                'nama' => 'Biro Perekonomian',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:08:55',
                'updated_at' => '2022-04-19 08:08:55',
            ),
            6 =>
            array(
                'id' => '9c0fb035-000a-4a79-a84f-7a6a585b9159',
                'nama' => 'Biro Hukum',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:08:52',
                'updated_at' => '2022-04-19 08:08:52',
            ),
            7 =>
            array(
                'id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'nama' => 'Biro Pemerintahan dan Otonomi Daerah',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:08:45',
                'updated_at' => '2022-04-19 08:08:45',
            ),
            8 =>
            array(
                'id' => 'ba3ce479-c20b-49fe-a822-0a9c262e6d9b',
                'nama' => 'Wakil Kepala Daerah',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:09:18',
                'updated_at' => '2022-04-19 08:09:18',
            ),
            9 =>
            array(
                'id' => 'c026e477-34ad-4de8-a203-b14b48694b63',
                'nama' => 'Biro Pengadaan Barang / Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:09:02',
                'updated_at' => '2022-04-19 08:09:02',
            ),
            10 =>
            array(
                'id' => 'dc4b10a9-fb12-4961-8feb-b63cbc184987',
                'nama' => 'Kepala Daerah',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:09:15',
                'updated_at' => '2022-04-19 08:09:15',
            ),
            11 =>
            array(
                'id' => 'e7ca83c4-7324-4afd-814d-4fed5111a14e',
                'nama' => 'Biro Hubungan Masyarakat dan Protokol',
                'deleted_at' => NULL,
                'created_at' => '2022-04-19 08:09:11',
                'updated_at' => '2022-04-19 08:09:11',
            ),
        ));
    }
}
