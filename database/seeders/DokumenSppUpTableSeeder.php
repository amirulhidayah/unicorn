<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DokumenSppUpTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dokumen_spp_up')->delete();
        
        \DB::table('dokumen_spp_up')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama_dokumen' => 'Surat Pengantar',
                'dokumen' => 'surat-pengantar-02022062111584573.pdf',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'created_at' => '2022-06-21 11:43:58',
                'updated_at' => '2022-06-21 11:43:58',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062111585277.pdf',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'created_at' => '2022-06-21 11:43:58',
                'updated_at' => '2022-06-21 11:43:58',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_dokumen' => 'Salinan SPD',
                'dokumen' => 'salinan-spd-22022062111588561.pdf',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'created_at' => '2022-06-21 11:43:58',
                'updated_at' => '2022-06-21 11:43:58',
            ),
            3 => 
            array (
                'id' => 4,
                'nama_dokumen' => 'Surat Permohonan Besaran UP',
                'dokumen' => 'surat-permohonan-besaran-up-32022062111588589.pdf',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'created_at' => '2022-06-21 11:43:58',
                'updated_at' => '2022-06-21 11:43:58',
            ),
            4 => 
            array (
                'id' => 5,
                'nama_dokumen' => 'Surat Terima',
                'dokumen' => 'surat-terima-02022062111514531.pdf',
                'spp_up_id' => 'dafdbff1-bb0a-4b18-b9f9-5434dc1030c8',
                'created_at' => '2022-06-21 11:58:51',
                'updated_at' => '2022-06-21 11:58:51',
            ),
        ));
        
        
    }
}