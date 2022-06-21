<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DokumenSppTuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dokumen_spp_tu')->delete();
        
        \DB::table('dokumen_spp_tu')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama_dokumen' => 'Surat Pengantar',
                'dokumen' => 'surat-pengantar-02022062111392715.pdf',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'created_at' => '2022-06-21 11:45:23',
                'updated_at' => '2022-06-21 11:59:39',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062111237951.pdf',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'created_at' => '2022-06-21 11:45:23',
                'updated_at' => '2022-06-21 11:45:23',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_dokumen' => 'Bukti Pembayaran',
                'dokumen' => 'bukti-pembayaran-12022062111395194.pdf',
                'spp_tu_id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'created_at' => '2022-06-21 11:45:23',
                'updated_at' => '2022-06-21 11:59:39',
            ),
        ));
        
        
    }
}