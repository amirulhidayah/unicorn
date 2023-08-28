<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RiwayatSppUpTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('riwayat_spp_up')->delete();
        
        \DB::table('riwayat_spp_up')->insert(array (
            0 => 
            array (
                'id' => '2f238be9-9e32-4126-8f7b-c509b3c3c3b4',
                'spp_up_id' => '6a6702aa-5f37-4ac6-9968-d0a19fbcde89',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 03:09:47',
                'updated_at' => '2023-08-28 03:09:47',
            ),
            1 => 
            array (
                'id' => '462d58d7-b8ad-4715-a167-467ed9bb1108',
                'spp_up_id' => '51d28665-6009-43a3-828e-07e6ab4ef66e',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 03:09:01',
                'updated_at' => '2023-08-28 03:09:01',
            ),
            2 => 
            array (
                'id' => '5ac91c9e-5bdb-48ac-afa8-72c5ba174067',
                'spp_up_id' => 'acbe3146-c10d-47e2-89ef-a91723041ce3',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 03:35:46',
                'updated_at' => '2023-08-28 03:35:46',
            ),
            3 => 
            array (
                'id' => '8225cda7-8c0f-4b48-9b89-76370ac92e9c',
                'spp_up_id' => '4ee72da0-232a-4817-a335-61231954ad00',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 03:37:18',
                'updated_at' => '2023-08-28 03:37:18',
            ),
            4 => 
            array (
                'id' => '86279c3f-846e-43b9-9c99-85a11a6648f1',
                'spp_up_id' => '588fce7b-84c5-4ce8-89e5-a80ea334fb62',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 03:07:55',
                'updated_at' => '2023-08-28 03:07:55',
            ),
            5 => 
            array (
                'id' => 'ce81f9c9-7c9c-418c-bf8d-2d6f6272e242',
                'spp_up_id' => '7758e663-b8a2-45d0-92f4-d082db249ea1',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 03:38:04',
                'updated_at' => '2023-08-28 03:38:04',
            ),
        ));
        
        
    }
}