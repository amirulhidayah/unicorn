<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('spp_ls')->delete();
        
        \DB::table('spp_ls')->insert(array (
            0 => 
            array (
                'id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'kategori_spp_ls_id' => '518dc47f-32fb-4304-960f-942780d2f092',
                'bulan' => 'Januari',
                'nomor_surat' => 'SPP-LS-3-BPOD',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
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
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            1 => 
            array (
                'id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'kategori_spp_ls_id' => '518dc47f-32fb-4304-960f-942780d2f091',
                'bulan' => 'Maret',
                'nomor_surat' => 'SPP-LS-3-BPOD',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
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
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            2 => 
            array (
                'id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'kategori_spp_ls_id' => '518dc47f-32fb-4304-960f-942780d2f092',
                'bulan' => 'Februari',
                'nomor_surat' => 'SPP-LS-2-BPOD',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
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
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            3 => 
            array (
                'id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'kategori_spp_ls_id' => '518dc47f-32fb-4304-960f-942780d2f092',
                'bulan' => 'Maret',
                'nomor_surat' => 'SPP-LS-1-BPOD',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
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
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            4 => 
            array (
                'id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'kategori_spp_ls_id' => '518dc47f-32fb-4304-960f-942780d2f091',
                'bulan' => 'Januari',
                'nomor_surat' => 'SPP-LS-1',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
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
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            5 => 
            array (
                'id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'kategori_spp_ls_id' => '518dc47f-32fb-4304-960f-942780d2f091',
                'bulan' => 'Februari',
                'nomor_surat' => 'SPP-LS-2',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
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
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
        ));
        
        
    }
}