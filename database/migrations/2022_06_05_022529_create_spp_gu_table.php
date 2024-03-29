<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSppGuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spp_gu', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('nomor_surat');
            $table->uuid('spj_gu_id');
            $table->integer('tahap_riwayat')->default(1);
            $table->text('surat_penolakan')->nullable();
            $table->text('surat_pengembalian')->nullable();
            $table->integer('status_validasi_asn')->default(0);
            $table->text('alasan_validasi_asn')->nullable();
            $table->integer('status_validasi_ppk')->default(0);
            $table->text('alasan_validasi_ppk')->nullable();
            $table->date('tanggal_validasi_asn')->nullable();
            $table->date('tanggal_validasi_ppk')->nullable();
            $table->integer('status_validasi_akhir')->default(0);
            $table->date('tanggal_validasi_akhir')->nullable();
            $table->text('dokumen_spm')->nullable();
            $table->text('dokumen_arsip_sp2d')->nullable();
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
        Schema::dropIfExists('spp_gu');
    }
}
