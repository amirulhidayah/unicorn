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
                'id' => '216c926c-5e18-4ffc-9922-be5a0e2d3472',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'kegiatan_id' => '7fd723e8-04a4-4e9e-87e4-58e5ec1dc350',
                'anggaran_digunakan' => 150000000,
                'dokumen' => '16932009701212.pdf',
                'created_at' => '2023-08-28 13:36:10',
                'updated_at' => '2023-08-28 13:36:10',
            ),
            1 => 
            array (
                'id' => '2f249fdd-9130-4535-8884-49422878a8ee',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 50000000,
                'dokumen' => '16932009706666.pdf',
                'created_at' => '2023-08-28 13:36:10',
                'updated_at' => '2023-08-28 13:36:10',
            ),
            2 => 
            array (
                'id' => '34647514-43fb-495b-b0c1-811790b4ab96',
                'spj_gu_id' => '6940b9ba-b841-403e-aff1-64cd11c44a96',
                'kegiatan_id' => '7fd723e8-04a4-4e9e-87e4-58e5ec1dc350',
                'anggaran_digunakan' => 25000000,
                'dokumen' => '16932010194569.pdf',
                'created_at' => '2023-08-28 13:36:59',
                'updated_at' => '2023-08-28 13:36:59',
            ),
            3 => 
            array (
                'id' => '3957d87a-24ae-49a4-a8c9-efdf6375abae',
                'spj_gu_id' => '6940b9ba-b841-403e-aff1-64cd11c44a96',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 15000000,
                'dokumen' => '16932010194514.pdf',
                'created_at' => '2023-08-28 13:36:59',
                'updated_at' => '2023-08-28 13:36:59',
            ),
            4 => 
            array (
                'id' => '46b7e353-2b12-488e-b89a-52314c6d01da',
                'spj_gu_id' => '6940b9ba-b841-403e-aff1-64cd11c44a96',
                'kegiatan_id' => '36352ee8-acb3-4157-81ba-20f9091705dc',
                'anggaran_digunakan' => 55000000,
                'dokumen' => '16932010194525.pdf',
                'created_at' => '2023-08-28 13:36:59',
                'updated_at' => '2023-08-28 13:36:59',
            ),
            5 => 
            array (
                'id' => '4bbe9e76-0b76-48f5-aef1-7a4e3af5e8dd',
                'spj_gu_id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 5900,
                'dokumen' => '16932015866727.pdf',
                'created_at' => '2023-08-28 13:46:26',
                'updated_at' => '2023-08-28 13:46:26',
            ),
            6 => 
            array (
                'id' => '505e98d1-3c86-4a58-ad60-7b99cb2fd593',
                'spj_gu_id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'anggaran_digunakan' => 700000000,
                'dokumen' => '16932015561706.pdf',
                'created_at' => '2023-08-28 13:45:56',
                'updated_at' => '2023-08-28 13:45:56',
            ),
            7 => 
            array (
                'id' => '66491cc4-0910-4f9d-87b3-523a47f1533f',
                'spj_gu_id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 490000,
                'dokumen' => '16932015563239.pdf',
                'created_at' => '2023-08-28 13:45:56',
                'updated_at' => '2023-08-28 13:45:56',
            ),
            8 => 
            array (
                'id' => 'a48e7dc2-7283-4e86-9e6f-c46e144d6b16',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'kegiatan_id' => '008cd974-674b-4c68-ac0b-3e7192339956',
                'anggaran_digunakan' => 60000000,
                'dokumen' => '16932009701954.pdf',
                'created_at' => '2023-08-28 13:36:10',
                'updated_at' => '2023-08-28 13:36:10',
            ),
            9 => 
            array (
                'id' => 'a6edddca-1d1f-4ba0-9530-3ca1a9fe3c74',
                'spj_gu_id' => '50d65814-7434-4cb0-b33e-1db3500f7ee0',
                'kegiatan_id' => '04ddbb18-fdef-4e83-bfe1-cb57e4031377',
                'anggaran_digunakan' => 80000000,
                'dokumen' => '16932015266680.pdf',
                'created_at' => '2023-08-28 13:45:26',
                'updated_at' => '2023-08-28 13:45:26',
            ),
            10 => 
            array (
                'id' => 'b3b4977b-a7c6-4189-bace-e46b769d3ddd',
                'spj_gu_id' => '50d65814-7434-4cb0-b33e-1db3500f7ee0',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'anggaran_digunakan' => 250000000,
                'dokumen' => '16932015267492.pdf',
                'created_at' => '2023-08-28 13:45:26',
                'updated_at' => '2023-08-28 13:45:26',
            ),
            11 => 
            array (
                'id' => 'b4bb080d-a7ff-4f4b-ab7e-4a51a9a52bd1',
                'spj_gu_id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'anggaran_digunakan' => 25000000,
                'dokumen' => '1693201586726.pdf',
                'created_at' => '2023-08-28 13:46:26',
                'updated_at' => '2023-08-28 13:46:26',
            ),
            12 => 
            array (
                'id' => 'cb509331-08f3-4698-8d3a-3451d8379a6e',
                'spj_gu_id' => '50d65814-7434-4cb0-b33e-1db3500f7ee0',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 500000,
                'dokumen' => '16932015265616.pdf',
                'created_at' => '2023-08-28 13:45:26',
                'updated_at' => '2023-08-28 13:45:26',
            ),
            13 => 
            array (
                'id' => 'd25ef00a-b5fe-449b-9973-aba7e6fb2e65',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'kegiatan_id' => '7fd723e8-04a4-4e9e-87e4-58e5ec1dc350',
                'anggaran_digunakan' => 95700000,
                'dokumen' => '16932007398626.pdf',
                'created_at' => '2023-08-28 13:32:19',
                'updated_at' => '2023-08-28 13:32:19',
            ),
            14 => 
            array (
                'id' => 'e134b913-1f19-44d3-8880-70bb7375990f',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'anggaran_digunakan' => 10000000,
                'dokumen' => '16932007397259.pdf',
                'created_at' => '2023-08-28 13:32:19',
                'updated_at' => '2023-08-28 13:32:19',
            ),
            15 => 
            array (
                'id' => 'edbc3e9f-69b0-4e53-8149-fde27955ff62',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'kegiatan_id' => '36352ee8-acb3-4157-81ba-20f9091705dc',
                'anggaran_digunakan' => 99000000,
                'dokumen' => '1693200739969.pdf',
                'created_at' => '2023-08-28 13:32:19',
                'updated_at' => '2023-08-28 13:32:19',
            ),
        ));
        
        
    }
}