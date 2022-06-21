<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SppTuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('spp_tu')->delete();
        
        \DB::table('spp_tu')->insert(array (
            0 => 
            array (
                'id' => '79c517da-adba-4bae-bbd4-ff7961054b77',
                'tahun_id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'kegiatan_spp_id' => '20fdc81e-b34c-46d3-bd1b-7947b91ebc9e',
                'jumlah_anggaran' => 23250000,
                'bulan' => 'Januari',
                'biro_organisasi_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tahap_riwayat' => 2,
                'nomor_surat' => '102/SPP-TU/06/2022',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'surat_penolakan' => 'Surat Penolakan-2022062111393234.pdf',
                'status_validasi_asn' => 1,
                'alasan_validasi_asn' => NULL,
                'status_validasi_ppk' => 1,
                'alasan_validasi_ppk' => NULL,
                'tanggal_validasi_asn' => '2022-06-21',
                'tanggal_validasi_ppk' => '2022-06-21',
                'status_validasi_akhir' => 1,
                'tanggal_validasi_akhir' => '2022-06-21',
                'created_at' => '2022-06-21 11:45:23',
                'updated_at' => '2022-06-21 12:01:09',
            ),
        ));
        
        
    }
}