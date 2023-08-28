<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('spp_gu')->delete();
        
        \DB::table('spp_gu')->insert(array (
            0 => 
            array (
                'id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'nomor_surat' => 'SPP-GU-2',
                'spj_gu_id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'tahap_riwayat' => 1,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 0,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 0,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => NULL,
                'tanggal_validasi_ppk' => NULL,
                'status_validasi_akhir' => 0,
                'tanggal_validasi_akhir' => NULL,
                'dokumen_spm' => NULL,
                'dokumen_arsip_sp2d' => NULL,
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            1 => 
            array (
                'id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'nomor_surat' => 'SPP-GU-2-BPOD',
                'spj_gu_id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'tahap_riwayat' => 1,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 0,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 0,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => NULL,
                'tanggal_validasi_ppk' => NULL,
                'status_validasi_akhir' => 0,
                'tanggal_validasi_akhir' => NULL,
                'dokumen_spm' => NULL,
                'dokumen_arsip_sp2d' => NULL,
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            2 => 
            array (
                'id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'nomor_surat' => 'SPP-GU-1-BPOD',
                'spj_gu_id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'tahap_riwayat' => 1,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 0,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 0,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => NULL,
                'tanggal_validasi_ppk' => NULL,
                'status_validasi_akhir' => 0,
                'tanggal_validasi_akhir' => NULL,
                'dokumen_spm' => NULL,
                'dokumen_arsip_sp2d' => NULL,
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            3 => 
            array (
                'id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'nomor_surat' => 'SPP-GU-1-BPOD',
                'spj_gu_id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'tahap_riwayat' => 1,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 0,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 0,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => NULL,
                'tanggal_validasi_ppk' => NULL,
                'status_validasi_akhir' => 0,
                'tanggal_validasi_akhir' => NULL,
                'dokumen_spm' => NULL,
                'dokumen_arsip_sp2d' => NULL,
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
        ));
        
        
    }
}