<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RiwayatSppTuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('riwayat_spp_tu')->delete();
        
        \DB::table('riwayat_spp_tu')->insert(array (
            0 => 
            array (
                'id' => '0ed5c086-085c-4917-9ee4-2fafb6f2f9aa',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'jumlah_anggaran' => 23250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2022-06-21 11:45:23',
                'updated_at' => '2022-06-21 11:45:23',
            ),
            1 => 
            array (
                'id' => '3209d17d-2658-4c5a-b1f5-b1ca1c61dc8a',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'jumlah_anggaran' => 23250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Diselesaikan',
                'created_at' => '2022-06-21 12:01:09',
                'updated_at' => '2022-06-21 12:01:09',
            ),
            2 => 
            array (
                'id' => '6b40ba94-3327-419d-a5f8-fc3878c49b85',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 2,
                'nomor_surat' => NULL,
                'role' => 'ASN Sub Bagian Keuangan',
                'jumlah_anggaran' => 23250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2022-06-21 12:00:48',
                'updated_at' => '2022-06-21 12:00:48',
            ),
            3 => 
            array (
                'id' => 'b3225144-6945-45a0-a750-819bbe63e40e',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'tahap_riwayat' => 1,
                'nomor_surat' => '1/SPP-TU/P/06/2022',
                'role' => 'ASN Sub Bagian Keuangan',
                'jumlah_anggaran' => 23250000,
                'alasan' => 'Surat Pengantar Kurang Tanda Tangan dan ganti bukti pembayaran',
                'surat_penolakan' => NULL,
                'status' => 'Ditolak',
                'created_at' => '2022-06-21 11:46:58',
                'updated_at' => '2022-06-21 11:46:58',
            ),
            4 => 
            array (
                'id' => 'bf499caf-6537-484f-8c54-1aa3fda24bd4',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'jumlah_anggaran' => 23250000,
                'alasan' => NULL,
                'surat_penolakan' => 'Surat Penolakan-2022062111393234.pdf',
                'status' => 'Diperbaiki',
                'created_at' => '2022-06-21 11:59:39',
                'updated_at' => '2022-06-21 11:59:39',
            ),
            5 => 
            array (
                'id' => 'd8448cad-5295-4ece-ac9c-dd1cedf9eeaa',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => 'PPK',
                'jumlah_anggaran' => 23250000,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'status' => 'Disetujui',
                'created_at' => '2022-06-21 11:47:35',
                'updated_at' => '2022-06-21 11:47:35',
            ),
        ));
        
        
    }
}