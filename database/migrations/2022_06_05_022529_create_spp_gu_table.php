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
            $table->uuid('tahun_id');
            $table->uuid('kegiatan_dpa_id');
            $table->uuid('sekretariat_daerah_id');
            $table->integer('tahap_riwayat')->default(1);
            $table->string('bulan');
            $table->bigInteger('anggaran_digunakan');
            $table->string('nomor_surat');
            $table->uuid('user_id');
            $table->string('tahap')->default('Awal');
            $table->text('surat_penolakan')->nullable();
            $table->integer('status_validasi_asn')->default(0);
            $table->text('alasan_validasi_asn')->nullable();
            $table->integer('status_validasi_ppk')->default(0);
            $table->text('alasan_validasi_ppk')->nullable();
            $table->date('tanggal_validasi_asn')->nullable();
            $table->date('tanggal_validasi_ppk')->nullable();
            $table->integer('status_validasi_akhir')->default(0);
            $table->date('tanggal_validasi_akhir')->nullable();
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
