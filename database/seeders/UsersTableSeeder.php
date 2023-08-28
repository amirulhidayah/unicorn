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
                'id' => '044115ba-8e63-4aa5-b88d-0d32536148c3',
                'username' => 'operatorspm',
                'password' => '$2y$10$DbaXIDX7lYnSMoHioJbzg.FLebVNsTul9hMgfolCl/topM4vfhl.y',
                'role' => 'Operator SPM',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2023-07-18 08:27:56',
                'updated_at' => '2023-07-18 08:27:56',
            ),
            1 => 
            array (
                'id' => '049e4da4-eb05-4216-a422-277703af887e',
                'username' => 'ppk',
                'password' => '$2y$10$rRRdZ/QLJjrp2vJXo.kl..zUOt/Entd4K6ZZ1zdUr7WSIRsgNOwNK',
                'role' => 'PPK',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 07:48:03',
                'updated_at' => '2022-06-13 07:48:03',
            ),
            2 => 
            array (
                'id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'username' => 'bendaharapengeluaran2',
                'password' => '$2y$10$ueH3HvKH3T32oJvXmqvCaOHWAUkQZ0FTnBMpHuhoYPaAjlAGtZqs2',
                'role' => 'Bendahara Pengeluaran',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2023-08-28 02:23:02',
                'updated_at' => '2023-08-28 03:16:58',
            ),
            3 => 
            array (
                'id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'username' => 'asn',
                'password' => '$2y$10$OLa0HqTYfAiOQNp7/XBbQOzuZ3QoorKaj0SXSiH.pIa2iW2E1xHd6',
                'role' => 'ASN Sub Bagian Keuangan',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 07:44:33',
                'updated_at' => '2022-06-13 07:44:33',
            ),
            4 => 
            array (
                'id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'username' => 'bendaharapengeluaran',
                'password' => '$2y$10$C/tkzkKt4pR0pxERElXcaOH77nekM/lr7VITcsUyjlyvfcrk17bAW',
                'role' => 'Bendahara Pengeluaran',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 07:58:15',
                'updated_at' => '2022-06-13 07:58:15',
            ),
            5 => 
            array (
                'id' => 'bd7db183-61f5-40b0-b1f2-75de801596ea',
                'username' => 'bendaharapengeluaranpembantu',
                'password' => '$2y$10$ACjnD//qDbEaGt9D8Lw4SerlprrPQqjmiAbLyQxgAjmA9U0pShYqe',
                'role' => 'Bendahara Pengeluaran Pembantu',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 07:59:41',
                'updated_at' => '2022-06-13 07:59:41',
            ),
            6 => 
            array (
                'id' => 'c72754d4-253c-49a4-ba39-b9f37af256e8',
                'username' => 'bendaharapengeluaranpembantubelanjahibah',
                'password' => '$2y$10$DfdDICEZ.KJBManMiodCrOXHHqa8jOX8tpV87S9y0P3FTkTEHe9B2',
                'role' => 'Bendahara Pengeluaran Pembantu Belanja Hibah',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 08:00:56',
                'updated_at' => '2022-06-13 08:00:56',
            ),
            7 => 
            array (
                'id' => 'd14cb4a0-650c-442b-a324-f489065c4a6a',
                'username' => 'kuasapenggunaanggaran',
                'password' => '$2y$10$N.2SfvoqUvzpdapBJQf/JeGJ2tQzs7D2vwfXQKaDwndY8vM24NRu.',
                'role' => 'Kuasa Pengguna Anggaran',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 07:45:39',
                'updated_at' => '2022-06-13 07:45:39',
            ),
            8 => 
            array (
                'id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'username' => 'admin',
                'password' => '$2y$10$jDSoFmVE15w0P7asttusKeZWPq5DlfOT6Pbash5TTHQT6xQGoBCy6',
                'role' => 'Admin',
                'is_aktif' => 1,
                'remember_token' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-06-13 07:41:12',
                'updated_at' => '2022-06-13 07:41:12',
            ),
        ));
        
        
    }
}