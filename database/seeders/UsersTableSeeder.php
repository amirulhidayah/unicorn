<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => '38b565f8-eca1-4878-ba42-5197254a2301',
                'email' => 'admin@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$TF9bSG1Gw7NxnvDHW02AaORS.9boQnOtSaTN4K4uLlf9wTo3YexWW',
                'role' => 'Admin',
                'remember_token' => NULL,
                'created_at' => '2022-05-04 02:06:43',
                'updated_at' => '2022-05-04 02:06:43',
            ),
        ));
        
        
    }
}