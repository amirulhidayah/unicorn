<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RiwayatSppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('riwayat_spp_ls')->delete();
        
        \DB::table('riwayat_spp_ls')->insert(array (
            0 => 
            array (
                'id' => '1ea30120-b297-424e-9c1b-0a5a609ec785',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            1 => 
            array (
                'id' => '2a996e2a-7577-4006-8466-9e189ed275ea',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            2 => 
            array (
                'id' => '56760ebb-e6f7-4407-a19d-fc86726cdfd6',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            3 => 
            array (
                'id' => '5ded5c18-a5fd-44a9-8a5f-8679e54e7065',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            4 => 
            array (
                'id' => '880dfaf1-5c71-4bd4-bac5-fa6ea9609f99',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            5 => 
            array (
                'id' => '8b2625f8-916b-4183-9fc3-60d8664ac49e',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
        ));
        
        
    }
}