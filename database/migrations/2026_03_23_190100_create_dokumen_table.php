<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->increments('dokumen_id');
            $table->string('judul', 255);
            $table->text('abstrak')->nullable();
            $table->integer('turnitin');
            $table->string('turnitin_file', 200)->nullable();
            $table->string('kata_kunci', 255)->nullable();
            $table->string('file_path', 255);
            $table->timestamp('tgl_unggah')->nullable()->useCurrent();
            $table->unsignedInteger('uploader_id');
            $table->unsignedInteger('id_tema')->nullable();
            $table->unsignedInteger('id_jurusan')->nullable();
            $table->unsignedInteger('id_prodi')->nullable();
            $table->unsignedInteger('id_divisi')->nullable();
            $table->unsignedInteger('year_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();

            $table->index('uploader_id');
            $table->index('id_tema');
            $table->index('id_jurusan');
            $table->index('id_prodi');
            $table->index('id_divisi');
            $table->index('year_id');
            $table->index('status_id');

            $table->foreign('uploader_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_tema')->references('id_tema')->on('master_tema')->nullOnDelete();
            $table->foreign('id_jurusan')->references('id_jurusan')->on('master_jurusan')->nullOnDelete();
            $table->foreign('id_prodi')->references('id_prodi')->on('master_prodi')->nullOnDelete();
            $table->foreign('id_divisi')->references('id_divisi')->on('master_divisi')->nullOnDelete();
            $table->foreign('year_id')->references('year_id')->on('master_tahun')->nullOnDelete();
            $table->foreign('status_id')->references('status_id')->on('master_status_dokumen')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
