<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanSppLsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_spp_ls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spp_ls_id');
            $table->uuid('kegiatan_id');
            $table->bigInteger('anggaran_digunakan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatan_spp_ls');
    }
}
