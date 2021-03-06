<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Storage::deleteDirectory('/dokumen_spp_gu');
        // Storage::deleteDirectory('/dokumen_spp_ls');
        // Storage::deleteDirectory('/dokumen_spp_tu');
        // Storage::deleteDirectory('/dokumen_spp_up');
        // Storage::deleteDirectory('/profil');
        // Storage::deleteDirectory('/surat_penolakan_spp_gu');
        // Storage::deleteDirectory('/surat_penolakan_spp_ls');
        // Storage::deleteDirectory('/surat_penolakan_spp_tu');
        // Storage::deleteDirectory('/surat_penolakan_spp_up');
        // Storage::deleteDirectory('/tanda_tangan');

        // Storage::makeDirectory('/dokumen_spp_gu');
        // Storage::makeDirectory('/dokumen_spp_ls');
        // Storage::makeDirectory('/dokumen_spp_tu');
        // Storage::makeDirectory('/dokumen_spp_up');
        // Storage::makeDirectory('/profil');
        // Storage::makeDirectory('/surat_penolakan_spp_gu');
        // Storage::makeDirectory('/surat_penolakan_spp_ls');
        // Storage::makeDirectory('/surat_penolakan_spp_tu');
        // Storage::makeDirectory('/surat_penolakan_spp_up');
        // Storage::makeDirectory('/tanda_tangan');

        // File::copyDirectory(
        //     public_path('file_dummy'),
        //     storage_path('app/public/')
        // );

        // \App\Models\User::factory(10)->create();
        $this->call(BiroOrganisasiTableSeeder::class);
        $this->call(DaftarDokumenSppUpTableSeeder::class);
        $this->call(DaftarDokumenSppTuTableSeeder::class);
        $this->call(DaftarDokumenSppLsTableSeeder::class);
        $this->call(TahunTableSeeder::class);
        $this->call(DaftarDokumenSppGuTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProfilTableSeeder::class);
        $this->call(SpdTableSeeder::class);
        $this->call(KegiatanTableSeeder::class);
        $this->call(ProgramTableSeeder::class);
        $this->call(KegiatanSppTableSeeder::class);
        $this->call(ProgramSppTableSeeder::class);
        $this->call(SppUpTableSeeder::class);
        $this->call(RiwayatSppUpTableSeeder::class);
        $this->call(DokumenSppUpTableSeeder::class);
        $this->call(SppTuTableSeeder::class);
        $this->call(RiwayatSppTuTableSeeder::class);
        $this->call(DokumenSppTuTableSeeder::class);
        $this->call(SppLsTableSeeder::class);
        $this->call(RiwayatSppLsTableSeeder::class);
        $this->call(DokumenSppLsTableSeeder::class);
        $this->call(SppGuTableSeeder::class);
        $this->call(RiwayatSppGuTableSeeder::class);
        $this->call(DokumenSppGuTableSeeder::class);
        $this->call(TentangSeeder::class);
    }
}
