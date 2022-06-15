<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KegiatanTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kegiatan')->delete();
        
        \DB::table('kegiatan')->insert(array (
            0 => 
            array (
                'id' => '008cd974-674b-4c68-ac0b-3e7192339956',
                'program_id' => 'eda52000-a895-4ebb-9e17-9952b2c084f3',
                'nama' => 'Pelaksanaan Tugas Pemerintah',
                'no_rek' => '4.01.03.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            1 => 
            array (
                'id' => '04ddbb18-fdef-4e83-bfe1-cb57e4031377',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Administrasi Barang Milik Daerah pada Perangkat Daerah',
                'no_rek' => '4.01.01.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            2 => 
            array (
                'id' => '1b5a2ee3-2c4e-4649-a5a4-292571509ba3',
                'program_id' => '524719e3-13b9-4228-9409-22093e3e3b9e',
                'nama' => 'Fasilitasi Kelembagaan dan Analisis Jabatan',
                'no_rek' => '4.01.02.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            3 => 
            array (
                'id' => '2e227248-8eb6-46b4-86c7-663664be12f3',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'fasilitasi Kerumahtanggaan Sekretariat Daerah',
                'no_rek' => '4.01.01.1.12',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            4 => 
            array (
                'id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Perencanaan, Pengganggaran dan Evalusi Kinerja perangkat Daerah',
                'no_rek' => '4.01.01.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            5 => 
            array (
                'id' => '36352ee8-acb3-4157-81ba-20f9091705dc',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Penyediaan Jasa Penunjang Urusan Pemerintahan Daerah',
                'no_rek' => '4.01.01.1.08',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            6 => 
            array (
                'id' => '45968532-7800-40f1-a0bc-3c3e378c2c2a',
                'program_id' => '987af6cd-4e2a-487a-9752-4e97ce4d554c',
                'nama' => 'Pengelolaan Kebijakan dan Koordinasi Perekonomian',
                'no_rek' => '4.01.06.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            7 => 
            array (
                'id' => '4d1f6d8f-b097-4a8c-bdfe-fba4dcb6a811',
                'program_id' => '987af6cd-4e2a-487a-9752-4e97ce4d554c',
                'nama' => 'Fasilitasi Bantuan Hukum',
                'no_rek' => '4.01.05.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            8 => 
            array (
                'id' => '53d74cba-eab9-4e8a-9462-ed70163c562a',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Administrasi Kepegawaian Perangkat Daerah',
                'no_rek' => '4.01.01.1.05',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            9 => 
            array (
                'id' => '675131ca-9136-42de-bf0b-54eb5ebca75a',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Pengadaan barang Milik daerah Penunjang Urusan Pemerintah Daerah',
                'no_rek' => '4.01.01.1.07',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            10 => 
            array (
                'id' => '687885b5-8629-4a85-9cf1-3b5641f48ff8',
                'program_id' => 'eda52000-a895-4ebb-9e17-9952b2c084f3',
                'nama' => 'fasilitasi Kerjasama Daerah',
                'no_rek' => '4.01.03.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            11 => 
            array (
                'id' => '68f3a5e3-f710-4150-bf16-9b0cd23b8890',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Fasilitasi Keprotokolan',
                'no_rek' => '4.01.01.1.14',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            12 => 
            array (
                'id' => '6f65e9cd-9f6c-44a6-8012-f3ed4341cd96',
                'program_id' => '30c590e0-18a8-47a0-bb23-2b455361470a',
                'nama' => 'Pengelolaan Pengadaan Barang Dan Jasa',
                'no_rek' => '4.01.07.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            13 => 
            array (
                'id' => '70693b8b-fd16-4fa4-bb4b-a411a190493f',
                'program_id' => '30c590e0-18a8-47a0-bb23-2b455361470a',
                'nama' => 'Pembinaan dan Advokasi Pengadaan Barang dan Jasa',
                'no_rek' => '4.01.07.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            14 => 
            array (
                'id' => '751737c4-f8da-4e95-af93-0d183f306ed9',
                'program_id' => '23f59723-7c80-4e1f-8f3e-fc569ebdbbfd',
                'nama' => 'fasilitasi Pengembangan Kesejahteraan Rakyat Pelayanan Dasar',
                'no_rek' => '4.01.04.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            15 => 
            array (
                'id' => '7fd723e8-04a4-4e9e-87e4-58e5ec1dc350',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Administrasi  Umum Perangkat Daerah',
                'no_rek' => '4.01.01.1.06',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            16 => 
            array (
                'id' => '89d00770-3a1b-472f-b53c-2b8045ef5839',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Fasilitasi Materi dan Komunikasi Pimpinan',
                'no_rek' => '4.01.01.1.13',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            17 => 
            array (
                'id' => '93e605d7-254e-4deb-8045-5ab64e90af5b',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Pemeliharaan Barang Milik Daerah Penunjang Urusan Pemerintahan Daerah',
                'no_rek' => '4.01.01.1.09',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            18 => 
            array (
                'id' => '9c5fc64a-831a-4998-9ec5-f45c4cca5dd4',
                'program_id' => '987af6cd-4e2a-487a-9752-4e97ce4d554c',
                'nama' => 'Pengelolaan Kebijakan dan Koordinasi BUMD dan BLUD',
                'no_rek' => '4.01.06.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            19 => 
            array (
                'id' => 'b51519c5-d276-4fe2-a25c-644bf96205a9',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Administrasi Keuangan Perangkat Daerah',
                'no_rek' => '4.01.01.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            20 => 
            array (
                'id' => 'b58fed40-8f0d-44f5-a061-cf812e49b37c',
                'program_id' => 'f81866f9-c407-4e4d-9da4-e3d50c69d48d',
                'nama' => 'Pengendalian Administrasi Pelaksanaan Pembanguna Daerah',
                'no_rek' => '4.01.08.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            21 => 
            array (
                'id' => 'ba5d8c12-8796-416c-b463-392a8a39e93b',
                'program_id' => '987af6cd-4e2a-487a-9752-4e97ce4d554c',
                'nama' => 'Pengelolaan Kebijakan dan Koordinasi Sumber Daya Alam',
                'no_rek' => '4.01.06.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            22 => 
            array (
                'id' => 'c23705cf-0f75-4eec-9e21-24ddc223624c',
                'program_id' => '524719e3-13b9-4228-9409-22093e3e3b9e',
                'nama' => 'Fasilitasi Reformasi Birokrasi dan Akuntabilitas Kinerja',
                'no_rek' => '4.01.02.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            23 => 
            array (
                'id' => 'c8aab468-55f6-470a-8c64-6f4827acb484',
                'program_id' => '23f59723-7c80-4e1f-8f3e-fc569ebdbbfd',
                'nama' => 'fasilitasi Pembinaan Mental Spritual',
                'no_rek' => '4.01.04.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            24 => 
            array (
                'id' => 'cc7bd67c-f066-4e45-913e-d75c3d23d42b',
                'program_id' => '23f59723-7c80-4e1f-8f3e-fc569ebdbbfd',
                'nama' => 'Fasilitasi Pengembangan Kesejahteraan Rakyat Non Pelayanan Dasar',
                'no_rek' => '4.01.04.1.03',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            25 => 
            array (
                'id' => 'd4b869dd-3b95-4bbd-8b05-2e85294e5012',
                'program_id' => '30c590e0-18a8-47a0-bb23-2b455361470a',
                'nama' => 'Pengelolaan Layanan Pengadaan Secara Elektronik',
                'no_rek' => '4.01.07.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            26 => 
            array (
                'id' => 'd99e34fe-43ac-4537-a17c-6851784f666e',
                'program_id' => 'b8c90913-1be3-4dfa-832c-06f68f03400c',
                'nama' => 'Fasilitasi Penyusunan Perundang-Undangan',
                'no_rek' => '4.01.05.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            27 => 
            array (
                'id' => 'e2a62997-27b2-486d-a465-bdd4cee2c29d',
                'program_id' => 'd48f919a-e846-4793-8c05-e26eca813cd9',
                'nama' => 'Administrasi Keuangan dan Operasional Kepala Daerah dan wakil Kepala Daerah',
                'no_rek' => '4.01.01.1.11',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            28 => 
            array (
                'id' => 'fb153f91-0250-480f-b287-dd4e6b9929b9',
                'program_id' => 'f81866f9-c407-4e4d-9da4-e3d50c69d48d',
                'nama' => 'Pengendalian Administrasi Pelaksanaan Pembanguna Daerah',
                'no_rek' => '4.01.08.1.01',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
            29 => 
            array (
                'id' => 'fe482d7f-ddae-4e50-ac6d-af083bb94376',
                'program_id' => 'eda52000-a895-4ebb-9e17-9952b2c084f3',
                'nama' => 'Pelaksanan Otonomi Daerah',
                'no_rek' => '4.01.03.1.02',
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:06:39',
                'updated_at' => '2022-06-13 08:06:39',
            ),
        ));
        
        
    }
}