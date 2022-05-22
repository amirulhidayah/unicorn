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
                'id' => '03c4cd96-4ba2-47a6-a133-0e842ec6e204',
                'nama' => 'Kwitansi Bermaterai Cukup',
                'kategori' => 'Belanja Hibah',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:53:07',
                'updated_at' => '2022-05-17 02:53:07',
            ),
            1 => 
            array (
                'id' => '09f79e40-a7b5-4774-9c91-dc43e3d9585e',
                'nama' => 'Berita Acara Pembayaran',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:38:35',
                'updated_at' => '2022-05-17 02:38:35',
            ),
            2 => 
            array (
                'id' => '0a2cfa58-2a48-4047-bacd-7399b679c4d3',
                'nama' => 'Berita Acara Pemeriksaan',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:39:16',
                'updated_at' => '2022-05-17 02:39:16',
            ),
            3 => 
            array (
                'id' => '10180e0b-22c9-4bae-bdbc-5c3cabd65dd5',
                'nama' => 'Dokumen Administrasi',
                'kategori' => 'Belanja Hibah',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:53:54',
                'updated_at' => '2022-05-17 02:53:54',
            ),
            4 => 
            array (
                'id' => '11e0ff64-5198-4f31-ae3e-026cca9110a8',
                'nama' => 'Foto/Buku/Dokumentasi tingkat Kemajuan / Penyelesaian Pekerjaan',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:39:56',
                'updated_at' => '2022-05-17 02:39:56',
            ),
            5 => 
            array (
                'id' => '181e07ba-e03a-48e6-a990-c5d192d13def',
                'nama' => 'Potongan Jamsostek',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:40:11',
                'updated_at' => '2022-05-17 02:40:11',
            ),
            6 => 
            array (
                'id' => '2454c328-594a-4fc6-b244-94b7143b30f8',
                'nama' => 'Surat Pemberitahuan Potongan Denda Keterlambatan Pekerjaan',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:39:44',
                'updated_at' => '2022-05-17 02:39:44',
            ),
            7 => 
            array (
                'id' => '46ecd732-f9ef-49a1-ba5a-eaf933ea6529',
                'nama' => 'Rincian',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:37:03',
                'updated_at' => '2022-05-17 02:37:03',
            ),
            8 => 
            array (
                'id' => '477d1244-2aa8-457a-8c67-4bb3b005080c',
                'nama' => 'Salinan Surat Rekomendasi',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:37:35',
                'updated_at' => '2022-05-17 02:37:35',
            ),
            9 => 
            array (
                'id' => '4a655511-33a2-41ef-97c0-8f2371f35a41',
                'nama' => 'NPHD',
                'kategori' => 'Belanja Hibah',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:52:02',
                'updated_at' => '2022-05-17 02:52:02',
            ),
            10 => 
            array (
                'id' => '5c8d45ef-e637-422d-909d-388b409f6583',
                'nama' => 'Fotokopi Rekening Bank',
                'kategori' => 'Belanja Hibah',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:52:35',
                'updated_at' => '2022-05-17 02:52:35',
            ),
            11 => 
            array (
                'id' => '758a7d22-0abb-4ad4-a890-7f9ab92f7c5e',
                'nama' => 'Berita Acara Serah Terima Hasil Pekerjaan',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:38:19',
                'updated_at' => '2022-05-17 02:38:19',
            ),
            12 => 
            array (
                'id' => '7c7c87a3-3026-4711-9130-864cc7e6b371',
                'nama' => 'E-Biling',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:37:45',
                'updated_at' => '2022-05-17 02:37:45',
            ),
            13 => 
            array (
                'id' => '80924bb4-8777-4285-b551-412fb7682005',
                'nama' => 'Fotokopi KTP Pengurus',
                'kategori' => 'Belanja Hibah',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:52:17',
                'updated_at' => '2022-05-17 02:52:17',
            ),
            14 => 
            array (
                'id' => '820e6606-013b-447f-b394-33f6aa2b1c87',
                'nama' => 'Surat Usulan Pencairan Hibah',
                'kategori' => 'Belanja Hibah',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:51:50',
                'updated_at' => '2022-05-17 02:51:50',
            ),
            15 => 
            array (
                'id' => '85aac30e-902d-42e6-8074-fe512a71ad24',
                'nama' => 'Salinan SPD',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:37:12',
                'updated_at' => '2022-05-17 02:37:12',
            ),
            16 => 
            array (
                'id' => '907acefc-2a14-414b-9f9e-6df8935609eb',
                'nama' => 'Dokumen lain',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:39:09',
                'updated_at' => '2022-05-17 02:39:09',
            ),
            17 => 
            array (
                'id' => 'a021dd85-6652-4de4-8c7d-696ed461b081',
                'nama' => 'Surat Pengantar',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:34:57',
                'updated_at' => '2022-05-17 02:34:57',
            ),
            18 => 
            array (
                'id' => 'cd594d15-dfb7-49ec-9ae5-d89b87e51187',
                'nama' => 'Kwitansi Bermaterai',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:38:53',
                'updated_at' => '2022-05-17 02:38:53',
            ),
            19 => 
            array (
                'id' => 'd3116307-08fd-4866-bdf4-036bd37c9642',
                'nama' => 'Surat Jaminan Bank',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:39:00',
                'updated_at' => '2022-05-17 02:39:00',
            ),
            20 => 
            array (
                'id' => 'd475337a-e276-455b-8277-6244a061b5e2',
                'nama' => 'Surat Angkutan',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:39:23',
                'updated_at' => '2022-05-17 02:39:23',
            ),
            21 => 
            array (
                'id' => 'e3a09a25-c68d-4ba4-88db-a76a47b96c36',
                'nama' => 'Surat Perjanjian Kersama',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:37:54',
                'updated_at' => '2022-05-17 02:37:54',
            ),
            22 => 
            array (
                'id' => 'e4cd97cf-f841-471e-873e-f60601410225',
                'nama' => 'Ringkasan',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:36:15',
                'updated_at' => '2022-05-17 02:36:15',
            ),
            23 => 
            array (
                'id' => 'e66002d6-e608-479c-af75-ecc58ca8dd06',
                'nama' => 'Berita acara prestasi kemajuan',
                'kategori' => 'Barang dan Jasa',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:40:29',
                'updated_at' => '2022-05-17 02:40:29',
            ),
            24 => 
            array (
                'id' => 'f8c9b28a-7074-474f-bc49-cc38caea1651',
                'nama' => 'Pakta Integritas Hibah',
                'kategori' => 'Belanja Hibah',
                'deleted_at' => NULL,
                'created_at' => '2022-05-17 02:53:38',
                'updated_at' => '2022-05-17 02:53:38',
            ),
        ));
        
        
    }
}