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

        \DB::table('profil')->insert(array(
            0 =>
            array(
                'alamat' => 'Prancis',
                'created_at' => '2022-06-13 07:48:03',
                'deleted_at' => NULL,
                'foto' => '1655106483.png',
                'id' => '03bbc0b0-122b-4d88-9879-70d7ae0e0479',
                'jenis_kelamin' => 'Laki-Laki',
                'nama' => 'Camavinga',
                'nip' => '999888777666555444333',
                'nomor_hp' => '08123456789012',
                'sekretariat_daerah_id' => NULL,
                'tanda_tangan' => '1655106483.png',
                'updated_at' => '2022-06-13 07:48:03',
                'user_id' => '049e4da4-eb05-4216-a422-277703af887e',
            ),
            1 =>
            array(
                'alamat' => 'Israel',
                'created_at' => '2022-06-13 07:58:15',
                'deleted_at' => NULL,
                'foto' => '1655107095.png',
                'id' => '1336001b-6e9f-4e2e-9b8b-637718b1895d',
                'jenis_kelamin' => 'Perempuan',
                'nama' => 'Gal Gadot',
                'nip' => '777666555444333222',
                'nomor_hp' => '0812345678913',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tanda_tangan' => '1655107095.png',
                'updated_at' => '2022-06-13 07:58:15',
                'user_id' => 'b45916f6-02aa-4285-a51e-1c2280f5a3d0',
            ),
            2 =>
            array(
                'alamat' => 'Inggris',
                'created_at' => '2022-06-13 08:00:56',
                'deleted_at' => NULL,
                'foto' => '1655107256.png',
                'id' => '1bd5b9fa-7536-46c4-a5de-528cd7d9749f',
                'jenis_kelamin' => 'Perempuan',
                'nama' => 'Emma Watson',
                'nip' => '666111333777888999',
                'nomor_hp' => '0812345678916',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tanda_tangan' => '1655107256.png',
                'updated_at' => '2022-06-13 08:00:56',
                'user_id' => 'c72754d4-253c-49a4-ba39-b9f37af256e8',
            ),
            3 =>
            array(
                'alamat' => 'Parigi',
                'created_at' => '2023-07-18 08:27:56',
                'deleted_at' => NULL,
                'foto' => '1689668876.png',
                'id' => '5cb06fac-de26-4939-ac8e-2a88b2c2bad4',
                'jenis_kelamin' => 'Laki-Laki',
                'nama' => 'Soony Moore',
                'nip' => '999999999999999999999',
                'nomor_hp' => '081354643573',
                'sekretariat_daerah_id' => null,
                'tanda_tangan' => '1689668876.png',
                'updated_at' => '2023-07-18 08:27:56',
                'user_id' => '044115ba-8e63-4aa5-b88d-0d32536148c3',
            ),
            4 =>
            array(
                'alamat' => 'Kroasia',
                'created_at' => '2022-06-13 07:45:39',
                'deleted_at' => NULL,
                'foto' => '1655106339.png',
                'id' => '6973c085-d2a4-4c9e-b06a-5fe846c7b84b',
                'jenis_kelamin' => 'Laki-Laki',
                'nama' => 'Luka Modric',
                'nip' => '333444555666777888999',
                'nomor_hp' => '0812345678912',
                'sekretariat_daerah_id' => NULL,
                'tanda_tangan' => '1655106339.png',
                'updated_at' => '2022-06-13 07:45:39',
                'user_id' => 'd14cb4a0-650c-442b-a324-f489065c4a6a',
            ),
            5 =>
            array(
                'alamat' => 'Inggris',
                'created_at' => '2022-06-13 07:59:41',
                'deleted_at' => NULL,
                'foto' => '1655107181.png',
                'id' => 'd5fcbf87-9fac-4fff-8c82-5bfaf09d355b',
                'jenis_kelamin' => 'Perempuan',
                'nama' => 'Margot Robbie',
                'nip' => '555333222111999000',
                'nomor_hp' => '0812345678915',
                'sekretariat_daerah_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'tanda_tangan' => '1655107181.png',
                'updated_at' => '2022-06-13 07:59:41',
                'user_id' => 'bd7db183-61f5-40b0-b1f2-75de801596ea',
            ),
            6 =>
            array(
                'alamat' => 'Brazil',
                'created_at' => '2022-06-13 07:44:33',
                'deleted_at' => NULL,
                'foto' => '1655106273.png',
                'id' => 'eec4a3e1-13e4-40fa-896b-77e85102119d',
                'jenis_kelamin' => 'Laki-Laki',
                'nama' => 'Vinicius Junior',
                'nip' => '222333444555666777888',
                'nomor_hp' => '0812345678911',
                'sekretariat_daerah_id' => NULL,
                'tanda_tangan' => '1655106273.png',
                'updated_at' => '2022-06-13 07:44:33',
                'user_id' => '81a75b22-5e0a-4a57-ac63-e2c28dec2612',
            ),
            7 =>
            array(
                'alamat' => 'Prancis',
                'created_at' => '2022-06-13 07:41:12',
                'deleted_at' => NULL,
                'foto' => '1655107257.png',
                'id' => 'f76ccdbd-d37c-4bf4-919e-cd716d809591',
                'jenis_kelamin' => 'Laki-Laki',
                'nama' => 'Karim Benzema',
                'nip' => '111222333444555666',
                'nomor_hp' => '0812345678910',
                'sekretariat_daerah_id' => NULL,
                'tanda_tangan' => '1655107257.png',
                'updated_at' => '2022-06-13 07:41:12',
                'user_id' => 'efac625e-df6e-4c75-8b6a-27cece457709',
            ),
        ));
    }
}
