<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TentangSeeder extends Seeder
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
                'id' => '049e4da4-eb05-4216-a422-277703af832x',
                'isi' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate iste nemo doloribus neque minima tempora architecto esse sunt voluptatum veniam.'
            ]
        ];

        DB::table('tentang')->insert($data);
    }
}
