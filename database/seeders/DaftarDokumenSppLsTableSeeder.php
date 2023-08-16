<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DaftarDokumenSppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('daftar_dokumen_spp_ls')->delete();
        
        \DB::table('daftar_dokumen_spp_ls')->insert(array (
            0 => 
            array (
                'id' => '4f28e03c-99c5-4d3e-b2d4-21291e513f62',
                'nama' => 'RTGS',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:26:01',
                'updated_at' => '2023-08-16 15:26:01',
            ),
            1 => 
            array (
                'id' => '58c7d395-5ef6-4355-948d-f6b0320ee4d1',
                'nama' => 'Rekap Pajak Per Golongan',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:25:54',
                'updated_at' => '2023-08-16 15:25:54',
            ),
            2 => 
            array (
                'id' => '5bd259bf-2e1b-4ac9-b6d8-d1952db8b9ce',
                'nama' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:24:54',
                'updated_at' => '2023-08-16 15:24:54',
            ),
            3 => 
            array (
                'id' => '617ecb7f-eb8c-4bc6-a528-1cdb17f1c58e',
                'nama' => 'Surat Pernyataan Pengajuan SPM LS',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:24:27',
                'updated_at' => '2023-08-16 15:24:27',
            ),
            4 => 
            array (
                'id' => 'dac44419-15dc-4523-8940-477e9e4f84db',
                'nama' => 'Surat Pernyataan Sumber Dana',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:25:02',
                'updated_at' => '2023-08-16 15:25:02',
            ),
            5 => 
            array (
                'id' => 'e2d771fa-3057-4947-b388-ebcb887945c1',
                'nama' => 'Surat Pernyataan Pengajuan SPP LS',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:24:41',
                'updated_at' => '2023-08-16 15:24:41',
            ),
            6 => 
            array (
                'id' => 'efa740c5-e579-46eb-ba21-3d3ffcd2eabf',
                'nama' => 'Fotocopy Rekening Koran',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:25:46',
                'updated_at' => '2023-08-16 15:25:46',
            ),
            7 => 
            array (
                'id' => 'fa92311d-bfe8-4598-acd0-9019ed8f00f2',
            'nama' => 'Billing (PPN & PPh) yang aktif',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:25:24',
                'updated_at' => '2023-08-16 15:25:24',
            ),
            8 => 
            array (
                'id' => 'fd902252-ef96-4694-b50f-ff30b3aee991',
                'nama' => 'Fotocopy NPWP',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:25:37',
                'updated_at' => '2023-08-16 15:25:37',
            ),
        ));
        
        
    }
}