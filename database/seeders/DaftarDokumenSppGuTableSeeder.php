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
                'id' => '01cbbd72-4597-4299-803a-135c2fb45c81',
                'nama' => 'Draf Surat Pernyataan',
                'kategori' => 'Akhir',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:30',
                'updated_at' => '2022-06-03 03:18:30',
            ),
            1 => 
            array (
                'id' => '02f28660-028a-4f2e-a46f-b6dfad20a358',
                'nama' => 'Bukti Transaksi 1',
                'kategori' => 'Awal',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:16:57',
                'updated_at' => '2022-06-03 03:16:57',
            ),
            2 => 
            array (
                'id' => '4ae088ec-a443-43fb-be12-856da00bdd22',
                'nama' => 'Ringkasan',
                'kategori' => 'Akhir',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:33',
                'updated_at' => '2022-06-03 03:17:33',
            ),
            3 => 
            array (
                'id' => '5f473c47-8806-415b-b710-ac9b2b353ccf',
                'nama' => 'E-Biling',
                'kategori' => 'Akhir',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:39',
                'updated_at' => '2022-06-03 03:18:39',
            ),
            4 => 
            array (
                'id' => '67e3fb51-a6d8-4e91-8dc2-22648648b782',
                'nama' => 'Salinan SPD',
                'kategori' => 'Akhir',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:08',
                'updated_at' => '2022-06-03 03:18:08',
            ),
            5 => 
            array (
                'id' => '695bd904-0e33-4fa5-a39c-a3b6dcfe13e6',
                'nama' => 'Surat Pengantar',
                'kategori' => 'Akhir',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:26',
                'updated_at' => '2022-06-03 03:17:26',
            ),
            6 => 
            array (
                'id' => '785d2032-3f5a-4de7-9e38-84c0e031e2a8',
                'nama' => 'Bukti Transaksi 2',
                'kategori' => 'Awal',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:09',
                'updated_at' => '2022-06-03 03:17:09',
            ),
            7 => 
            array (
                'id' => '87a94a10-5638-4289-b5b6-8757cf1e2bdc',
                'nama' => 'Surat Pengesahan Laporan Pertanggungjawaban Bendahara Pengeluaran atas  Penggunaan Dana',
                'kategori' => 'Akhir',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:19',
                'updated_at' => '2022-06-03 03:18:19',
            ),
            8 => 
            array (
                'id' => 'b2dc12d3-6bd5-42f1-90ca-33b961c91849',
                'nama' => 'Bukti Transaksi 3',
                'kategori' => 'Awal',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:14',
                'updated_at' => '2022-06-03 03:17:14',
            ),
            9 => 
            array (
                'id' => 'f7d15100-8e94-40a8-977a-9943ecd098d3',
                'nama' => 'Rincian',
                'kategori' => 'Akhir',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:41',
                'updated_at' => '2022-06-03 03:17:41',
            ),
        ));
        
        
    }
}