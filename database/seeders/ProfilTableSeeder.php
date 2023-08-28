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
                'id' => '03bbc0b0-122b-4d88-9879-70d7ae0e0479',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
                'nama' => 'Camavinga',
                'alamat' => 'Prancis',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '08123456789012',
                'nip' => '999888777666555444333',
                'foto' => '1655106483.png',
                'tanda_tangan' => '1655106483.png',
                'sekretariat_daerah_id' => NULL,
                'created_at' => '2022-06-13 07:48:03',
                'updated_at' => '2022-06-13 07:48:03',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => '1336001b-6e9f-4e2e-9b8b-637718b1895d',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
                'nama' => 'Gal Gadot',
                'alamat' => 'Israel',
                'jenis_kelamin' => 'Perempuan',
                'nomor_hp' => '0812345678913',
                'nip' => '777666555444333222',
                'foto' => '1655107095.png',
                'tanda_tangan' => '1655107095.png',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-06-13 07:58:15',
                'updated_at' => '2022-06-13 07:58:15',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => '1bd5b9fa-7536-46c4-a5de-528cd7d9749f',
                'user_id' => 'c72754d4-253c-49a4-ba39-b9f37af256e8',
                'nama' => 'Emma Watson',
                'alamat' => 'Inggris',
                'jenis_kelamin' => 'Perempuan',
                'nomor_hp' => '0812345678916',
                'nip' => '666111333777888999',
                'foto' => '1655107256.png',
                'tanda_tangan' => '1655107256.png',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-06-13 08:00:56',
                'updated_at' => '2022-06-13 08:00:56',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => '5cb06fac-de26-4939-ac8e-2a88b2c2bad4',
                'user_id' => '044115ba-8e63-4aa5-b88d-0d32536148c3',
                'nama' => 'Soony Moore',
                'alamat' => 'Parigi',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '081354643573',
                'nip' => '999999999999999999999',
                'foto' => '1689668876.png',
                'tanda_tangan' => '1689668876.png',
                'sekretariat_daerah_id' => NULL,
                'created_at' => '2023-07-18 08:27:56',
                'updated_at' => '2023-07-18 08:27:56',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => '6973c085-d2a4-4c9e-b06a-5fe846c7b84b',
                'user_id' => 'd14cb4a0-650c-442b-a324-f489065c4a6a',
                'nama' => 'Luka Modric',
                'alamat' => 'Kroasia',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '0812345678912',
                'nip' => '333444555666777888999',
                'foto' => '1655106339.png',
                'tanda_tangan' => '1655106339.png',
                'sekretariat_daerah_id' => NULL,
                'created_at' => '2022-06-13 07:45:39',
                'updated_at' => '2022-06-13 07:45:39',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => '6d610bbc-6443-45f6-ab87-4c7b92c2d98d',
                'user_id' => '2963feae-1c78-4a83-95f2-adde905d5cb1',
                'nama' => 'Equinox',
                'alamat' => 'Skrillex',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '08111111111111',
                'nip' => '21321321313221321323',
                'foto' => '1693189382.png',
                'tanda_tangan' => '1693189382.png',
                'sekretariat_daerah_id' => 'ae151a72-90b2-4ea9-9cd3-dd261a5fc647',
                'created_at' => '2023-08-28 02:23:02',
                'updated_at' => '2023-08-28 02:23:02',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 'd5fcbf87-9fac-4fff-8c82-5bfaf09d355b',
                'user_id' => 'bd7db183-61f5-40b0-b1f2-75de801596ea',
                'nama' => 'Margot Robbie',
                'alamat' => 'Inggris',
                'jenis_kelamin' => 'Perempuan',
                'nomor_hp' => '0812345678915',
                'nip' => '555333222111999000',
                'foto' => '1655107181.png',
                'tanda_tangan' => '1655107181.png',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-06-13 07:59:41',
                'updated_at' => '2022-06-13 07:59:41',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 'eec4a3e1-13e4-40fa-896b-77e85102119d',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
                'nama' => 'Vinicius Junior',
                'alamat' => 'Brazil',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '0812345678911',
                'nip' => '222333444555666777888',
                'foto' => '1655106273.png',
                'tanda_tangan' => '1655106273.png',
                'sekretariat_daerah_id' => NULL,
                'created_at' => '2022-06-13 07:44:33',
                'updated_at' => '2022-06-13 07:44:33',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 'f76ccdbd-d37c-4bf4-919e-cd716d809591',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
                'nama' => 'Karim Benzema',
                'alamat' => 'Prancis',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '0812345678910',
                'nip' => '111222333444555666',
                'foto' => '1655107257.png',
                'tanda_tangan' => '1655107257.png',
                'sekretariat_daerah_id' => NULL,
                'created_at' => '2022-06-13 07:41:12',
                'updated_at' => '2022-06-13 07:41:12',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}