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
                'id' => 'd027ec6e-aa44-4b8f-b183-8d9a0af04322',
                'email' => 'ppk@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$bDm96iniJtSYROAkh59/t.1AcUImZN46aBXM3FhhaVNzZrahOccQi',
                'role' => 'PPK',
                'remember_token' => NULL,
                'created_at' => '2022-05-14 12:31:46',
                'updated_at' => '2022-05-14 12:31:46',
            ),
            1 => 
            array (
                'id' => 'd61f7c8d-03ca-4e4a-9fbc-f16d17dc95bd',
                'email' => 'bendaharapembantu@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$cD0.B9cVhYqv7OUzEh3tJuAwRK/zAsgnQZkFSOQl3oapHOzQjinJu',
                'role' => 'Bendahara Pembantu',
                'remember_token' => NULL,
                'created_at' => '2022-05-14 12:30:40',
                'updated_at' => '2022-05-14 12:30:40',
            ),
            2 => 
            array (
                'id' => 'ddfa22a2-5590-4c51-aafa-a1e0e649041b',
                'email' => 'asn@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$7lH.fcRWqcGKYK2ActWQ5O2GyfqvaYbdbr3eOQs9hc/oPWTQl49B.',
                'role' => 'ASN Sub Bagian Keuangan',
                'remember_token' => NULL,
                'created_at' => '2022-05-14 12:29:25',
                'updated_at' => '2022-05-14 12:29:25',
            ),
            3 => 
            array (
                'id' => 'ea50a24c-f284-4913-9a2c-9d6b675848e3',
                'email' => 'admin@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$1PktwM5ROVSYrcKtwyYNUOgsNyxY1W3bDOhUBDIZg.ix0TYIKHnEK',
                'role' => 'Admin',
                'remember_token' => NULL,
                'created_at' => '2022-05-14 12:28:17',
                'updated_at' => '2022-05-14 12:28:17',
            ),
        ));
        
        
    }
}