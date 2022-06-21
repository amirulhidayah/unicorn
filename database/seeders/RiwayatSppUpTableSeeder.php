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
                'id' => '4d9353eb-c66f-441d-9c88-a8ab0b855459',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 2,
                'nomor_surat' => NULL,
                'role' => 'ASN Sub Bagian Keuangan',
                'jumlah_anggaran' => 17250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2022-06-21 12:00:38',
                'updated_at' => '2022-06-21 12:00:38',
            ),
            1 => 
            array (
                'id' => 'be385a73-19c3-42b0-9281-4117c892f5e4',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'jumlah_anggaran' => 17250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2022-06-21 11:43:58',
                'updated_at' => '2022-06-21 11:43:58',
            ),
            2 => 
            array (
                'id' => 'c472f8d5-6d50-41c5-8653-e52ca44c8f36',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => 'PPK',
                'jumlah_anggaran' => 17250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2022-06-21 11:47:24',
                'updated_at' => '2022-06-21 11:47:24',
            ),
            3 => 
            array (
                'id' => 'ca5dda01-61f8-4b62-a85c-b1f0835ad24b',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'jumlah_anggaran' => 17250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Diselesaikan',
                'created_at' => '2022-06-21 12:01:03',
                'updated_at' => '2022-06-21 12:01:03',
            ),
            4 => 
            array (
                'id' => 'd7bdace0-9d2c-4d2b-9111-d86da5d0cb6c',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 1,
                'nomor_surat' => '1/SPP-UP/P/06/2022',
                'role' => 'ASN Sub Bagian Keuangan',
                'jumlah_anggaran' => 17250000,
                'alasan' => 'Kurang 1 Dokumen, dan Dokumen SPD Salah',
                'surat_penolakan' => NULL,
                'status' => 'Ditolak',
                'created_at' => '2022-06-21 11:46:34',
                'updated_at' => '2022-06-21 11:46:34',
            ),
            5 => 
            array (
                'id' => 'dd2d9428-e8ec-458a-86ed-0018e612f1c1',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'jumlah_anggaran' => 17250000,
                'alasan' => NULL,
                'surat_penolakan' => 'Surat Penolakan-2022062111513519.pdf',
                'status' => 'Diperbaiki',
                'created_at' => '2022-06-21 11:58:51',
                'updated_at' => '2022-06-21 11:58:51',
            ),
        ));
        
        
    }
}