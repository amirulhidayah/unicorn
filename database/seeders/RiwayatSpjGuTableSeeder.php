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
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:45:15',
                'id' => '33e9245d-48e6-4696-aeaf-f8b71c82669d',
                'nomor_surat' => NULL,
                'role' => NULL,
                'spj_gu_id' => 'a30b869c-2300-4c98-9bc3-95c5dcccbb77',
                'status' => 'Dibuat',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:45:15',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
            1 => 
            array (
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:46:14',
                'id' => '342b4762-c8a3-429a-8212-df887c376dbb',
                'nomor_surat' => NULL,
                'role' => 'ASN Sub Bagian Keuangan',
                'spj_gu_id' => '039155d1-37cc-4d24-8b19-5bc6fad7e9a4',
                'status' => 'Disetujui',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:46:14',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
            ),
            2 => 
            array (
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:46:38',
                'id' => '43fb97b2-34ef-46b0-8665-e63df370280a',
                'nomor_surat' => NULL,
                'role' => 'PPK',
                'spj_gu_id' => 'a30b869c-2300-4c98-9bc3-95c5dcccbb77',
                'status' => 'Disetujui',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:46:38',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
            ),
            3 => 
            array (
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:46:32',
                'id' => '6e777eea-8553-4e53-b681-6934952d96b8',
                'nomor_surat' => NULL,
                'role' => 'PPK',
                'spj_gu_id' => '039155d1-37cc-4d24-8b19-5bc6fad7e9a4',
                'status' => 'Disetujui',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:46:32',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
            ),
            4 => 
            array (
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:46:42',
                'id' => '75b45980-2ab1-4ae0-84c1-0f77e3f96fe6',
                'nomor_surat' => NULL,
                'role' => NULL,
                'spj_gu_id' => '039155d1-37cc-4d24-8b19-5bc6fad7e9a4',
                'status' => 'Diselesaikan',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:46:42',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
            ),
            5 => 
            array (
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:46:50',
                'id' => 'b9d1489a-6fb7-4c64-847c-55cf58ad218a',
                'nomor_surat' => NULL,
                'role' => NULL,
                'spj_gu_id' => 'a30b869c-2300-4c98-9bc3-95c5dcccbb77',
                'status' => 'Diselesaikan',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:46:50',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
            ),
            6 => 
            array (
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:46:20',
                'id' => 'f41aa988-1007-4d9b-b1c8-5e708a11784e',
                'nomor_surat' => NULL,
                'role' => 'ASN Sub Bagian Keuangan',
                'spj_gu_id' => 'a30b869c-2300-4c98-9bc3-95c5dcccbb77',
                'status' => 'Disetujui',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:46:20',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
            ),
            7 => 
            array (
                'alasan' => NULL,
                'created_at' => '2023-08-23 01:46:00',
                'id' => 'fe8124fe-f137-4b93-b23b-eb19b8fafa2d',
                'nomor_surat' => NULL,
                'role' => NULL,
                'spj_gu_id' => '039155d1-37cc-4d24-8b19-5bc6fad7e9a4',
                'status' => 'Dibuat',
                'surat_pengembalian' => NULL,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-08-23 01:46:00',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
        ));
        
        
    }
}