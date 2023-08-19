<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DokumenSppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dokumen_spp_ls')->delete();
        
        \DB::table('dokumen_spp_ls')->insert(array (
            0 => 
            array (
                'id' => 1,
            'nama_dokumen' => 'Billing (PPN & PPh) yang aktif',
                'dokumen' => 'billing-ppn-pph-yang-aktif-12023081907486456.pdf',
                'spp_ls_id' => '35df1ca2-c222-40a7-aba7-9cf1f010b966',
                'created_at' => '2023-08-19 07:47:48',
                'updated_at' => '2023-08-19 07:47:48',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Fotocopy NPWP',
                'dokumen' => 'fotocopy-npwp-2202308190748250.pdf',
                'spp_ls_id' => '35df1ca2-c222-40a7-aba7-9cf1f010b966',
                'created_at' => '2023-08-19 07:47:48',
                'updated_at' => '2023-08-19 07:47:48',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_dokumen' => 'Fotocopy Rekening Koran',
                'dokumen' => 'fotocopy-rekening-koran-32023081907483700.pdf',
                'spp_ls_id' => '35df1ca2-c222-40a7-aba7-9cf1f010b966',
                'created_at' => '2023-08-19 07:47:48',
                'updated_at' => '2023-08-19 07:47:48',
            ),
        ));
        
        
    }
}