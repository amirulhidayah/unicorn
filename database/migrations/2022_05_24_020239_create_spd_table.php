<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spd', function (Blueprint $table) {
            $table->id();
            $table->uuid('kegiatan_id');
            $table->uuid('biro_organisasi_id');
            $table->uuid('tahun_id');
            $table->bigInteger('tw1');
            $table->bigInteger('tw2');
            $table->bigInteger('tw3');
            $table->bigInteger('tw4');
            $table->softDeletes();
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
        Schema::dropIfExists('spd');
    }
}
