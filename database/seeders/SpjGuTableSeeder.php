<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SpjGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('spj_gu')->delete();
        
        \DB::table('spj_gu')->insert(array (
            0 => 
            array (
                'id' => '08494017-2227-4288-9eb0-c478dfd8992b',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'bulan' => 'Februari',
                'nomor_surat' => 'SPJ-GU-2',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 1,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 1,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => '2023-08-28',
                'tanggal_validasi_ppk' => '2023-08-28',
                'status_validasi_akhir' => 1,
                'tanggal_validasi_akhir' => '2023-08-28',
                'created_at' => '2023-08-28 13:45:56',
                'updated_at' => '2023-08-28 21:46:09',
            ),
            1 => 
            array (
                'id' => '50d65814-7434-4cb0-b33e-1db3500f7ee0',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'bulan' => 'Maret',
                'nomor_surat' => 'SPJ-GU-1',
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
                'created_at' => '2023-08-28 13:45:26',
                'updated_at' => '2023-08-28 13:45:26',
            ),
            2 => 
            array (
                'id' => '6940b9ba-b841-403e-aff1-64cd11c44a96',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'bulan' => 'Januari',
                'nomor_surat' => 'SPP-GU-3-BPOD',
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
                'created_at' => '2023-08-28 13:36:59',
                'updated_at' => '2023-08-28 13:36:59',
            ),
            3 => 
            array (
                'id' => '77a82ebf-fe61-433a-9139-d7e9ae084258',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 1,
                'bulan' => 'Januari',
                'nomor_surat' => 'SPJ-GU-3',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 1,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 1,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => '2023-08-28',
                'tanggal_validasi_ppk' => '2023-08-28',
                'status_validasi_akhir' => 1,
                'tanggal_validasi_akhir' => '2023-08-28',
                'created_at' => '2023-08-28 13:46:26',
                'updated_at' => '2023-08-28 21:46:02',
            ),
            4 => 
            array (
                'id' => '8daf5060-2026-460a-9696-7b08c4516bf4',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'bulan' => 'Maret',
                'nomor_surat' => 'SPP-GU-1-BPOD',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 1,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 1,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => '2023-08-28',
                'tanggal_validasi_ppk' => '2023-08-28',
                'status_validasi_akhir' => 1,
                'tanggal_validasi_akhir' => '2023-08-28',
                'created_at' => '2023-08-28 13:32:19',
                'updated_at' => '2023-08-28 21:48:15',
            ),
            5 => 
            array (
                'id' => 'b33ee4b7-e4b1-4ba1-a26e-8e9ec315f704',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'tahap_riwayat' => 1,
                'bulan' => 'Februari',
                'nomor_surat' => 'SPP-GU-2-BPOD',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status_validasi_asn' => 1,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 1,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => '2023-08-28',
                'tanggal_validasi_ppk' => '2023-08-28',
                'status_validasi_akhir' => 1,
                'tanggal_validasi_akhir' => '2023-08-28',
                'created_at' => '2023-08-28 13:36:10',
                'updated_at' => '2023-08-28 21:47:51',
            ),
        ));
        
        
    }
}