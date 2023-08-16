<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppLsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('spp_ls')->delete();

        \DB::table('spp_ls')->insert(array(
            0 =>
            array(
                'alasan_validasi_asn' => NULL,
                'alasan_validasi_ppk' => NULL,
                'anggaran_digunakan' => 230000,
                'bulan' => 'Januari',
                'created_at' => '2023-07-26 06:12:34',
                'dokumen_arsip_sp2d' => '1690352111.pdf',
                'dokumen_spm' => '1690352105.pdf',
                'id' => '2586e4f1-d998-4f27-8def-1e935b1ccc3f',
                'kategori' => 'Belanja Hibah',
                'kegiatan_id' => '35d37341-1b52-41ca-811d-1eb53d045837',
                'nomor_surat' => 'SPP-LS',
                'sekretariat_daerah_id' => '68d47270-c3fd-44a0-9033-0d5c4259a756',
                'status_validasi_akhir' => 1,
                'status_validasi_asn' => 1,
                'status_validasi_ppk' => 1,
                'surat_penolakan' => NULL,
                'tahap_riwayat' => 1,
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'tanggal_validasi_akhir' => '2023-07-26',
                'tanggal_validasi_asn' => '2023-07-26',
                'tanggal_validasi_ppk' => '2023-07-26',
                'updated_at' => '2023-07-26 06:15:11',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
        ));
    }
}
