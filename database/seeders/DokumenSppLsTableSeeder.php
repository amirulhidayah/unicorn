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
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113159993.pdf',
                'spp_ls_id' => '787f1da4-c936-4019-aa5f-21c9182d3f3b',
                'created_at' => '2022-06-21 13:26:15',
                'updated_at' => '2022-06-21 13:26:15',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113159181.pdf',
                'spp_ls_id' => '787f1da4-c936-4019-aa5f-21c9182d3f3b',
                'created_at' => '2022-06-21 13:26:15',
                'updated_at' => '2022-06-21 13:26:15',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_dokumen' => 'Fotokopi Rekening Bank',
                'dokumen' => 'fotokopi-rekening-bank-22022062113156517.pdf',
                'spp_ls_id' => '787f1da4-c936-4019-aa5f-21c9182d3f3b',
                'created_at' => '2022-06-21 13:26:15',
                'updated_at' => '2022-06-21 13:26:15',
            ),
            3 => 
            array (
                'id' => 4,
                'nama_dokumen' => 'Kwitansi Bermaterai Cukup',
                'dokumen' => 'kwitansi-bermaterai-cukup-32022062113152251.pdf',
                'spp_ls_id' => '787f1da4-c936-4019-aa5f-21c9182d3f3b',
                'created_at' => '2022-06-21 13:26:15',
                'updated_at' => '2022-06-21 13:26:15',
            ),
            4 => 
            array (
                'id' => 5,
                'nama_dokumen' => 'Berita Acara Pembayaran',
                'dokumen' => 'berita-acara-pembayaran-02022062113466917.pdf',
                'spp_ls_id' => 'f78ea3c1-0cbb-4039-b580-ef53b4da0ae9',
                'created_at' => '2022-06-21 13:27:46',
                'updated_at' => '2022-06-21 13:27:46',
            ),
            5 => 
            array (
                'id' => 6,
                'nama_dokumen' => 'Berita Acara Pemeriksaan',
                'dokumen' => 'berita-acara-pemeriksaan-12022062113465737.pdf',
                'spp_ls_id' => 'f78ea3c1-0cbb-4039-b580-ef53b4da0ae9',
                'created_at' => '2022-06-21 13:27:46',
                'updated_at' => '2022-06-21 13:27:46',
            ),
            6 => 
            array (
                'id' => 7,
                'nama_dokumen' => 'Berita acara prestasi kemajuan',
                'dokumen' => 'berita-acara-prestasi-kemajuan-22022062113466728.pdf',
                'spp_ls_id' => 'f78ea3c1-0cbb-4039-b580-ef53b4da0ae9',
                'created_at' => '2022-06-21 13:27:46',
                'updated_at' => '2022-06-21 13:27:46',
            ),
            7 => 
            array (
                'id' => 8,
                'nama_dokumen' => 'Berita Acara Serah Terima Hasil Pekerjaan',
                'dokumen' => 'berita-acara-serah-terima-hasil-pekerjaan-32022062113467785.pdf',
                'spp_ls_id' => 'f78ea3c1-0cbb-4039-b580-ef53b4da0ae9',
                'created_at' => '2022-06-21 13:27:46',
                'updated_at' => '2022-06-21 13:27:46',
            ),
            8 => 
            array (
                'id' => 9,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113222208.pdf',
                'spp_ls_id' => '98b92e4d-08c0-4f84-90fc-6230f6ff4fbf',
                'created_at' => '2022-06-21 13:29:22',
                'updated_at' => '2022-06-21 13:29:22',
            ),
            9 => 
            array (
                'id' => 10,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113225383.pdf',
                'spp_ls_id' => '98b92e4d-08c0-4f84-90fc-6230f6ff4fbf',
                'created_at' => '2022-06-21 13:29:22',
                'updated_at' => '2022-06-21 13:29:22',
            ),
            10 => 
            array (
                'id' => 11,
                'nama_dokumen' => 'Fotokopi Rekening Bank',
                'dokumen' => 'fotokopi-rekening-bank-22022062113225395.pdf',
                'spp_ls_id' => '98b92e4d-08c0-4f84-90fc-6230f6ff4fbf',
                'created_at' => '2022-06-21 13:29:22',
                'updated_at' => '2022-06-21 13:29:22',
            ),
            11 => 
            array (
                'id' => 12,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113488533.pdf',
                'spp_ls_id' => 'f1e222f6-36ac-4fd2-84dc-74879438e9a8',
                'created_at' => '2022-06-21 13:30:48',
                'updated_at' => '2022-06-21 13:30:48',
            ),
            12 => 
            array (
                'id' => 13,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113484287.pdf',
                'spp_ls_id' => 'f1e222f6-36ac-4fd2-84dc-74879438e9a8',
                'created_at' => '2022-06-21 13:30:48',
                'updated_at' => '2022-06-21 13:30:48',
            ),
            13 => 
            array (
                'id' => 14,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113039298.pdf',
                'spp_ls_id' => '2594aad1-8ec0-4944-90b0-40b355c83075',
                'created_at' => '2022-06-21 13:33:03',
                'updated_at' => '2022-06-21 13:33:03',
            ),
            14 => 
            array (
                'id' => 15,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113033472.pdf',
                'spp_ls_id' => '2594aad1-8ec0-4944-90b0-40b355c83075',
                'created_at' => '2022-06-21 13:33:03',
                'updated_at' => '2022-06-21 13:33:03',
            ),
            15 => 
            array (
                'id' => 16,
                'nama_dokumen' => 'Fotokopi Rekening Bank',
                'dokumen' => 'fotokopi-rekening-bank-22022062113037181.pdf',
                'spp_ls_id' => '2594aad1-8ec0-4944-90b0-40b355c83075',
                'created_at' => '2022-06-21 13:33:03',
                'updated_at' => '2022-06-21 13:33:03',
            ),
            16 => 
            array (
                'id' => 17,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113286816.pdf',
                'spp_ls_id' => 'f56c4607-f7cd-47de-a788-739d63188fc5',
                'created_at' => '2022-06-21 13:34:28',
                'updated_at' => '2022-06-21 13:34:28',
            ),
            17 => 
            array (
                'id' => 18,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113285299.pdf',
                'spp_ls_id' => 'f56c4607-f7cd-47de-a788-739d63188fc5',
                'created_at' => '2022-06-21 13:34:28',
                'updated_at' => '2022-06-21 13:34:28',
            ),
            18 => 
            array (
                'id' => 19,
                'nama_dokumen' => 'Fotokopi Rekening Bank',
                'dokumen' => 'fotokopi-rekening-bank-22022062113282386.pdf',
                'spp_ls_id' => 'f56c4607-f7cd-47de-a788-739d63188fc5',
                'created_at' => '2022-06-21 13:34:28',
                'updated_at' => '2022-06-21 13:34:28',
            ),
            19 => 
            array (
                'id' => 20,
                'nama_dokumen' => 'Kwitansi Bermaterai Cukup',
                'dokumen' => 'kwitansi-bermaterai-cukup-32022062113286297.pdf',
                'spp_ls_id' => 'f56c4607-f7cd-47de-a788-739d63188fc5',
                'created_at' => '2022-06-21 13:34:28',
                'updated_at' => '2022-06-21 13:34:28',
            ),
            20 => 
            array (
                'id' => 21,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113215471.pdf',
                'spp_ls_id' => '6bed8151-7643-41e0-9161-ce3c17b88ab6',
                'created_at' => '2022-06-21 13:36:21',
                'updated_at' => '2022-06-21 13:36:21',
            ),
            21 => 
            array (
                'id' => 22,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113212715.pdf',
                'spp_ls_id' => '6bed8151-7643-41e0-9161-ce3c17b88ab6',
                'created_at' => '2022-06-21 13:36:21',
                'updated_at' => '2022-06-21 13:36:21',
            ),
            22 => 
            array (
                'id' => 23,
                'nama_dokumen' => 'Fotokopi Rekening Bank',
                'dokumen' => 'fotokopi-rekening-bank-22022062113212307.pdf',
                'spp_ls_id' => '6bed8151-7643-41e0-9161-ce3c17b88ab6',
                'created_at' => '2022-06-21 13:36:21',
                'updated_at' => '2022-06-21 13:36:21',
            ),
            23 => 
            array (
                'id' => 24,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113107357.pdf',
                'spp_ls_id' => '1f16ad29-6e7d-42e8-a790-e642fb6abeaa',
                'created_at' => '2022-06-21 13:37:10',
                'updated_at' => '2022-06-21 13:37:10',
            ),
            24 => 
            array (
                'id' => 25,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113105927.pdf',
                'spp_ls_id' => '1f16ad29-6e7d-42e8-a790-e642fb6abeaa',
                'created_at' => '2022-06-21 13:37:10',
                'updated_at' => '2022-06-21 13:37:10',
            ),
            25 => 
            array (
                'id' => 26,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113277764.pdf',
                'spp_ls_id' => '2eaa2353-18e5-464f-a792-4f1b2d0c74bc',
                'created_at' => '2022-06-21 13:38:27',
                'updated_at' => '2022-06-21 13:38:27',
            ),
            26 => 
            array (
                'id' => 27,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113275514.pdf',
                'spp_ls_id' => '2eaa2353-18e5-464f-a792-4f1b2d0c74bc',
                'created_at' => '2022-06-21 13:38:27',
                'updated_at' => '2022-06-21 13:38:27',
            ),
            27 => 
            array (
                'id' => 28,
                'nama_dokumen' => 'Fotokopi Rekening Bank',
                'dokumen' => 'fotokopi-rekening-bank-22022062113278690.pdf',
                'spp_ls_id' => '2eaa2353-18e5-464f-a792-4f1b2d0c74bc',
                'created_at' => '2022-06-21 13:38:27',
                'updated_at' => '2022-06-21 13:38:27',
            ),
            28 => 
            array (
                'id' => 29,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113297812.pdf',
                'spp_ls_id' => 'ef55c0f5-d2ec-40cb-b80b-e59352570179',
                'created_at' => '2022-06-21 13:39:29',
                'updated_at' => '2022-06-21 13:39:29',
            ),
            29 => 
            array (
                'id' => 30,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113299740.pdf',
                'spp_ls_id' => 'ef55c0f5-d2ec-40cb-b80b-e59352570179',
                'created_at' => '2022-06-21 13:39:29',
                'updated_at' => '2022-06-21 13:39:29',
            ),
            30 => 
            array (
                'id' => 31,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113325345.pdf',
                'spp_ls_id' => '4482f1c2-1635-4cdb-ac61-afbc8eb98b17',
                'created_at' => '2022-06-21 13:40:32',
                'updated_at' => '2022-06-21 13:40:32',
            ),
            31 => 
            array (
                'id' => 32,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113323755.pdf',
                'spp_ls_id' => '4482f1c2-1635-4cdb-ac61-afbc8eb98b17',
                'created_at' => '2022-06-21 13:40:32',
                'updated_at' => '2022-06-21 13:40:32',
            ),
            32 => 
            array (
                'id' => 33,
                'nama_dokumen' => 'Dokumen Administrasi',
                'dokumen' => 'dokumen-administrasi-02022062113147389.pdf',
                'spp_ls_id' => '6055b9db-bcd8-4805-8dd4-9cbb782b8c7f',
                'created_at' => '2022-06-21 13:41:14',
                'updated_at' => '2022-06-21 13:41:14',
            ),
            33 => 
            array (
                'id' => 34,
                'nama_dokumen' => 'Fotokopi KTP Pengurus',
                'dokumen' => 'fotokopi-ktp-pengurus-12022062113145151.pdf',
                'spp_ls_id' => '6055b9db-bcd8-4805-8dd4-9cbb782b8c7f',
                'created_at' => '2022-06-21 13:41:14',
                'updated_at' => '2022-06-21 13:41:14',
            ),
            34 => 
            array (
                'id' => 35,
                'nama_dokumen' => 'Fotokopi Rekening Bank',
                'dokumen' => 'fotokopi-rekening-bank-22022062113144982.pdf',
                'spp_ls_id' => '6055b9db-bcd8-4805-8dd4-9cbb782b8c7f',
                'created_at' => '2022-06-21 13:41:14',
                'updated_at' => '2022-06-21 13:41:14',
            ),
        ));
        
        
    }
}