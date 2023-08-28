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
                'id' => '2fc26120-dda7-4f71-8e9e-46c89f09e9ad',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 54000000,
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            1 => 
            array (
                'id' => '30efeab9-1822-4d79-9741-b51d1d1a5bf6',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'anggaran_digunakan' => 4000000000,
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            2 => 
            array (
                'id' => '407eda01-0cd8-45f9-8305-2b9a9d9d6ee2',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'kegiatan_id' => '008cd974-674b-4c68-ac0b-3e7192339956',
                'anggaran_digunakan' => 31009999,
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            3 => 
            array (
                'id' => '48a58310-3a3d-4403-9b55-7edaf4a9e1f4',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 81000000,
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            4 => 
            array (
                'id' => '4cfaead1-3c75-40a7-a202-f2189d781d06',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'kegiatan_id' => '93e605d7-254e-4deb-8045-5ab64e90af5b',
                'anggaran_digunakan' => 54003000,
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            5 => 
            array (
                'id' => '5c8ac0ba-d133-4c67-8ab5-ddf8f3c4dc55',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'anggaran_digunakan' => 951000000,
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            6 => 
            array (
                'id' => '66e210e7-5f6b-4093-bd07-7ffb0bf000c4',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 30000000,
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            7 => 
            array (
                'id' => '7552022f-ea99-440d-ba62-4ce35572d05a',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'kegiatan_id' => 'fe482d7f-ddae-4e50-ac6d-af083bb94376',
                'anggaran_digunakan' => 76783000,
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            8 => 
            array (
                'id' => '785afd94-444d-4674-aa7a-5f92bd649097',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'kegiatan_id' => '687885b5-8629-4a85-9cf1-3b5641f48ff8',
                'anggaran_digunakan' => 48500000,
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            9 => 
            array (
                'id' => '877fc485-11df-49aa-87f7-b0420bbf0a2d',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'kegiatan_id' => 'fe482d7f-ddae-4e50-ac6d-af083bb94376',
                'anggaran_digunakan' => 76000000,
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            10 => 
            array (
                'id' => '8bf40032-c7d8-4978-9611-34048898f4c5',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 569000,
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            11 => 
            array (
                'id' => 'b1070421-3e03-4cef-a054-4ad5d1fe80df',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'kegiatan_id' => '7fd723e8-04a4-4e9e-87e4-58e5ec1dc350',
                'anggaran_digunakan' => 54300000,
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            12 => 
            array (
                'id' => 'b51b4e08-9be3-48c9-8b1b-ce8f84023970',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'anggaran_digunakan' => 49000000000,
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            13 => 
            array (
                'id' => 'b9ec8dbc-9d73-4d4a-8b0e-1e231f9eeb5c',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'kegiatan_id' => '93e605d7-254e-4deb-8045-5ab64e90af5b',
                'anggaran_digunakan' => 300000000,
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            14 => 
            array (
                'id' => 'c0d2b59d-d904-49b8-9cd4-eb82dcde995f',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'kegiatan_id' => '36352ee8-acb3-4157-81ba-20f9091705dc',
                'anggaran_digunakan' => 30020009,
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            15 => 
            array (
                'id' => 'e7515937-8bf8-4ad1-8eef-1c59bd6c4086',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 110000000,
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            16 => 
            array (
                'id' => 'e8cd70bb-49d4-40c2-848e-52775b0d77c5',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 9000000,
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
        ));
        
        
    }
}