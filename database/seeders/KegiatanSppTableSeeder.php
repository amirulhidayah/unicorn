<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KegiatanSppTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kegiatan_spp')->delete();
        
        \DB::table('kegiatan_spp')->insert(array (
            0 => 
            array (
                'id' => '13c23e7f-e3b8-49cc-b4b1-eca1b27ee979',
                'program_spp_id' => '49c49c80-d4c2-4c8b-a7af-f013006293a2',
                'nama' => 'Pengelolaan Layanan Pengadaan Secara Elektronik',
                'no_rek' => '4.01.07.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            1 => 
            array (
                'id' => '14b91685-bb48-4e59-93d6-2e0079950e0f',
                'program_spp_id' => 'b2c4d604-3367-4af7-8cae-7b180b454334',
                'nama' => 'Pelaksanan Otonomi Daerah',
                'no_rek' => '4.01.03.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            2 => 
            array (
                'id' => '16e86782-6591-4aa8-ae32-e42832eeb50a',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Pengadaan barang Milik daerah Penunjang Urusan Pemerintah Daerah',
                'no_rek' => '4.01.01.1.07',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            3 => 
            array (
                'id' => '1b4da8d7-afdb-41a5-9282-7e765a2ed4f2',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'fasilitasi Kerumahtanggaan Sekretariat Daerah',
                'no_rek' => '4.01.01.1.12',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            4 => 
            array (
                'id' => '20d8969f-bb98-4b6f-a885-5d5559887c38',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Penyediaan Jasa Penunjang Urusan Pemerintahan Daerah',
                'no_rek' => '4.01.01.1.08',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            5 => 
            array (
                'id' => '20fdc81e-b34c-46d3-bd1b-7947b91ebc9e',
                'program_spp_id' => 'f958b108-21cc-4806-9aca-b6bcd6aae21c',
                'nama' => 'Fasilitasi Kelembagaan dan Analisis Jabatan',
                'no_rek' => '4.01.02.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            6 => 
            array (
                'id' => '2167b2b1-a73d-45c9-9bc3-f69813e14708',
                'program_spp_id' => '6d7c67a2-ff15-491e-b536-07ae6d15af00',
                'nama' => 'fasilitasi Pembinaan Mental Spritual',
                'no_rek' => '4.01.04.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            7 => 
            array (
                'id' => '27ab6b84-1495-4213-ab8e-842ff8849f03',
                'program_spp_id' => '73f2b481-1a56-445b-8bf5-6dfd86d6d590',
                'nama' => 'Pengelolaan Kebijakan dan Koordinasi BUMD dan BLUD',
                'no_rek' => '4.01.06.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            8 => 
            array (
                'id' => '324f159e-3927-4d2b-9190-d45ab5010a92',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Administrasi Keuangan dan Operasional Kepala Daerah dan wakil Kepala Daerah',
                'no_rek' => '4.01.01.1.11',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            9 => 
            array (
                'id' => '35849f8e-3811-4f59-8802-c1211bd66671',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Fasilitasi Keprotokolan',
                'no_rek' => '4.01.01.1.14',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            10 => 
            array (
                'id' => '4d4d7799-5b53-4e77-8794-5d06ce55d433',
                'program_spp_id' => '49c49c80-d4c2-4c8b-a7af-f013006293a2',
                'nama' => 'Pembinaan dan Advokasi Pengadaan Barang dan Jasa',
                'no_rek' => '4.01.07.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            11 => 
            array (
                'id' => '64933d02-72e4-4aca-a103-884e73ceb9e5',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Administrasi Keuangan Perangkat Daerah',
                'no_rek' => '4.01.01.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            12 => 
            array (
                'id' => '7829cb1f-6c4b-495e-8b1e-d305febcd892',
                'program_spp_id' => '2ce27d47-68a7-43b1-b680-835d2a779d65',
                'nama' => 'Pengendalian Administrasi Pelaksanaan Pembanguna Daerah',
                'no_rek' => '4.01.08.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            13 => 
            array (
                'id' => '7b4cbba6-fb4c-412f-9913-0b1aa9a0c55e',
                'program_spp_id' => '73f2b481-1a56-445b-8bf5-6dfd86d6d590',
                'nama' => 'Pengelolaan Kebijakan dan Koordinasi Perekonomian',
                'no_rek' => '4.01.06.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            14 => 
            array (
                'id' => '7de8f434-5715-4fe8-8400-ec1987c09e8e',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Administrasi Kepegawaian Perangkat Daerah',
                'no_rek' => '4.01.01.1.05',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            15 => 
            array (
                'id' => '9c3b72e1-c1d6-4e27-90ac-e7764a0b3e64',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Pemeliharaan Barang Milik Daerah Penunjang Urusan Pemerintahan Daerah',
                'no_rek' => '4.01.01.1.09',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            16 => 
            array (
                'id' => '9e0208e1-6724-499a-84ec-cc36f79260fb',
                'program_spp_id' => '49c49c80-d4c2-4c8b-a7af-f013006293a2',
                'nama' => 'Pengelolaan Pengadaan Barang Dan Jasa',
                'no_rek' => '4.01.07.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            17 => 
            array (
                'id' => 'a40d6067-c651-4d0b-8596-80f1f6538c8c',
                'program_spp_id' => '73f2b481-1a56-445b-8bf5-6dfd86d6d590',
                'nama' => 'Pengelolaan Kebijakan dan Koordinasi Sumber Daya Alam',
                'no_rek' => '4.01.06.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            18 => 
            array (
                'id' => 'b2639019-3fe6-4cb2-aee8-84aa4db2f462',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Fasilitasi Materi dan Komunikasi Pimpinan',
                'no_rek' => '4.01.01.1.13',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            19 => 
            array (
                'id' => 'b93ecc74-27c7-471b-b2e7-bf37508c6d57',
                'program_spp_id' => 'b2c4d604-3367-4af7-8cae-7b180b454334',
                'nama' => 'fasilitasi Kerjasama Daerah',
                'no_rek' => '4.01.03.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            20 => 
            array (
                'id' => 'bd042dd5-bb6c-4423-b264-8c6c305b6a1e',
                'program_spp_id' => '73f2b481-1a56-445b-8bf5-6dfd86d6d590',
                'nama' => 'Fasilitasi Bantuan Hukum',
                'no_rek' => '4.01.05.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            21 => 
            array (
                'id' => 'c7fac6e9-8217-42f5-aab8-7579ed5b4474',
                'program_spp_id' => 'b2c4d604-3367-4af7-8cae-7b180b454334',
                'nama' => 'Pelaksanaan Tugas Pemerintah',
                'no_rek' => '4.01.03.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            22 => 
            array (
                'id' => 'cdfa19db-8450-4f7f-8bb1-1377775473f4',
                'program_spp_id' => 'f958b108-21cc-4806-9aca-b6bcd6aae21c',
                'nama' => 'Fasilitasi Reformasi Birokrasi dan Akuntabilitas Kinerja',
                'no_rek' => '4.01.02.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            23 => 
            array (
                'id' => 'd081a871-3bc2-4b50-bd8c-9b855b5a39a2',
                'program_spp_id' => '6d7c67a2-ff15-491e-b536-07ae6d15af00',
                'nama' => 'Fasilitasi Pengembangan Kesejahteraan Rakyat Non Pelayanan Dasar',
                'no_rek' => '4.01.04.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            24 => 
            array (
                'id' => 'd32dae6a-d730-46e4-9aac-955547cce306',
                'program_spp_id' => '6d7c67a2-ff15-491e-b536-07ae6d15af00',
                'nama' => 'fasilitasi Pengembangan Kesejahteraan Rakyat Pelayanan Dasar',
                'no_rek' => '4.01.04.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            25 => 
            array (
                'id' => 'e833b517-8f30-4833-92c5-aa28255e7507',
                'program_spp_id' => '2ce27d47-68a7-43b1-b680-835d2a779d65',
                'nama' => 'Pengendalian Administrasi Pelaksanaan Pembanguna Daerah',
                'no_rek' => '4.01.08.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            26 => 
            array (
                'id' => 'ed2c4164-36e9-4dd7-a948-2567042f8953',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Administrasi  Umum Perangkat Daerah',
                'no_rek' => '4.01.01.1.06',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            27 => 
            array (
                'id' => 'effb02ce-87f4-4658-9e3f-47b75ac377c8',
                'program_spp_id' => 'bbb82ca8-a204-47e4-957f-9bb5860bb902',
                'nama' => 'Fasilitasi Penyusunan Perundang-Undangan',
                'no_rek' => '4.01.05.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            28 => 
            array (
                'id' => 'f408e460-42a6-429c-92c6-73b3f8529939',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Perencanaan, Pengganggaran dan Evalusi Kinerja perangkat Daerah',
                'no_rek' => '4.01.01.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            29 => 
            array (
                'id' => 'fbbf6f10-5313-44e0-b387-f826326c6d41',
                'program_spp_id' => '568f6f1d-82b6-4e6f-a66d-7e46b0f3084f',
                'nama' => 'Administrasi Barang Milik Daerah pada Perangkat Daerah',
                'no_rek' => '4.01.01.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
        ));
        
        
    }
}