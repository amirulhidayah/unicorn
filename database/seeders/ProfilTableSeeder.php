<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProfilTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('profil')->delete();
        
        \DB::table('profil')->insert(array (
            0 => 
            array (
                'id' => 'c4710d12-8d25-4f82-bf11-fd6840edc79e',
                'user_id' => '38b565f8-eca1-4878-ba42-5197254a2301',
                'nama' => 'Admin',
                'alamat' => 'Admin',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '08123456789',
                'nip' => '123456789',
                'foto' => '1651630003.png',
                'biro_organisasi_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-05-04 02:06:43',
                'updated_at' => '2022-05-04 02:06:43',
            ),
        ));
        
        
    }
}