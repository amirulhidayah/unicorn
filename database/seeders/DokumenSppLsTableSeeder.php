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
                'dokumen' => 'billing-ppn-pph-yang-aktif-12023082805365640.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Fotocopy NPWP',
                'dokumen' => 'fotocopy-npwp-22023082805361306.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_dokumen' => 'Fotocopy Rekening Koran',
                'dokumen' => 'fotocopy-rekening-koran-32023082805363037.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            3 => 
            array (
                'id' => 4,
                'nama_dokumen' => 'Rekap Pajak Per Golongan',
                'dokumen' => 'rekap-pajak-per-golongan-42023082805363774.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            4 => 
            array (
                'id' => 5,
                'nama_dokumen' => 'RTGS',
                'dokumen' => 'rtgs-52023082805361021.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            5 => 
            array (
                'id' => 6,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-ls-620230828053638.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            6 => 
            array (
                'id' => 7,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-ls-72023082805367471.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            7 => 
            array (
                'id' => 8,
                'nama_dokumen' => 'Surat Pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-82023082805368556.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            8 => 
            array (
                'id' => 9,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-92023082805363992.pdf',
                'spp_ls_id' => '779052b3-3eef-4284-8a40-9f04c35442a4',
                'created_at' => '2023-08-28 05:10:36',
                'updated_at' => '2023-08-28 05:10:36',
            ),
            9 => 
            array (
                'id' => 10,
            'nama_dokumen' => 'Billing (PPN & PPh) yang aktif',
                'dokumen' => 'billing-ppn-pph-yang-aktif-12023082805424876.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            10 => 
            array (
                'id' => 11,
                'nama_dokumen' => 'Fotocopy NPWP',
                'dokumen' => 'fotocopy-npwp-22023082805423017.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            11 => 
            array (
                'id' => 12,
                'nama_dokumen' => 'Fotocopy Rekening Koran',
                'dokumen' => 'fotocopy-rekening-koran-32023082805423472.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            12 => 
            array (
                'id' => 13,
                'nama_dokumen' => 'Rekap Pajak Per Golongan',
                'dokumen' => 'rekap-pajak-per-golongan-42023082805427886.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            13 => 
            array (
                'id' => 14,
                'nama_dokumen' => 'RTGS',
                'dokumen' => 'rtgs-52023082805428637.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            14 => 
            array (
                'id' => 15,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-ls-62023082805427896.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            15 => 
            array (
                'id' => 16,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-ls-72023082805422035.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            16 => 
            array (
                'id' => 17,
                'nama_dokumen' => 'Surat Pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-82023082805425178.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            17 => 
            array (
                'id' => 18,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-92023082805425214.pdf',
                'spp_ls_id' => '642ce1db-7b6a-4e77-bb84-95eb79a93786',
                'created_at' => '2023-08-28 05:12:42',
                'updated_at' => '2023-08-28 05:12:42',
            ),
            18 => 
            array (
                'id' => 19,
            'nama_dokumen' => 'Billing (PPN & PPh) yang aktif',
                'dokumen' => 'billing-ppn-pph-yang-aktif-12023082813088273.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            19 => 
            array (
                'id' => 20,
                'nama_dokumen' => 'Fotocopy NPWP',
                'dokumen' => 'fotocopy-npwp-22023082813088754.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            20 => 
            array (
                'id' => 21,
                'nama_dokumen' => 'Fotocopy Rekening Koran',
                'dokumen' => 'fotocopy-rekening-koran-32023082813082272.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            21 => 
            array (
                'id' => 22,
                'nama_dokumen' => 'Rekap Pajak Per Golongan',
                'dokumen' => 'rekap-pajak-per-golongan-42023082813086054.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            22 => 
            array (
                'id' => 23,
                'nama_dokumen' => 'RTGS',
                'dokumen' => 'rtgs-520230828130818.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            23 => 
            array (
                'id' => 24,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-ls-6202308281308797.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            24 => 
            array (
                'id' => 25,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-ls-72023082813084036.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            25 => 
            array (
                'id' => 26,
                'nama_dokumen' => 'Surat Pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-82023082813088595.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            26 => 
            array (
                'id' => 27,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-92023082813086476.pdf',
                'spp_ls_id' => '0a69c86c-2894-4b54-acb1-3f4276a27aef',
                'created_at' => '2023-08-28 13:15:08',
                'updated_at' => '2023-08-28 13:15:08',
            ),
            27 => 
            array (
                'id' => 28,
            'nama_dokumen' => 'Billing (PPN & PPh) yang aktif',
                'dokumen' => 'billing-ppn-pph-yang-aktif-12023082813174600.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            28 => 
            array (
                'id' => 29,
                'nama_dokumen' => 'Fotocopy NPWP',
                'dokumen' => 'fotocopy-npwp-22023082813171340.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            29 => 
            array (
                'id' => 30,
                'nama_dokumen' => 'Fotocopy Rekening Koran',
                'dokumen' => 'fotocopy-rekening-koran-32023082813177402.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            30 => 
            array (
                'id' => 31,
                'nama_dokumen' => 'Rekap Pajak Per Golongan',
                'dokumen' => 'rekap-pajak-per-golongan-42023082813173139.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            31 => 
            array (
                'id' => 32,
                'nama_dokumen' => 'RTGS',
                'dokumen' => 'rtgs-52023082813175583.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            32 => 
            array (
                'id' => 33,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-ls-62023082813177687.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            33 => 
            array (
                'id' => 34,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-ls-72023082813174080.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            34 => 
            array (
                'id' => 35,
                'nama_dokumen' => 'Surat Pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-82023082813173921.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            35 => 
            array (
                'id' => 36,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-92023082813175673.pdf',
                'spp_ls_id' => '55b94e2e-ecde-4223-b638-e2a4edf23e49',
                'created_at' => '2023-08-28 13:23:17',
                'updated_at' => '2023-08-28 13:23:17',
            ),
            36 => 
            array (
                'id' => 37,
            'nama_dokumen' => 'Billing (PPN & PPh) yang aktif',
                'dokumen' => 'billing-ppn-pph-yang-aktif-12023082813126401.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            37 => 
            array (
                'id' => 38,
                'nama_dokumen' => 'Fotocopy NPWP',
                'dokumen' => 'fotocopy-npwp-2202308281312988.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            38 => 
            array (
                'id' => 39,
                'nama_dokumen' => 'Fotocopy Rekening Koran',
                'dokumen' => 'fotocopy-rekening-koran-32023082813123087.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            39 => 
            array (
                'id' => 40,
                'nama_dokumen' => 'Rekap Pajak Per Golongan',
                'dokumen' => 'rekap-pajak-per-golongan-42023082813122907.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            40 => 
            array (
                'id' => 41,
                'nama_dokumen' => 'RTGS',
                'dokumen' => 'rtgs-52023082813124356.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            41 => 
            array (
                'id' => 42,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-ls-62023082813123318.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            42 => 
            array (
                'id' => 43,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-ls-72023082813127086.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            43 => 
            array (
                'id' => 44,
                'nama_dokumen' => 'Surat Pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-82023082813121668.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            44 => 
            array (
                'id' => 45,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-92023082813127860.pdf',
                'spp_ls_id' => 'e954fb56-b0e7-465c-a4ca-9e887ed2ec64',
                'created_at' => '2023-08-28 13:24:12',
                'updated_at' => '2023-08-28 13:24:12',
            ),
            45 => 
            array (
                'id' => 46,
            'nama_dokumen' => 'Billing (PPN & PPh) yang aktif',
                'dokumen' => 'billing-ppn-pph-yang-aktif-12023082813002985.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            46 => 
            array (
                'id' => 47,
                'nama_dokumen' => 'Fotocopy NPWP',
                'dokumen' => 'fotocopy-npwp-22023082813005784.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            47 => 
            array (
                'id' => 48,
                'nama_dokumen' => 'Fotocopy Rekening Koran',
                'dokumen' => 'fotocopy-rekening-koran-32023082813009027.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            48 => 
            array (
                'id' => 49,
                'nama_dokumen' => 'Rekap Pajak Per Golongan',
                'dokumen' => 'rekap-pajak-per-golongan-42023082813009104.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            49 => 
            array (
                'id' => 50,
                'nama_dokumen' => 'RTGS',
                'dokumen' => 'rtgs-52023082813004444.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            50 => 
            array (
                'id' => 51,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPM LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spm-ls-62023082813001696.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            51 => 
            array (
                'id' => 52,
                'nama_dokumen' => 'Surat Pernyataan Pengajuan SPP LS',
                'dokumen' => 'surat-pernyataan-pengajuan-spp-ls-72023082813003235.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            52 => 
            array (
                'id' => 53,
                'nama_dokumen' => 'Surat Pernyataan Sumber Dana',
                'dokumen' => 'surat-pernyataan-sumber-dana-82023082813008612.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
            53 => 
            array (
                'id' => 54,
                'nama_dokumen' => 'Surat Pernyataan Tanggung Jawab PA/KPA',
                'dokumen' => 'surat-pernyataan-tanggung-jawab-pakpa-92023082813001574.pdf',
                'spp_ls_id' => '8e737d86-d665-4053-a6de-8efeea27c358',
                'created_at' => '2023-08-28 13:27:00',
                'updated_at' => '2023-08-28 13:27:00',
            ),
        ));
        
        
    }
}