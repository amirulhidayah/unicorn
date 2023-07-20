<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSppUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spp_up', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tahun_id');
            $table->uuid('kegiatan_spp_id');
            $table->bigInteger('jumlah_anggaran');
            $table->uuid('sekretariat_daerah_id');
            $table->string('nomor_surat');
            $table->integer('tahap_riwayat')->default(1);
            $table->uuid('user_id');
            $table->text('surat_penolakan')->nullable();
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
        Schema::dropIfExists('spp_up');
    }
}
