<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanSppTuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_spp_tu', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spp_tu_id');
            $table->uuid('kegiatan_id');
            $table->bigInteger('jumlah_anggaran');
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
        Schema::dropIfExists('kegiatan_spp_tu');
    }
}
