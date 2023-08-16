<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSppLsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '518dc47f-32fb-4304-960f-942780d2f091',
                'nama' => 'Belanja Gaji'
            ],
            [
                'id' => '518dc47f-32fb-4304-960f-942780d2f092',
                'nama' => 'Belanja Barang Jasa'
            ],
            [
                'id' => '518dc47f-32fb-4304-960f-942780d2f093',
                'nama' => 'Belanja Pihak 3 Lainnya'
            ],
            [
                'id' => '518dc47f-32fb-4304-960f-942780d2f094',
                'nama' => 'Pembiayaan'
            ],
        ];

        DB::table('kategori_spp_ls')->insert($data);
    }
}
