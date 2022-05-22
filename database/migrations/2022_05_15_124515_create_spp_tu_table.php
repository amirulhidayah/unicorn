<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSppTuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spp_tu', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('nomor_surat');
            $table->bigInteger('jumlah_nominal');
            $table->uuid('user_id');
            $table->integer('status_validasi_asn')->default(0);
            $table->text('alasan_validasi_asn')->nullable();
            $table->text('surat_penolakan_asn')->nullable();
            $table->integer('status_validasi_ppk')->default(0);
            $table->text('alasan_validasi_ppk')->nullable();
            $table->text('surat_penolakan_ppk')->nullable();
            $table->date('tanggal_validasi_asn')->nullable();
            $table->date('tanggal_validasi_ppk')->nullable();
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
        Schema::dropIfExists('spp_tu');
    }
}
