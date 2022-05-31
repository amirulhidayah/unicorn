<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TahunTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tahun')->delete();
        
        \DB::table('tahun')->insert(array (
            0 => 
            array (
                'id' => '24255963-ea6a-49fc-8319-1402afdc2fef',
                'tahun' => '2022',
                'deleted_at' => NULL,
                'created_at' => '2022-05-23 13:33:47',
                'updated_at' => '2022-05-23 13:33:47',
            ),
            1 => 
            array (
                'id' => '8fef08db-e1bf-4a1f-8bd2-9809d5e60426',
                'tahun' => '2021',
                'deleted_at' => NULL,
                'created_at' => '2022-05-23 13:33:43',
                'updated_at' => '2022-05-23 13:33:43',
            ),
        ));
        
        
    }
}