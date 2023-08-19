<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatSppLsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_spp_ls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spp_ls_id');
            $table->uuid('user_id');
            $table->integer('tahap_riwayat')->default(1);
            $table->string('role')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->text('alasan')->nullable();
            $table->text('surat_penolakan')->nullable();
            $table->text('surat_pengembalian')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('riwayat_spp_ls');
    }
}
