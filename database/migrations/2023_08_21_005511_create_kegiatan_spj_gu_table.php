<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanSpjGuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_spj_gu', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spj_gu_id');
            $table->uuid('kegiatan_id');
            $table->bigInteger('anggaran_digunakan');
            $table->string('dokumen');
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
        Schema::dropIfExists('kegiatan_spj_gu');
    }
}
