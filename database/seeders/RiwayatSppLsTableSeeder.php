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
                'alasan' => NULL,
                'anggaran_digunakan' => 230000,
                'created_at' => '2023-07-26 06:14:47',
                'id' => '04a4310a-ba7b-4d81-9d84-56d11779496d',
                'nomor_surat' => NULL,
                'role' => 'PPK',
                'spp_ls_id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'status' => 'Disetujui',
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-07-26 06:14:47',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
            ),
            1 => 
            array (
                'alasan' => NULL,
                'anggaran_digunakan' => NULL,
                'created_at' => '2023-07-26 06:15:11',
                'id' => '58c7e297-a8e8-4658-be0c-4ff34822cf2b',
                'nomor_surat' => NULL,
                'role' => 'Admin',
                'spp_ls_id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'status' => 'Upload Arsip SP2D',
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-07-26 06:15:11',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
            2 => 
            array (
                'alasan' => NULL,
                'anggaran_digunakan' => NULL,
                'created_at' => '2023-07-26 06:15:05',
                'id' => '69c8d2ce-73a2-4d58-b3d9-3927f3e76571',
                'nomor_surat' => NULL,
                'role' => 'Admin',
                'spp_ls_id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'status' => 'Upload SPM',
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-07-26 06:15:05',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
            3 => 
            array (
                'alasan' => NULL,
                'anggaran_digunakan' => 230000,
                'created_at' => '2023-07-26 06:14:53',
                'id' => '880f7717-7614-4e35-bff9-1503522cf096',
                'nomor_surat' => NULL,
                'role' => NULL,
                'spp_ls_id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'status' => 'Diselesaikan',
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-07-26 06:14:53',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
            ),
            4 => 
            array (
                'alasan' => NULL,
                'anggaran_digunakan' => 230000,
                'created_at' => '2023-07-26 06:14:33',
                'id' => 'd13e4f91-96af-4bc4-aaa2-ca0f74dc1edf',
                'nomor_surat' => NULL,
                'role' => 'ASN Sub Bagian Keuangan',
                'spp_ls_id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'status' => 'Disetujui',
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-07-26 06:14:33',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
            ),
            5 => 
            array (
                'alasan' => NULL,
                'anggaran_digunakan' => 230000,
                'created_at' => '2023-07-26 06:12:34',
                'id' => 'd528b626-502c-415f-a458-fd002bbbee28',
                'nomor_surat' => NULL,
                'role' => NULL,
                'spp_ls_id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'status' => 'Dibuat',
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'updated_at' => '2023-07-26 06:12:34',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
        ));
        
        
    }
}