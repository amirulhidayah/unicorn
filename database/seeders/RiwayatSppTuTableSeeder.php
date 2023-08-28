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
                'id' => '0870e87e-2eac-407b-8848-c7555eaadee1',
                'spp_tu_id' => '5c45acad-5f8a-4f86-908b-7f272b8f96f6',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 04:50:23',
                'updated_at' => '2023-08-28 04:50:23',
            ),
            1 => 
            array (
                'id' => '1dfee0f7-1c73-4393-b475-e7526fe2539b',
                'spp_tu_id' => '3cf32643-8001-4b99-83f8-daa959a3ee25',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 04:42:31',
                'updated_at' => '2023-08-28 04:42:31',
            ),
            2 => 
            array (
                'id' => '3224d5e9-6d47-49c2-83e2-5caffffaf398',
                'spp_tu_id' => 'ee50dfca-c463-4362-8f32-d2786e54bd27',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 04:43:28',
                'updated_at' => '2023-08-28 04:43:28',
            ),
            3 => 
            array (
                'id' => '63e1c7c5-117b-4c28-b516-cede0a7ad55f',
                'spp_tu_id' => '42cf23fe-96ee-454f-8e9e-0e01d542b244',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 04:51:29',
                'updated_at' => '2023-08-28 04:51:29',
            ),
            4 => 
            array (
                'id' => '6f69c238-20e1-431e-9931-a5a3a06065f2',
                'spp_tu_id' => '0f436b7b-d61d-4ad7-8c0e-58741122e340',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 04:41:40',
                'updated_at' => '2023-08-28 04:41:40',
            ),
            5 => 
            array (
                'id' => '7946e037-3587-4164-9890-b747480d51ab',
                'spp_tu_id' => '8dc0f0be-df1f-4f00-8a8f-d3093b643b72',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'tahap_riwayat' => 1,
                'nomor_surat' => NULL,
                'role' => NULL,
                'alasan' => NULL,
                'surat_penolakan' => NULL,
                'surat_pengembalian' => NULL,
                'status' => 'Dibuat',
                'created_at' => '2023-08-28 04:52:17',
                'updated_at' => '2023-08-28 04:52:17',
            ),
        ));
        
        
    }
}