<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RiwayatSppGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('riwayat_spp_gu')->delete();
        
        \DB::table('riwayat_spp_gu')->insert(array (
            0 => 
            array (
                'id' => '085ff7a2-ee40-40a7-8d02-009503ff44f1',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            1 => 
            array (
                'id' => '21b364f9-af9c-4b54-90b8-95ea4ca92f15',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            2 => 
            array (
                'id' => '58df41df-c7a5-4111-a538-70d6b9561fe3',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            3 => 
            array (
                'id' => 'fd241265-c756-41a0-b79b-7bff18fb3879',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
        ));
        
        
    }
}