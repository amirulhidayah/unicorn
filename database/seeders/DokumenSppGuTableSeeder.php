<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DokumenSppGuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dokumen_spp_gu')->delete();
        
        \DB::table('dokumen_spp_gu')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama_dokumen' => 'Bukti Lunas Pajak',
                'dokumen' => 'bukti-lunas-pajak-12023082822513308.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-gu-22023082822514953.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_dokumen' => 'Laporan Pertanggung Jawaban GU',
                'dokumen' => 'laporan-pertanggung-jawaban-gu-3202308282251570.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            3 => 
            array (
                'id' => 4,
                'nama_dokumen' => 'Rekap Pajak',
                'dokumen' => 'rekap-pajak-42023082822518902.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            4 => 
            array (
                'id' => 5,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-52023082822519737.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            5 => 
            array (
                'id' => 6,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-gu-62023082822517587.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            6 => 
            array (
                'id' => 7,
                'nama_dokumen' => 'Surat pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-72023082822514451.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            7 => 
            array (
                'id' => 8,
                'nama_dokumen' => 'Pengesahan Pertanggung Jawaban Bendahara',
                'dokumen' => 'pengesahan-pertanggung-jawaban-bendahara-82023082822518296.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'eabe1ea0-fbc8-4083-b30b-8677a3d739e9',
                'created_at' => '2023-08-28 22:19:51',
                'updated_at' => '2023-08-28 22:19:51',
            ),
            8 => 
            array (
                'id' => 9,
                'nama_dokumen' => 'Bukti Lunas Pajak',
                'dokumen' => 'bukti-lunas-pajak-12023082822487291.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            9 => 
            array (
                'id' => 10,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-gu-22023082822484056.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            10 => 
            array (
                'id' => 11,
                'nama_dokumen' => 'Laporan Pertanggung Jawaban GU',
                'dokumen' => 'laporan-pertanggung-jawaban-gu-32023082822485097.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            11 => 
            array (
                'id' => 12,
                'nama_dokumen' => 'Rekap Pajak',
                'dokumen' => 'rekap-pajak-42023082822481779.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            12 => 
            array (
                'id' => 13,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-52023082822487663.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            13 => 
            array (
                'id' => 14,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-gu-62023082822488442.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            14 => 
            array (
                'id' => 15,
                'nama_dokumen' => 'Surat pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-72023082822486895.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            15 => 
            array (
                'id' => 16,
                'nama_dokumen' => 'Pengesahan Pertanggung Jawaban Bendahara',
                'dokumen' => 'pengesahan-pertanggung-jawaban-bendahara-82023082822482712.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'a66e8f30-767c-4f11-bc02-ad4b05ea4b29',
                'created_at' => '2023-08-28 22:20:48',
                'updated_at' => '2023-08-28 22:20:48',
            ),
            16 => 
            array (
                'id' => 17,
                'nama_dokumen' => 'Bukti Lunas Pajak',
                'dokumen' => 'bukti-lunas-pajak-12023082822309263.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            17 => 
            array (
                'id' => 18,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-gu-2202308282230209.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            18 => 
            array (
                'id' => 19,
                'nama_dokumen' => 'Laporan Pertanggung Jawaban GU',
                'dokumen' => 'laporan-pertanggung-jawaban-gu-32023082822305059.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            19 => 
            array (
                'id' => 20,
                'nama_dokumen' => 'Rekap Pajak',
                'dokumen' => 'rekap-pajak-4202308282230874.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            20 => 
            array (
                'id' => 21,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-52023082822304966.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            21 => 
            array (
                'id' => 22,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-gu-62023082822309183.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            22 => 
            array (
                'id' => 23,
                'nama_dokumen' => 'Surat pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-72023082822305456.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            23 => 
            array (
                'id' => 24,
                'nama_dokumen' => 'Pengesahan Pertanggung Jawaban Bendahara',
                'dokumen' => 'pengesahan-pertanggung-jawaban-bendahara-8202308282230400.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => 'd774af2f-3d8a-4e6a-819c-a5fb3aa1ee3b',
                'created_at' => '2023-08-28 22:22:30',
                'updated_at' => '2023-08-28 22:22:30',
            ),
            24 => 
            array (
                'id' => 25,
                'nama_dokumen' => 'Bukti Lunas Pajak',
                'dokumen' => 'bukti-lunas-pajak-12023082822025289.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            25 => 
            array (
                'id' => 26,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-gu-22023082822022908.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            26 => 
            array (
                'id' => 27,
                'nama_dokumen' => 'Laporan Pertanggung Jawaban GU',
                'dokumen' => 'laporan-pertanggung-jawaban-gu-32023082822028127.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            27 => 
            array (
                'id' => 28,
                'nama_dokumen' => 'Rekap Pajak',
                'dokumen' => 'rekap-pajak-42023082822027624.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            28 => 
            array (
                'id' => 29,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-52023082822026998.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            29 => 
            array (
                'id' => 30,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM GU',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-gu-62023082822028376.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            30 => 
            array (
                'id' => 31,
                'nama_dokumen' => 'Surat pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-72023082822021538.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
            31 => 
            array (
                'id' => 32,
                'nama_dokumen' => 'Pengesahan Pertanggung Jawaban Bendahara',
                'dokumen' => 'pengesahan-pertanggung-jawaban-bendahara-82023082822027338.pdf',
                'tahap' => 'SPJ',
                'spp_gu_id' => '7e7dabcf-3d7a-4863-914f-af9524f2f89f',
                'created_at' => '2023-08-28 22:23:02',
                'updated_at' => '2023-08-28 22:23:02',
            ),
        ));
        
        
    }
}