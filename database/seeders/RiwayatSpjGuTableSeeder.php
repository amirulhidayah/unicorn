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
                'id' => '0f1ecad3-3a47-4534-ac42-231a0e50c0d3',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Diselesaikan',
                'created_at' => '2023-08-28 21:48:15',
                'updated_at' => '2023-08-28 21:48:15',
            ),
            1 => 
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
            2 => 
            array (
                'id' => '1b7189c7-3a1d-4f6e-a2de-81b804465dc1',
                'spj_gu_id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 1,
                'role' => 'ASN Sub Bagian Keuangan',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:44:59',
                'updated_at' => '2023-08-28 21:44:59',
            ),
            3 => 
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
            4 => 
            array (
                'id' => '31f99788-4ad9-4517-ad63-fbdfb03fc148',
                'spj_gu_id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Diselesaikan',
                'created_at' => '2023-08-28 21:46:09',
                'updated_at' => '2023-08-28 21:46:09',
            ),
            5 => 
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
            6 => 
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
            7 => 
            array (
                'id' => '397eba79-08af-4ab9-8727-f222cde80c68',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => 'PPK',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:45:57',
                'updated_at' => '2023-08-28 21:45:57',
            ),
            8 => 
            array (
                'id' => '5946239b-711b-448b-9645-d1d0b2fba4c2',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 1,
                'role' => 'ASN Sub Bagian Keuangan',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:45:23',
                'updated_at' => '2023-08-28 21:45:23',
            ),
            9 => 
            array (
                'id' => '5f9df106-4a4e-4f73-acec-050d6a85cd21',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Diselesaikan',
                'created_at' => '2023-08-28 21:47:51',
                'updated_at' => '2023-08-28 21:47:51',
            ),
            10 => 
            array (
                'id' => '71ba2170-ea7d-4e29-b749-9d5421fb41ae',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => 'PPK',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:45:50',
                'updated_at' => '2023-08-28 21:45:50',
            ),
            11 => 
            array (
                'id' => '8b8ef176-f3e6-4c96-a3aa-fbca6513be5b',
                'spj_gu_id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 1,
                'role' => 'ASN Sub Bagian Keuangan',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:45:06',
                'updated_at' => '2023-08-28 21:45:06',
            ),
            12 => 
            array (
                'id' => '9876f1be-de0d-49c4-b0c4-a436c63cb741',
                'spj_gu_id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => 'PPK',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:45:44',
                'updated_at' => '2023-08-28 21:45:44',
            ),
            13 => 
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
            14 => 
            array (
                'id' => 'd1a57dd7-7f07-4e7c-b716-9cc2ce3fb003',
                'spj_gu_id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Diselesaikan',
                'created_at' => '2023-08-28 21:46:02',
                'updated_at' => '2023-08-28 21:46:02',
            ),
            15 => 
            array (
                'id' => 'd622cbfa-8fac-4bf5-9a42-334d1fa6634c',
                'spj_gu_id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'role' => 'PPK',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:45:37',
                'updated_at' => '2023-08-28 21:45:37',
            ),
            16 => 
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
            17 => 
            array (
                'id' => 'fbfe83dc-222f-4753-97cc-f88add64c6cd',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 1,
                'role' => 'ASN Sub Bagian Keuangan',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-08-28 21:45:17',
                'updated_at' => '2023-08-28 21:45:17',
            ),
        ));
        
        
    }
}