<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KegiatanSppUpTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kegiatan_spp_up')->delete();
        
        \DB::table('kegiatan_spp_up')->insert(array (
            0 => 
            array (
                'id' => '212880a3-91bc-40bc-bf55-bae37cfdf848',
                'spp_up_id' => '7758e663-b8a2-45d0-92f4-d082db249ea1',
                'kegiatan_id' => 'fe482d7f-ddae-4e50-ac6d-af083bb94376',
                'jumlah_anggaran' => 34200000,
                'created_at' => '2023-08-28 03:38:04',
                'updated_at' => '2023-08-28 03:38:04',
            ),
            1 => 
            array (
                'id' => '23d3d440-b1dc-4851-9a43-ca38c48d6173',
                'spp_up_id' => 'acbe3146-c10d-47e2-89ef-a91723041ce3',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'jumlah_anggaran' => 300000000,
                'created_at' => '2023-08-28 03:35:46',
                'updated_at' => '2023-08-28 03:35:46',
            ),
            2 => 
            array (
                'id' => '2748862f-546c-4432-aac2-2cd79cea7df8',
                'spp_up_id' => '588fce7b-84c5-4ce8-89e5-a80ea334fb62',
                'kegiatan_id' => '687885b5-8629-4a85-9cf1-3b5641f48ff8',
                'jumlah_anggaran' => 33000000,
                'created_at' => '2023-08-28 03:07:55',
                'updated_at' => '2023-08-28 03:07:55',
            ),
            3 => 
            array (
                'id' => '2b17c0c3-29aa-4084-9f83-5c10e732baa2',
                'spp_up_id' => '588fce7b-84c5-4ce8-89e5-a80ea334fb62',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'jumlah_anggaran' => 300000000,
                'created_at' => '2023-08-28 03:07:55',
                'updated_at' => '2023-08-28 03:07:55',
            ),
            4 => 
            array (
                'id' => '32dfd9a6-bd00-4b3d-9c70-70886a0ea495',
                'spp_up_id' => '51d28665-6009-43a3-828e-07e6ab4ef66e',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'jumlah_anggaran' => 400000000,
                'created_at' => '2023-08-28 03:09:01',
                'updated_at' => '2023-08-28 03:09:01',
            ),
            5 => 
            array (
                'id' => '373d66b6-6f4b-4c5f-90bb-7f4b4ac617a7',
                'spp_up_id' => '6a6702aa-5f37-4ac6-9968-d0a19fbcde89',
                'kegiatan_id' => 'fb153f91-0250-480f-b287-dd4e6b9929b9',
                'jumlah_anggaran' => 3900000000,
                'created_at' => '2023-08-28 03:09:47',
                'updated_at' => '2023-08-28 03:09:47',
            ),
            6 => 
            array (
                'id' => '438d9bef-cf2a-4aa9-90ad-c7c9396546d9',
                'spp_up_id' => 'acbe3146-c10d-47e2-89ef-a91723041ce3',
                'kegiatan_id' => 'c23705cf-0f75-4eec-9e21-24ddc223624c',
                'jumlah_anggaran' => 25000000,
                'created_at' => '2023-08-28 03:35:46',
                'updated_at' => '2023-08-28 03:35:46',
            ),
            7 => 
            array (
                'id' => '55a6a4c7-8efb-4331-987f-59c80310924c',
                'spp_up_id' => '4ee72da0-232a-4817-a335-61231954ad00',
                'kegiatan_id' => '687885b5-8629-4a85-9cf1-3b5641f48ff8',
                'jumlah_anggaran' => 35000000,
                'created_at' => '2023-08-28 03:37:18',
                'updated_at' => '2023-08-28 03:37:18',
            ),
            8 => 
            array (
                'id' => '5c33c1ac-d40c-484b-9898-d96f002059a3',
                'spp_up_id' => '51d28665-6009-43a3-828e-07e6ab4ef66e',
                'kegiatan_id' => 'd99e34fe-43ac-4537-a17c-6851784f666e',
                'jumlah_anggaran' => 350000000,
                'created_at' => '2023-08-28 03:09:01',
                'updated_at' => '2023-08-28 03:09:01',
            ),
            9 => 
            array (
                'id' => '7cd1d7a6-7ac5-4daa-92e4-75a8ef60f350',
                'spp_up_id' => 'acbe3146-c10d-47e2-89ef-a91723041ce3',
                'kegiatan_id' => '89d00770-3a1b-472f-b53c-2b8045ef5839',
                'jumlah_anggaran' => 10000000,
                'created_at' => '2023-08-28 03:35:46',
                'updated_at' => '2023-08-28 03:35:46',
            ),
            10 => 
            array (
                'id' => '9230abe9-337a-4366-a88c-d6d56403dcdc',
                'spp_up_id' => '4ee72da0-232a-4817-a335-61231954ad00',
                'kegiatan_id' => 'fe482d7f-ddae-4e50-ac6d-af083bb94376',
                'jumlah_anggaran' => 2000000,
                'created_at' => '2023-08-28 03:37:18',
                'updated_at' => '2023-08-28 03:37:18',
            ),
            11 => 
            array (
                'id' => '994570d8-71b5-4a2a-b7f8-e65b12bdde7b',
                'spp_up_id' => '7758e663-b8a2-45d0-92f4-d082db249ea1',
                'kegiatan_id' => 'c23705cf-0f75-4eec-9e21-24ddc223624c',
                'jumlah_anggaran' => 19000000,
                'created_at' => '2023-08-28 03:38:04',
                'updated_at' => '2023-08-28 03:38:04',
            ),
            12 => 
            array (
                'id' => 'abc7264c-afeb-42dd-83de-3d9d8f390eff',
                'spp_up_id' => 'acbe3146-c10d-47e2-89ef-a91723041ce3',
                'kegiatan_id' => 'd4b869dd-3b95-4bbd-8b05-2e85294e5012',
                'jumlah_anggaran' => 1999999,
                'created_at' => '2023-08-28 03:35:46',
                'updated_at' => '2023-08-28 03:35:46',
            ),
            13 => 
            array (
                'id' => 'ba72e3ab-12f4-410f-a936-7794742d6e67',
                'spp_up_id' => '6a6702aa-5f37-4ac6-9968-d0a19fbcde89',
                'kegiatan_id' => '1b5a2ee3-2c4e-4649-a5a4-292571509ba3',
                'jumlah_anggaran' => 15400000,
                'created_at' => '2023-08-28 03:09:47',
                'updated_at' => '2023-08-28 03:09:47',
            ),
            14 => 
            array (
                'id' => 'ca76a7db-3eec-4616-a1dc-33ea4c1e06ba',
                'spp_up_id' => '588fce7b-84c5-4ce8-89e5-a80ea334fb62',
                'kegiatan_id' => '1b5a2ee3-2c4e-4649-a5a4-292571509ba3',
                'jumlah_anggaran' => 150000000,
                'created_at' => '2023-08-28 03:07:55',
                'updated_at' => '2023-08-28 03:07:55',
            ),
            15 => 
            array (
                'id' => 'd7b613cd-624d-45c3-b685-9b5661080652',
                'spp_up_id' => '51d28665-6009-43a3-828e-07e6ab4ef66e',
                'kegiatan_id' => '1b5a2ee3-2c4e-4649-a5a4-292571509ba3',
                'jumlah_anggaran' => 28999999,
                'created_at' => '2023-08-28 03:09:01',
                'updated_at' => '2023-08-28 03:09:01',
            ),
            16 => 
            array (
                'id' => 'e1297eda-f47c-492d-bf59-d96c1f071897',
                'spp_up_id' => '6a6702aa-5f37-4ac6-9968-d0a19fbcde89',
                'kegiatan_id' => '687885b5-8629-4a85-9cf1-3b5641f48ff8',
                'jumlah_anggaran' => 1000090000,
                'created_at' => '2023-08-28 03:09:47',
                'updated_at' => '2023-08-28 03:09:47',
            ),
            17 => 
            array (
                'id' => 'f85f1234-32b9-4932-b29c-13dc9be2198e',
                'spp_up_id' => '4ee72da0-232a-4817-a335-61231954ad00',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'jumlah_anggaran' => 30009000,
                'created_at' => '2023-08-28 03:37:18',
                'updated_at' => '2023-08-28 03:37:18',
            ),
        ));
        
        
    }
}