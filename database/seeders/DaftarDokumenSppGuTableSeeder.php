<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DaftarDokumenSppGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('daftar_dokumen_spp_gu')->delete();
        
        \DB::table('daftar_dokumen_spp_gu')->insert(array (
            0 => 
            array (
                'id' => '17b29fdb-3324-487f-b360-f16a3908e34c',
                'nama' => 'Bukti Lunas Pajak',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:28:39',
                'updated_at' => '2023-08-16 15:28:39',
            ),
            1 => 
            array (
                'id' => '1ef9f636-9b17-4364-96b0-4e19a59aa6f2',
                'nama' => 'Surat Pernyataan Pengajuan SPP GU',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:27:55',
                'updated_at' => '2023-08-16 15:27:55',
            ),
            2 => 
            array (
                'id' => '270c9894-bc51-48dc-b8b5-64af5631a9f4',
                'nama' => 'Laporan Pertanggung Jawaban GU',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:27:32',
                'updated_at' => '2023-08-16 15:27:32',
            ),
            3 => 
            array (
                'id' => '666280d6-18be-4dec-9d9e-51928c900542',
                'nama' => 'Rekap Pajak',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:28:30',
                'updated_at' => '2023-08-16 15:28:30',
            ),
            4 => 
            array (
                'id' => '90142e65-660f-4deb-82df-65e82d625323',
                'nama' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:28:12',
                'updated_at' => '2023-08-16 15:28:12',
            ),
            5 => 
            array (
                'id' => 'bb92b0f8-d5da-4e57-851d-9d26d69244f2',
                'nama' => 'Surat Pernyataan Pengajuan SPM GU',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:27:18',
                'updated_at' => '2023-08-16 15:27:18',
            ),
            6 => 
            array (
                'id' => 'd3bd75fc-2257-4ed2-b2b9-20cf77e58ca4',
                'nama' => 'Surat pernyataan Sumber Dana',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:28:24',
                'updated_at' => '2023-08-16 15:28:24',
            ),
            7 => 
            array (
                'id' => 'e6daaeec-7669-4194-b9b9-2a2fd3251902',
                'nama' => 'Pengesahan Pertanggung Jawaban Bendahara',
                'deleted_at' => NULL,
                'created_at' => '2023-08-16 15:27:44',
                'updated_at' => '2023-08-16 15:27:44',
            ),
        ));
        
        
    }
}