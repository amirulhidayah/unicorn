<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(BiroOrganisasiTableSeeder::class);
        $this->call(DaftarDokumenSppUpTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProfilTableSeeder::class);
        $this->call(DaftarDokumenSppTuTableSeeder::class);
        $this->call(DaftarDokumenSppLsTableSeeder::class);
        $this->call(TahunTableSeeder::class);
    }
}
