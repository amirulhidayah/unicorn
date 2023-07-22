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

        \DB::table('daftar_dokumen_spp_gu')->insert([
            [
                'id' => '01cbbd72-4597-4299-803a-135c2fb45c81',
                'nama' => 'Draf Surat Pernyataan',
                'kategori' => 'SPJ',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:30',
                'updated_at' => '2022-06-03 03:18:30',
            ],
            [
                'id' => '4ae088ec-a443-43fb-be12-856da00bdd22',
                'nama' => 'Ringkasan',
                'kategori' => 'SPJ',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:33',
                'updated_at' => '2022-06-03 03:17:33',
            ],
            [
                'id' => '5f473c47-8806-415b-b710-ac9b2b353ccf',
                'nama' => 'E-Biling',
                'kategori' => 'SPJ',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:39',
                'updated_at' => '2022-06-03 03:18:39',
            ],
            [
                'id' => '67e3fb51-a6d8-4e91-8dc2-22648648b782',
                'nama' => 'Salinan SPD',
                'kategori' => 'SPJ',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:08',
                'updated_at' => '2022-06-03 03:18:08',
            ],
            [
                'id' => '695bd904-0e33-4fa5-a39c-a3b6dcfe13e6',
                'nama' => 'Surat Pengantar',
                'kategori' => 'SPJ',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:26',
                'updated_at' => '2022-06-03 03:17:26',
            ],
            [
                'id' => '87a94a10-5638-4289-b5b6-8757cf1e2bdc',
                'nama' => 'Surat Pengesahan Laporan Pertanggungjawaban Bendahara Pengeluaran atas  Penggunaan Dana',
                'kategori' => 'SPJ',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:19',
                'updated_at' => '2022-06-03 03:18:19',
            ],
            [
                'id' => 'f7d15100-8e94-40a8-977a-9943ecd098d3',
                'nama' => 'Rincian',
                'kategori' => 'SPJ',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:41',
                'updated_at' => '2022-06-03 03:17:41',
            ],
        ]);

        \DB::table('daftar_dokumen_spp_gu')->insert([
            [
                'id' => '01cbbd72-4597-4299-803a-135c2fb45c82',
                'nama' => 'Draf Surat Pernyataan',
                'kategori' => 'SPP',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:30',
                'updated_at' => '2022-06-03 03:18:30',
            ],
            [
                'id' => '4ae088ec-a443-43fb-be12-856da00bdd23',
                'nama' => 'Ringkasan',
                'kategori' => 'SPP',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:33',
                'updated_at' => '2022-06-03 03:17:33',
            ],
            [
                'id' => '5f473c47-8806-415b-b710-ac9b2b353ccg',
                'nama' => 'E-Biling',
                'kategori' => 'SPP',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:39',
                'updated_at' => '2022-06-03 03:18:39',
            ],
            [
                'id' => '67e3fb51-a6d8-4e91-8dc2-22648648b783',
                'nama' => 'Salinan SPD',
                'kategori' => 'SPP',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:08',
                'updated_at' => '2022-06-03 03:18:08',
            ],
            [
                'id' => '695bd904-0e33-4fa5-a39c-a3b6dcfe13e7',
                'nama' => 'Surat Pengantar',
                'kategori' => 'SPP',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:26',
                'updated_at' => '2022-06-03 03:17:26',
            ],
            [
                'id' => '87a94a10-5638-4289-b5b6-8757cf1e2bdv',
                'nama' => 'Surat Pengesahan Laporan Pertanggungjawaban Bendahara Pengeluaran atas  Penggunaan Dana',
                'kategori' => 'SPP',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:18:19',
                'updated_at' => '2022-06-03 03:18:19',
            ],
            [
                'id' => 'f7d15100-8e94-40a8-977a-9943ecd098d4',
                'nama' => 'Rincian',
                'kategori' => 'SPP',
                'deleted_at' => NULL,
                'created_at' => '2022-06-03 03:17:41',
                'updated_at' => '2022-06-03 03:17:41',
            ],
        ]);
    }
}
