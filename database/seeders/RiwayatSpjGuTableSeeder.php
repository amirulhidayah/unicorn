<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RiwayatSpjGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('riwayat_spj_gu')->delete();
        
        \DB::table('riwayat_spj_gu')->insert(array (
            0 => 
            array (
                'id' => '11ff7b42-ae6b-429a-bfa8-56b9f546a721',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:32:19',
                'updated_at' => '2023-08-28 13:32:19',
            ),
            1 => 
            array (
                'id' => '31150b13-9b09-44f1-b742-21083b850af6',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:36:10',
                'updated_at' => '2023-08-28 13:36:10',
            ),
            2 => 
            array (
                'id' => '3393bb87-a9fa-4e98-8808-5312eadad57b',
                'spj_gu_id' => '50d65814-7434-4cb0-b33e-1db3500f7ee0',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:45:26',
                'updated_at' => '2023-08-28 13:45:26',
            ),
            3 => 
            array (
                'id' => '37230e96-a1ae-4286-96ce-f57a6c537ea1',
                'spj_gu_id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:45:56',
                'updated_at' => '2023-08-28 13:45:56',
            ),
            4 => 
            array (
                'id' => 'cc3593ac-f442-4fd7-8b49-0f718d1084da',
                'spj_gu_id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:46:26',
                'updated_at' => '2023-08-28 13:46:26',
            ),
            5 => 
            array (
                'id' => 'e2a44b44-55f4-4a5b-bada-57c9654e2dc5',
                'spj_gu_id' => '6940b9ba-b841-403e-aff1-64cd11c44a96',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 13:36:59',
                'updated_at' => '2023-08-28 13:36:59',
            ),
        ));
        
        
    }
}