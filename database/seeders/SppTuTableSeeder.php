<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppTuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('spp_tu')->delete();
        
        \DB::table('spp_tu')->insert(array (
            0 => 
            array (
                'id' => '0f436b7b-d61d-4ad7-8c0e-58741122e340',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'bulan' => 'Januari',
                'nomor_surat' => 'SPP-TU-1-BPOD',
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
                'created_at' => '2023-08-28 04:41:40',
                'updated_at' => '2023-08-28 04:41:40',
            ),
            1 => 
            array (
                'id' => '3cf32643-8001-4b99-83f8-daa959a3ee25',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'bulan' => 'Februari',
                'nomor_surat' => 'SPP-TU-2-BPOD',
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
                'created_at' => '2023-08-28 04:42:31',
                'updated_at' => '2023-08-28 04:42:31',
            ),
            2 => 
            array (
                'id' => '42cf23fe-96ee-454f-8e9e-0e01d542b244',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'bulan' => 'Februari',
                'nomor_surat' => 'SPP-TU-2',
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
                'created_at' => '2023-08-28 04:51:29',
                'updated_at' => '2023-08-28 04:51:29',
            ),
            3 => 
            array (
                'id' => '5c45acad-5f8a-4f86-908b-7f272b8f96f6',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'bulan' => 'Januari',
                'nomor_surat' => 'SPP-TU-1',
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
                'created_at' => '2023-08-28 04:50:23',
                'updated_at' => '2023-08-28 04:50:23',
            ),
            4 => 
            array (
                'id' => '8dc0f0be-df1f-4f00-8a8f-d3093b643b72',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'bulan' => 'Maret',
                'nomor_surat' => 'SPP-TU-3',
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
                'created_at' => '2023-08-28 04:52:17',
                'updated_at' => '2023-08-28 04:52:17',
            ),
            5 => 
            array (
                'id' => 'ee50dfca-c463-4362-8f32-d2786e54bd27',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'bulan' => 'Maret',
                'nomor_surat' => 'SPP-TU-3-BPOD',
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
                'created_at' => '2023-08-28 04:43:28',
                'updated_at' => '2023-08-28 04:43:28',
            ),
        ));
        
        
    }
}