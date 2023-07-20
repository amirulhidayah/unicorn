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
                'created_at' => '2023-07-18 08:27:56',
                'deleted_at' => NULL,
                'id' => '044115ba-8e63-4aa5-b88d-0d32536148c3',
                'is_aktif' => 1,
                'password' => '$2y$10$DbaXIDX7lYnSMoHioJbzg.FLebVNsTul9hMgfolCl/topM4vfhl.y',
                'remember_token' => NULL,
                'role' => 'Operator SPM',
                'updated_at' => '2023-07-18 08:27:56',
                'username' => 'operatorspm',
            ),
            1 => 
            array (
                'created_at' => '2022-06-13 07:48:03',
                'deleted_at' => NULL,
                'id' => '049e4da4-eb05-4216-a422-277703af887e',
                'is_aktif' => 1,
                'password' => '$2y$10$rRRdZ/QLJjrp2vJXo.kl..zUOt/Entd4K6ZZ1zdUr7WSIRsgNOwNK',
                'remember_token' => NULL,
                'role' => 'PPK',
                'updated_at' => '2022-06-13 07:48:03',
                'username' => 'ppk',
            ),
            2 => 
            array (
                'created_at' => '2022-06-13 07:44:33',
                'deleted_at' => NULL,
                'id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'is_aktif' => 1,
                'password' => '$2y$10$OLa0HqTYfAiOQNp7/XBbQOzuZ3QoorKaj0SXSiH.pIa2iW2E1xHd6',
                'remember_token' => NULL,
                'role' => 'ASN Sub Bagian Keuangan',
                'updated_at' => '2022-06-13 07:44:33',
                'username' => 'asn',
            ),
            3 => 
            array (
                'created_at' => '2022-06-13 07:58:15',
                'deleted_at' => NULL,
                'id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'is_aktif' => 1,
                'password' => '$2y$10$C/tkzkKt4pR0pxERElXcaOH77nekM/lr7VITcsUyjlyvfcrk17bAW',
                'remember_token' => NULL,
                'role' => 'Bendahara Pengeluaran',
                'updated_at' => '2022-06-13 07:58:15',
                'username' => 'bendaharapengeluaran',
            ),
            4 => 
            array (
                'created_at' => '2022-06-13 07:59:41',
                'deleted_at' => NULL,
                'id' => 'bd7db183-61f5-40b0-b1f2-75de801596ea',
                'is_aktif' => 1,
                'password' => '$2y$10$ACjnD//qDbEaGt9D8Lw4SerlprrPQqjmiAbLyQxgAjmA9U0pShYqe',
                'remember_token' => NULL,
                'role' => 'Bendahara Pengeluaran Pembantu',
                'updated_at' => '2022-06-13 07:59:41',
                'username' => 'bendaharapengeluaranpembantu',
            ),
            5 => 
            array (
                'created_at' => '2022-06-13 08:00:56',
                'deleted_at' => NULL,
                'id' => 'c72754d4-253c-49a4-ba39-b9f37af256e8',
                'is_aktif' => 1,
                'password' => '$2y$10$DfdDICEZ.KJBManMiodCrOXHHqa8jOX8tpV87S9y0P3FTkTEHe9B2',
                'remember_token' => NULL,
                'role' => 'Bendahara Pengeluaran Pembantu Belanja Hibah',
                'updated_at' => '2022-06-13 08:00:56',
                'username' => 'bendaharapengeluaranpembantubelanjahibah',
            ),
            6 => 
            array (
                'created_at' => '2022-06-13 07:45:39',
                'deleted_at' => NULL,
                'id' => 'd14cb4a0-650c-442b-a324-f489065c4a6a',
                'is_aktif' => 1,
                'password' => '$2y$10$N.2SfvoqUvzpdapBJQf/JeGJ2tQzs7D2vwfXQKaDwndY8vM24NRu.',
                'remember_token' => NULL,
                'role' => 'Kuasa Pengguna Anggaran',
                'updated_at' => '2022-06-13 07:45:39',
                'username' => 'kuasapenggunaanggaran',
            ),
            7 => 
            array (
                'created_at' => '2022-06-13 07:41:12',
                'deleted_at' => NULL,
                'id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'is_aktif' => 1,
                'password' => '$2y$10$jDSoFmVE15w0P7asttusKeZWPq5DlfOT6Pbash5TTHQT6xQGoBCy6',
                'remember_token' => NULL,
                'role' => 'Admin',
                'updated_at' => '2022-06-13 07:41:12',
                'username' => 'admin',
            ),
        ));
        
        
    }
}