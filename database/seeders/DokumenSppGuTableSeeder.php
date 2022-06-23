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
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114537198.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '82d809a8-2655-4e90-87b1-15524001e902',
                'created_at' => '2022-06-21 14:07:53',
                'updated_at' => '2022-06-21 14:07:53',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114537388.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '82d809a8-2655-4e90-87b1-15524001e902',
                'created_at' => '2022-06-21 14:07:53',
                'updated_at' => '2022-06-21 14:07:53',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114531224.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '82d809a8-2655-4e90-87b1-15524001e902',
                'created_at' => '2022-06-21 14:07:53',
                'updated_at' => '2022-06-21 14:07:53',
            ),
            3 => 
            array (
                'id' => 4,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114238469.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '1a9073f4-1730-4b65-a464-7d182c0105e7',
                'created_at' => '2022-06-21 14:08:23',
                'updated_at' => '2022-06-21 14:08:23',
            ),
            4 => 
            array (
                'id' => 5,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114239706.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '1a9073f4-1730-4b65-a464-7d182c0105e7',
                'created_at' => '2022-06-21 14:08:23',
                'updated_at' => '2022-06-21 14:08:23',
            ),
            5 => 
            array (
                'id' => 6,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-2202206211423213.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '1a9073f4-1730-4b65-a464-7d182c0105e7',
                'created_at' => '2022-06-21 14:08:23',
                'updated_at' => '2022-06-21 14:08:23',
            ),
            6 => 
            array (
                'id' => 7,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114008375.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '93ed1231-dd20-406a-99aa-88d511bbcfc3',
                'created_at' => '2022-06-21 14:09:00',
                'updated_at' => '2022-06-21 14:09:00',
            ),
            7 => 
            array (
                'id' => 8,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114009175.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '93ed1231-dd20-406a-99aa-88d511bbcfc3',
                'created_at' => '2022-06-21 14:09:00',
                'updated_at' => '2022-06-21 14:09:00',
            ),
            8 => 
            array (
                'id' => 9,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114002117.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '93ed1231-dd20-406a-99aa-88d511bbcfc3',
                'created_at' => '2022-06-21 14:09:00',
                'updated_at' => '2022-06-21 14:09:00',
            ),
            9 => 
            array (
                'id' => 10,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114458428.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '34aaab8c-7bdd-4d89-bf89-604b4bd2b966',
                'created_at' => '2022-06-21 14:09:45',
                'updated_at' => '2022-06-21 14:09:45',
            ),
            10 => 
            array (
                'id' => 11,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114458764.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '34aaab8c-7bdd-4d89-bf89-604b4bd2b966',
                'created_at' => '2022-06-21 14:09:45',
                'updated_at' => '2022-06-21 14:09:45',
            ),
            11 => 
            array (
                'id' => 12,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114457234.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '34aaab8c-7bdd-4d89-bf89-604b4bd2b966',
                'created_at' => '2022-06-21 14:09:45',
                'updated_at' => '2022-06-21 14:09:45',
            ),
            12 => 
            array (
                'id' => 13,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114516315.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'a150b243-bf2b-4159-ac49-9e9034c13586',
                'created_at' => '2022-06-21 14:10:51',
                'updated_at' => '2022-06-21 14:10:51',
            ),
            13 => 
            array (
                'id' => 14,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114511302.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'a150b243-bf2b-4159-ac49-9e9034c13586',
                'created_at' => '2022-06-21 14:10:51',
                'updated_at' => '2022-06-21 14:10:51',
            ),
            14 => 
            array (
                'id' => 15,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114513315.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'a150b243-bf2b-4159-ac49-9e9034c13586',
                'created_at' => '2022-06-21 14:10:51',
                'updated_at' => '2022-06-21 14:10:51',
            ),
            15 => 
            array (
                'id' => 16,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114423415.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'b8012402-9ebd-4956-a811-e3e038517a97',
                'created_at' => '2022-06-21 14:11:42',
                'updated_at' => '2022-06-21 14:11:42',
            ),
            16 => 
            array (
                'id' => 17,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114426271.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'b8012402-9ebd-4956-a811-e3e038517a97',
                'created_at' => '2022-06-21 14:11:42',
                'updated_at' => '2022-06-21 14:11:42',
            ),
            17 => 
            array (
                'id' => 18,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114423373.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'b8012402-9ebd-4956-a811-e3e038517a97',
                'created_at' => '2022-06-21 14:11:42',
                'updated_at' => '2022-06-21 14:11:42',
            ),
            18 => 
            array (
                'id' => 19,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114171619.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'df6e1383-0ffd-423c-9e9b-af40616061c4',
                'created_at' => '2022-06-21 14:12:17',
                'updated_at' => '2022-06-21 14:12:17',
            ),
            19 => 
            array (
                'id' => 20,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114174226.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'df6e1383-0ffd-423c-9e9b-af40616061c4',
                'created_at' => '2022-06-21 14:12:17',
                'updated_at' => '2022-06-21 14:12:17',
            ),
            20 => 
            array (
                'id' => 21,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114172629.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'df6e1383-0ffd-423c-9e9b-af40616061c4',
                'created_at' => '2022-06-21 14:12:17',
                'updated_at' => '2022-06-21 14:12:17',
            ),
            21 => 
            array (
                'id' => 22,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-020220621140611.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '8f0f7187-a2dc-40bf-aa4b-b345fe0b2edd',
                'created_at' => '2022-06-21 14:13:06',
                'updated_at' => '2022-06-21 14:13:06',
            ),
            22 => 
            array (
                'id' => 23,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114067494.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '8f0f7187-a2dc-40bf-aa4b-b345fe0b2edd',
                'created_at' => '2022-06-21 14:13:06',
                'updated_at' => '2022-06-21 14:13:06',
            ),
            23 => 
            array (
                'id' => 24,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114066826.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '8f0f7187-a2dc-40bf-aa4b-b345fe0b2edd',
                'created_at' => '2022-06-21 14:13:06',
                'updated_at' => '2022-06-21 14:13:06',
            ),
            24 => 
            array (
                'id' => 25,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062114512958.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'f2af10b1-bbf1-4618-a82a-bd4712fe0e62',
                'created_at' => '2022-06-21 14:13:51',
                'updated_at' => '2022-06-21 14:13:51',
            ),
            25 => 
            array (
                'id' => 26,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062114519881.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'f2af10b1-bbf1-4618-a82a-bd4712fe0e62',
                'created_at' => '2022-06-21 14:13:51',
                'updated_at' => '2022-06-21 14:13:51',
            ),
            26 => 
            array (
                'id' => 27,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062114514399.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'f2af10b1-bbf1-4618-a82a-bd4712fe0e62',
                'created_at' => '2022-06-21 14:13:51',
                'updated_at' => '2022-06-21 14:13:51',
            ),
            27 => 
            array (
                'id' => 28,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062123556066.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'c5824fc2-134d-4dc6-9588-05d35f80383b',
                'created_at' => '2022-06-21 23:44:55',
                'updated_at' => '2022-06-21 23:44:55',
            ),
            28 => 
            array (
                'id' => 29,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062123554984.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'c5824fc2-134d-4dc6-9588-05d35f80383b',
                'created_at' => '2022-06-21 23:44:55',
                'updated_at' => '2022-06-21 23:44:55',
            ),
            29 => 
            array (
                'id' => 30,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062123557360.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'c5824fc2-134d-4dc6-9588-05d35f80383b',
                'created_at' => '2022-06-21 23:44:55',
                'updated_at' => '2022-06-21 23:44:55',
            ),
            30 => 
            array (
                'id' => 31,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-02022062123202236.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '9659e288-f3a2-49c9-9d39-dcb1ef51dd22',
                'created_at' => '2022-06-21 23:46:20',
                'updated_at' => '2022-06-21 23:46:20',
            ),
            31 => 
            array (
                'id' => 32,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062123203298.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '9659e288-f3a2-49c9-9d39-dcb1ef51dd22',
                'created_at' => '2022-06-21 23:46:20',
                'updated_at' => '2022-06-21 23:46:20',
            ),
            32 => 
            array (
                'id' => 33,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062123207097.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => '9659e288-f3a2-49c9-9d39-dcb1ef51dd22',
                'created_at' => '2022-06-21 23:46:20',
                'updated_at' => '2022-06-21 23:46:20',
            ),
            33 => 
            array (
                'id' => 34,
                'nama_dokumen' => 'Bukti Transaksi 1',
                'dokumen' => 'bukti-transaksi-1-0202206212309375.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'dc0e14bb-4a80-413e-a381-88f11df58112',
                'created_at' => '2022-06-21 23:47:09',
                'updated_at' => '2022-06-21 23:47:09',
            ),
            34 => 
            array (
                'id' => 35,
                'nama_dokumen' => 'Bukti Transaksi 2',
                'dokumen' => 'bukti-transaksi-2-12022062123096485.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'dc0e14bb-4a80-413e-a381-88f11df58112',
                'created_at' => '2022-06-21 23:47:09',
                'updated_at' => '2022-06-21 23:47:09',
            ),
            35 => 
            array (
                'id' => 36,
                'nama_dokumen' => 'Bukti Transaksi 3',
                'dokumen' => 'bukti-transaksi-3-22022062123099615.pdf',
                'tahap' => 'Awal',
                'spp_gu_id' => 'dc0e14bb-4a80-413e-a381-88f11df58112',
                'created_at' => '2022-06-21 23:47:09',
                'updated_at' => '2022-06-21 23:47:09',
            ),
            36 => 
            array (
                'id' => 37,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123532627.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'dc0e14bb-4a80-413e-a381-88f11df58112',
                'created_at' => '2022-06-21 23:51:53',
                'updated_at' => '2022-06-21 23:51:53',
            ),
            37 => 
            array (
                'id' => 38,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123531211.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'dc0e14bb-4a80-413e-a381-88f11df58112',
                'created_at' => '2022-06-21 23:51:53',
                'updated_at' => '2022-06-21 23:51:53',
            ),
            38 => 
            array (
                'id' => 39,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-2202206212353625.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'dc0e14bb-4a80-413e-a381-88f11df58112',
                'created_at' => '2022-06-21 23:51:53',
                'updated_at' => '2022-06-21 23:51:53',
            ),
            39 => 
            array (
                'id' => 40,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123129509.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '9659e288-f3a2-49c9-9d39-dcb1ef51dd22',
                'created_at' => '2022-06-21 23:52:12',
                'updated_at' => '2022-06-21 23:52:12',
            ),
            40 => 
            array (
                'id' => 41,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123126403.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '9659e288-f3a2-49c9-9d39-dcb1ef51dd22',
                'created_at' => '2022-06-21 23:52:12',
                'updated_at' => '2022-06-21 23:52:12',
            ),
            41 => 
            array (
                'id' => 42,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123121683.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '9659e288-f3a2-49c9-9d39-dcb1ef51dd22',
                'created_at' => '2022-06-21 23:52:12',
                'updated_at' => '2022-06-21 23:52:12',
            ),
            42 => 
            array (
                'id' => 43,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123378463.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'c5824fc2-134d-4dc6-9588-05d35f80383b',
                'created_at' => '2022-06-21 23:52:37',
                'updated_at' => '2022-06-21 23:52:37',
            ),
            43 => 
            array (
                'id' => 44,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-1202206212337499.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'c5824fc2-134d-4dc6-9588-05d35f80383b',
                'created_at' => '2022-06-21 23:52:37',
                'updated_at' => '2022-06-21 23:52:37',
            ),
            44 => 
            array (
                'id' => 45,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123371096.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'c5824fc2-134d-4dc6-9588-05d35f80383b',
                'created_at' => '2022-06-21 23:52:37',
                'updated_at' => '2022-06-21 23:52:37',
            ),
            45 => 
            array (
                'id' => 46,
                'nama_dokumen' => 'Salinan SPD',
                'dokumen' => 'salinan-spd-32022062123375219.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'c5824fc2-134d-4dc6-9588-05d35f80383b',
                'created_at' => '2022-06-21 23:52:37',
                'updated_at' => '2022-06-21 23:52:37',
            ),
            46 => 
            array (
                'id' => 47,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123248945.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'f2af10b1-bbf1-4618-a82a-bd4712fe0e62',
                'created_at' => '2022-06-21 23:54:24',
                'updated_at' => '2022-06-21 23:54:24',
            ),
            47 => 
            array (
                'id' => 48,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123241313.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'f2af10b1-bbf1-4618-a82a-bd4712fe0e62',
                'created_at' => '2022-06-21 23:54:24',
                'updated_at' => '2022-06-21 23:54:24',
            ),
            48 => 
            array (
                'id' => 49,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123248463.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'f2af10b1-bbf1-4618-a82a-bd4712fe0e62',
                'created_at' => '2022-06-21 23:54:24',
                'updated_at' => '2022-06-21 23:54:24',
            ),
            49 => 
            array (
                'id' => 50,
                'nama_dokumen' => 'Salinan SPD',
                'dokumen' => 'salinan-spd-32022062123241958.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'f2af10b1-bbf1-4618-a82a-bd4712fe0e62',
                'created_at' => '2022-06-21 23:54:24',
                'updated_at' => '2022-06-21 23:54:24',
            ),
            50 => 
            array (
                'id' => 51,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-0202206212350954.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '8f0f7187-a2dc-40bf-aa4b-b345fe0b2edd',
                'created_at' => '2022-06-21 23:54:50',
                'updated_at' => '2022-06-21 23:54:50',
            ),
            51 => 
            array (
                'id' => 52,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123507018.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '8f0f7187-a2dc-40bf-aa4b-b345fe0b2edd',
                'created_at' => '2022-06-21 23:54:50',
                'updated_at' => '2022-06-21 23:54:50',
            ),
            52 => 
            array (
                'id' => 53,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123505074.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '8f0f7187-a2dc-40bf-aa4b-b345fe0b2edd',
                'created_at' => '2022-06-21 23:54:50',
                'updated_at' => '2022-06-21 23:54:50',
            ),
            53 => 
            array (
                'id' => 54,
                'nama_dokumen' => 'Salinan SPD',
                'dokumen' => 'salinan-spd-32022062123509991.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '8f0f7187-a2dc-40bf-aa4b-b345fe0b2edd',
                'created_at' => '2022-06-21 23:54:50',
                'updated_at' => '2022-06-21 23:54:50',
            ),
            54 => 
            array (
                'id' => 55,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123433592.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'df6e1383-0ffd-423c-9e9b-af40616061c4',
                'created_at' => '2022-06-21 23:55:43',
                'updated_at' => '2022-06-21 23:55:43',
            ),
            55 => 
            array (
                'id' => 56,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123433686.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'df6e1383-0ffd-423c-9e9b-af40616061c4',
                'created_at' => '2022-06-21 23:55:43',
                'updated_at' => '2022-06-21 23:55:43',
            ),
            56 => 
            array (
                'id' => 57,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123437129.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'df6e1383-0ffd-423c-9e9b-af40616061c4',
                'created_at' => '2022-06-21 23:55:43',
                'updated_at' => '2022-06-21 23:55:43',
            ),
            57 => 
            array (
                'id' => 58,
                'nama_dokumen' => 'Salinan SPD',
                'dokumen' => 'salinan-spd-32022062123436316.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'df6e1383-0ffd-423c-9e9b-af40616061c4',
                'created_at' => '2022-06-21 23:55:43',
                'updated_at' => '2022-06-21 23:55:43',
            ),
            58 => 
            array (
                'id' => 59,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123081039.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'b8012402-9ebd-4956-a811-e3e038517a97',
                'created_at' => '2022-06-21 23:56:08',
                'updated_at' => '2022-06-21 23:56:08',
            ),
            59 => 
            array (
                'id' => 60,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123089197.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'b8012402-9ebd-4956-a811-e3e038517a97',
                'created_at' => '2022-06-21 23:56:08',
                'updated_at' => '2022-06-21 23:56:08',
            ),
            60 => 
            array (
                'id' => 61,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123081713.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'b8012402-9ebd-4956-a811-e3e038517a97',
                'created_at' => '2022-06-21 23:56:08',
                'updated_at' => '2022-06-21 23:56:08',
            ),
            61 => 
            array (
                'id' => 62,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123312170.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'a150b243-bf2b-4159-ac49-9e9034c13586',
                'created_at' => '2022-06-21 23:56:31',
                'updated_at' => '2022-06-21 23:56:31',
            ),
            62 => 
            array (
                'id' => 63,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123311419.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'a150b243-bf2b-4159-ac49-9e9034c13586',
                'created_at' => '2022-06-21 23:56:31',
                'updated_at' => '2022-06-21 23:56:31',
            ),
            63 => 
            array (
                'id' => 64,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123313369.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => 'a150b243-bf2b-4159-ac49-9e9034c13586',
                'created_at' => '2022-06-21 23:56:31',
                'updated_at' => '2022-06-21 23:56:31',
            ),
            64 => 
            array (
                'id' => 65,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123506745.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '34aaab8c-7bdd-4d89-bf89-604b4bd2b966',
                'created_at' => '2022-06-21 23:56:50',
                'updated_at' => '2022-06-21 23:56:50',
            ),
            65 => 
            array (
                'id' => 66,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-1202206212350182.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '34aaab8c-7bdd-4d89-bf89-604b4bd2b966',
                'created_at' => '2022-06-21 23:56:50',
                'updated_at' => '2022-06-21 23:56:50',
            ),
            66 => 
            array (
                'id' => 67,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123504451.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '34aaab8c-7bdd-4d89-bf89-604b4bd2b966',
                'created_at' => '2022-06-21 23:56:50',
                'updated_at' => '2022-06-21 23:56:50',
            ),
            67 => 
            array (
                'id' => 68,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123113909.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '93ed1231-dd20-406a-99aa-88d511bbcfc3',
                'created_at' => '2022-06-21 23:57:11',
                'updated_at' => '2022-06-21 23:57:11',
            ),
            68 => 
            array (
                'id' => 69,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123116879.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '93ed1231-dd20-406a-99aa-88d511bbcfc3',
                'created_at' => '2022-06-21 23:57:11',
                'updated_at' => '2022-06-21 23:57:11',
            ),
            69 => 
            array (
                'id' => 70,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123363466.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '1a9073f4-1730-4b65-a464-7d182c0105e7',
                'created_at' => '2022-06-21 23:57:36',
                'updated_at' => '2022-06-21 23:57:36',
            ),
            70 => 
            array (
                'id' => 71,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-1202206212336477.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '1a9073f4-1730-4b65-a464-7d182c0105e7',
                'created_at' => '2022-06-21 23:57:36',
                'updated_at' => '2022-06-21 23:57:36',
            ),
            71 => 
            array (
                'id' => 72,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123362647.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '1a9073f4-1730-4b65-a464-7d182c0105e7',
                'created_at' => '2022-06-21 23:57:36',
                'updated_at' => '2022-06-21 23:57:36',
            ),
            72 => 
            array (
                'id' => 73,
                'nama_dokumen' => 'Draf Surat Pernyataan',
                'dokumen' => 'draf-surat-pernyataan-02022062123056231.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '82d809a8-2655-4e90-87b1-15524001e902',
                'created_at' => '2022-06-21 23:58:05',
                'updated_at' => '2022-06-21 23:58:05',
            ),
            73 => 
            array (
                'id' => 74,
                'nama_dokumen' => 'Ringkasan',
                'dokumen' => 'ringkasan-12022062123056203.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '82d809a8-2655-4e90-87b1-15524001e902',
                'created_at' => '2022-06-21 23:58:05',
                'updated_at' => '2022-06-21 23:58:05',
            ),
            74 => 
            array (
                'id' => 75,
                'nama_dokumen' => 'E-Biling',
                'dokumen' => 'e-biling-22022062123059628.pdf',
                'tahap' => 'Akhir',
                'spp_gu_id' => '82d809a8-2655-4e90-87b1-15524001e902',
                'created_at' => '2022-06-21 23:58:05',
                'updated_at' => '2022-06-21 23:58:05',
            ),
        ));
        
        
    }
}