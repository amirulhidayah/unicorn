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
                'id' => '518dc47f-32fb-4304-960f-942780d2f969',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'perencanaan_anggaran' => 89000000,
                'anggaran_digunakan' => 80000000,
                'tahap_riwayat' => 2,
                'role' => 'ASN Sub Bagian Keuangan',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-07-24 06:06:35',
                'updated_at' => '2023-07-24 06:06:35',
            ),
            1 => 
            array (
                'id' => '5ce519d7-a9dd-4205-bfea-6f5573555ade',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'perencanaan_anggaran' => NULL,
                'anggaran_digunakan' => NULL,
                'tahap_riwayat' => 1,
                'role' => 'Admin',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Upload SPM',
                'created_at' => '2023-07-24 06:08:57',
                'updated_at' => '2023-07-24 06:08:57',
            ),
            2 => 
            array (
                'id' => '7c9abf0a-9667-435a-8754-450d522319a2',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'perencanaan_anggaran' => 89000000,
                'anggaran_digunakan' => 0,
                'tahap_riwayat' => 1,
                'role' => 'ASN Sub Bagian Keuangan',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-07-24 06:05:34',
                'updated_at' => '2023-07-24 06:05:34',
            ),
            3 => 
            array (
                'id' => '90c0cc7a-a79f-4bc9-8005-4a95c12ed25f',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'perencanaan_anggaran' => 89000000,
                'anggaran_digunakan' => 0,
                'tahap_riwayat' => 1,
                'role' => 'PPK',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-07-24 06:05:54',
                'updated_at' => '2023-07-24 06:05:54',
            ),
            4 => 
            array (
                'id' => '9750baed-e78f-47ea-af4d-a79149e8f06f',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'perencanaan_anggaran' => NULL,
                'anggaran_digunakan' => NULL,
                'tahap_riwayat' => 1,
                'role' => 'Admin',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Upload Arsip SP2D',
                'created_at' => '2023-07-24 06:09:03',
                'updated_at' => '2023-07-24 06:09:03',
            ),
            5 => 
            array (
                'id' => 'bc01b292-b563-427b-ba31-6ce134d6fa22',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'perencanaan_anggaran' => 89000000,
                'anggaran_digunakan' => 80000000,
                'tahap_riwayat' => 2,
                'role' => 'PPK',
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2023-07-24 06:06:50',
                'updated_at' => '2023-07-24 06:06:50',
            ),
            6 => 
            array (
                'id' => 'c73a580c-5f8c-4c91-963b-8ef242954be7',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'perencanaan_anggaran' => 89000000,
                'anggaran_digunakan' => NULL,
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-07-24 06:02:55',
                'updated_at' => '2023-07-24 06:02:55',
            ),
            7 => 
            array (
                'id' => 'e94537be-0912-49e4-a7aa-c315cd06c453',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'perencanaan_anggaran' => NULL,
                'anggaran_digunakan' => 80000000,
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Upload Tahap SPP',
                'created_at' => '2023-07-24 06:06:19',
                'updated_at' => '2023-07-24 06:06:19',
            ),
            8 => 
            array (
                'id' => 'fae28018-6a89-4436-92dc-311567e1aaf6',
                'spp_gu_id' => '0d88eae1-15f1-4676-b7e7-854240041a16',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'perencanaan_anggaran' => NULL,
                'anggaran_digunakan' => 80000000,
                'tahap_riwayat' => 1,
                'role' => NULL,
                'nomor_surat' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Diselesaikan',
                'created_at' => '2023-07-24 06:08:43',
                'updated_at' => '2023-07-24 06:08:43',
            ),
        ));
        
        
    }
}