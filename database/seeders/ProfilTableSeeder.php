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
                'id' => '1a495f28-c1d1-4581-822e-8b4a6a3e8227',
                'user_id' => 'd027ec6e-aa44-4b8f-b183-8d9a0af04322',
                'nama' => 'Putri Zelda',
                'alamat' => 'Jl. PPK',
                'jenis_kelamin' => 'Perempuan',
                'nomor_hp' => '082123231232131',
                'nip' => '09999999999999213',
                'foto' => '1652531506.png',
                'tanda_tangan' => '1652531506.png',
                'biro_organisasi_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-05-14 12:31:46',
                'updated_at' => '2022-05-14 12:31:46',
            ),
            1 => 
            array (
                'id' => '21655e22-8d33-48a3-836c-a68feb6dfd92',
                'user_id' => 'ea50a24c-f284-4913-9a2c-9d6b675848e3',
                'nama' => 'Ahmad Bruce Wayne',
                'alamat' => 'Jl. Admin',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '081234567882',
                'nip' => '08999999999999',
                'foto' => '1652531297.png',
                'tanda_tangan' => '1652531297.png',
                'biro_organisasi_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-05-14 12:28:17',
                'updated_at' => '2022-05-14 12:28:17',
            ),
            2 => 
            array (
                'id' => '2a5518d0-e56e-4c1d-b8c2-d7c7eb179021',
                'user_id' => 'ddfa22a2-5590-4c51-aafa-a1e0e649041b',
                'nama' => 'Diplo',
                'alamat' => 'Jl. ASN',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '082222222222',
                'nip' => '0877777777777',
                'foto' => '1652531365.png',
                'tanda_tangan' => '1652531365.png',
                'biro_organisasi_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-05-14 12:29:25',
                'updated_at' => '2022-05-14 12:29:25',
            ),
            3 => 
            array (
                'id' => '3bb96416-70ce-4787-b965-419f3fe2fdef',
                'user_id' => 'a87bc04e-a7f0-4bcd-9a1e-2c645aecd370',
                'nama' => 'Vinicius Junior',
                'alamat' => 'Brazil',
                'jenis_kelamin' => 'Laki-Laki',
                'nomor_hp' => '08123456789',
                'nip' => '1234567890',
                'foto' => '1654347607.png',
                'tanda_tangan' => '1654347607.png',
                'biro_organisasi_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-06-04 13:00:07',
                'updated_at' => '2022-06-04 13:00:07',
            ),
            4 => 
            array (
                'id' => 'a2f3dfad-ae9a-402b-a753-dce1ac905add',
                'user_id' => 'd61f7c8d-03ca-4e4a-9fbc-f16d17dc95bd',
                'nama' => 'Krewella',
                'alamat' => 'Jl. Bendahara',
                'jenis_kelamin' => 'Perempuan',
                'nomor_hp' => '0788888888888',
                'nip' => '09999999999999',
                'foto' => '1652531440.png',
                'tanda_tangan' => '1652531440.png',
                'biro_organisasi_id' => '68c37c05-84a4-493b-9419-35cb6d10a319',
                'created_at' => '2022-05-14 12:30:40',
                'updated_at' => '2022-05-14 12:30:40',
            ),
        ));
        
        
    }
}