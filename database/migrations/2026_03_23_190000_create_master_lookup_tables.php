<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_rumpun', function (Blueprint $table) {
            $table->increments('id_rumpun');
            $table->string('nama_rumpun', 255);
        });

        Schema::create('master_jurusan', function (Blueprint $table) {
            $table->increments('id_jurusan');
            $table->string('nama_jurusan', 100);
            $table->unsignedInteger('id_rumpun')->nullable();

            $table->foreign('id_rumpun')->references('id_rumpun')->on('master_rumpun');
        });

        Schema::create('master_prodi', function (Blueprint $table) {
            $table->unsignedInteger('id_jurusan');
            $table->increments('id_prodi');
            $table->string('nama_prodi', 100);

            $table->foreign('id_jurusan')->references('id_jurusan')->on('master_jurusan');
        });

        Schema::create('master_tema', function (Blueprint $table) {
            $table->increments('id_tema');
            $table->unsignedInteger('id_rumpun')->nullable();
            $table->string('kode_tema', 50)->nullable();
            $table->string('nama_tema', 100);

            $table->foreign('id_rumpun')->references('id_rumpun')->on('master_rumpun');
        });

        Schema::create('master_tahun', function (Blueprint $table) {
            $table->increments('year_id');
            $table->string('tahun', 20);
        });

        Schema::create('master_status', function (Blueprint $table) {
            $table->increments('id_status');
            $table->string('nama_status', 50);
            $table->text('deskripsi')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('master_status_dokumen', function (Blueprint $table) {
            $table->increments('status_id');
            $table->string('nama_status', 50);
        });

        Schema::create('master_divisi', function (Blueprint $table) {
            $table->increments('id_divisi');
            $table->string('nama_divisi', 100);
        });

        Schema::create('master_author', function (Blueprint $table) {
            $table->increments('author_id');
            $table->string('nama_author', 150);
        });

        Schema::create('master_keyword', function (Blueprint $table) {
            $table->increments('keyword_id');
            $table->string('nama_keyword', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_keyword');
        Schema::dropIfExists('master_author');
        Schema::dropIfExists('master_divisi');
        Schema::dropIfExists('master_status_dokumen');
        Schema::dropIfExists('master_status');
        Schema::dropIfExists('master_tahun');
        Schema::dropIfExists('master_tema');
        Schema::dropIfExists('master_prodi');
        Schema::dropIfExists('master_jurusan');
        Schema::dropIfExists('master_rumpun');
    }
};
