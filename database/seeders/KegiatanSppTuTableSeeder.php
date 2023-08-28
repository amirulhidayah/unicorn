<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KegiatanSppTuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kegiatan_spp_tu')->delete();
        
        \DB::table('kegiatan_spp_tu')->insert(array (
            0 => 
            array (
                'id' => '09d4c2fc-59a4-491e-ba0b-9de4ea4eec0e',
                'spp_tu_id' => '5c45acad-5f8a-4f86-908b-7f272b8f96f6',
                'kegiatan_id' => 'fe482d7f-ddae-4e50-ac6d-af083bb94376',
                'jumlah_anggaran' => 35400000,
                'created_at' => '2023-08-28 04:50:23',
                'updated_at' => '2023-08-28 04:50:23',
            ),
            1 => 
            array (
                'id' => '0aa6a8bd-81d0-47ba-99df-1b5644fb4b20',
                'spp_tu_id' => '3cf32643-8001-4b99-83f8-daa959a3ee25',
                'kegiatan_id' => 'd99e34fe-43ac-4537-a17c-6851784f666e',
                'jumlah_anggaran' => 30000000,
                'created_at' => '2023-08-28 04:42:31',
                'updated_at' => '2023-08-28 04:42:31',
            ),
            2 => 
            array (
                'id' => '0d0dcbdf-a583-4418-a133-eb10ccfb1b2b',
                'spp_tu_id' => '0f436b7b-d61d-4ad7-8c0e-58741122e340',
                'kegiatan_id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'jumlah_anggaran' => 30000000,
                'created_at' => '2023-08-28 04:41:40',
                'updated_at' => '2023-08-28 04:41:40',
            ),
            3 => 
            array (
                'id' => '25ed8d78-9faa-4bec-972a-5b17f1429a99',
                'spp_tu_id' => '42cf23fe-96ee-454f-8e9e-0e01d542b244',
                'kegiatan_id' => '687885b5-8629-4a85-9cf1-3b5641f48ff8',
                'jumlah_anggaran' => 450000000,
                'created_at' => '2023-08-28 04:51:29',
                'updated_at' => '2023-08-28 04:51:29',
            ),
            4 => 
            array (
                'id' => '2d7b1288-66ba-431f-a589-7eddd771a67a',
                'spp_tu_id' => '8dc0f0be-df1f-4f00-8a8f-d3093b643b72',
                'kegiatan_id' => 'b58fed40-8f0d-44f5-a061-cf812e49b37c',
                'jumlah_anggaran' => 45000000,
                'created_at' => '2023-08-28 04:52:17',
                'updated_at' => '2023-08-28 04:52:17',
            ),
            5 => 
            array (
                'id' => '30162321-b9b6-467c-9ce5-61f3794fdddd',
                'spp_tu_id' => '0f436b7b-d61d-4ad7-8c0e-58741122e340',
                'kegiatan_id' => 'c23705cf-0f75-4eec-9e21-24ddc223624c',
                'jumlah_anggaran' => 45000000,
                'created_at' => '2023-08-28 04:41:40',
                'updated_at' => '2023-08-28 04:41:40',
            ),
            6 => 
            array (
                'id' => '328ad267-167f-436c-a6c4-dc6007c1ea7c',
                'spp_tu_id' => '3cf32643-8001-4b99-83f8-daa959a3ee25',
                'kegiatan_id' => 'd4b869dd-3b95-4bbd-8b05-2e85294e5012',
                'jumlah_anggaran' => 25000000,
                'created_at' => '2023-08-28 04:42:31',
                'updated_at' => '2023-08-28 04:42:31',
            ),
            7 => 
            array (
                'id' => '6d7a8e5e-92a9-4a31-a92a-5a5e20eed2e8',
                'spp_tu_id' => '42cf23fe-96ee-454f-8e9e-0e01d542b244',
                'kegiatan_id' => 'fe482d7f-ddae-4e50-ac6d-af083bb94376',
                'jumlah_anggaran' => 14500000,
                'created_at' => '2023-08-28 04:51:29',
                'updated_at' => '2023-08-28 04:51:29',
            ),
            8 => 
            array (
                'id' => '737bc1b4-c5e0-4126-8963-5d07d22be22b',
                'spp_tu_id' => 'ee50dfca-c463-4362-8f32-d2786e54bd27',
                'kegiatan_id' => '687885b5-8629-4a85-9cf1-3b5641f48ff8',
                'jumlah_anggaran' => 35000000,
                'created_at' => '2023-08-28 04:43:28',
                'updated_at' => '2023-08-28 04:43:28',
            ),
            9 => 
            array (
                'id' => '86124e24-642f-404e-8d83-1b50465b7b26',
                'spp_tu_id' => 'ee50dfca-c463-4362-8f32-d2786e54bd27',
                'kegiatan_id' => 'c23705cf-0f75-4eec-9e21-24ddc223624c',
                'jumlah_anggaran' => 21000000,
                'created_at' => '2023-08-28 04:43:28',
                'updated_at' => '2023-08-28 04:43:28',
            ),
            10 => 
            array (
                'id' => '88e12377-2a66-4da3-8d6c-a029f711c7db',
                'spp_tu_id' => '3cf32643-8001-4b99-83f8-daa959a3ee25',
                'kegiatan_id' => 'c23705cf-0f75-4eec-9e21-24ddc223624c',
                'jumlah_anggaran' => 56000000,
                'created_at' => '2023-08-28 04:42:31',
                'updated_at' => '2023-08-28 04:42:31',
            ),
            11 => 
            array (
                'id' => '8c9cc4d3-88c2-4f60-a29d-9780f4bc3f2c',
                'spp_tu_id' => '8dc0f0be-df1f-4f00-8a8f-d3093b643b72',
                'kegiatan_id' => 'd99e34fe-43ac-4537-a17c-6851784f666e',
                'jumlah_anggaran' => 320000000,
                'created_at' => '2023-08-28 04:52:17',
                'updated_at' => '2023-08-28 04:52:17',
            ),
            12 => 
            array (
                'id' => 'b2931554-8b34-4d2a-91fa-8290c8175d4a',
                'spp_tu_id' => '8dc0f0be-df1f-4f00-8a8f-d3093b643b72',
                'kegiatan_id' => '45968532-7800-40f1-a0bc-3c3e378c2c2a',
                'jumlah_anggaran' => 139000000,
                'created_at' => '2023-08-28 04:52:17',
                'updated_at' => '2023-08-28 04:52:17',
            ),
            13 => 
            array (
                'id' => 'c75d6694-1040-4202-af3a-b3e327de13d1',
                'spp_tu_id' => '8dc0f0be-df1f-4f00-8a8f-d3093b643b72',
                'kegiatan_id' => '6f65e9cd-9f6c-44a6-8012-f3ed4341cd96',
                'jumlah_anggaran' => 165000000,
                'created_at' => '2023-08-28 04:52:17',
                'updated_at' => '2023-08-28 04:52:17',
            ),
            14 => 
            array (
                'id' => 'ca78bb00-74c9-4f7c-a47e-19148c42881c',
                'spp_tu_id' => '5c45acad-5f8a-4f86-908b-7f272b8f96f6',
                'kegiatan_id' => 'ba5d8c12-8796-416c-b463-392a8a39e93b',
                'jumlah_anggaran' => 24000000,
                'created_at' => '2023-08-28 04:50:23',
                'updated_at' => '2023-08-28 04:50:23',
            ),
            15 => 
            array (
                'id' => 'e80a3663-646a-4a83-a18f-821c3aaa4419',
                'spp_tu_id' => '42cf23fe-96ee-454f-8e9e-0e01d542b244',
                'kegiatan_id' => '1b5a2ee3-2c4e-4649-a5a4-292571509ba3',
                'jumlah_anggaran' => 39000000,
                'created_at' => '2023-08-28 04:51:29',
                'updated_at' => '2023-08-28 04:51:29',
            ),
        ));
        
        
    }
}