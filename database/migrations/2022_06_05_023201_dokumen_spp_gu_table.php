<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DokumenSppGuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_spp_gu', function (Blueprint $table) {
            $table->id();
            $table->text('nama_dokumen');
            $table->text('dokumen');
            $table->string('tahap');
            $table->uuid('spp_gu_id');
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
        Schema::dropIfExists('dokumen_spp_gu');
    }
}
