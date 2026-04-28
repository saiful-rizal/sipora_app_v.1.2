<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_author', function (Blueprint $table) {
            $table->increments('dokumen_author_id');
            $table->unsignedInteger('dokumen_id');
            $table->unsignedInteger('author_id');

            $table->index('dokumen_id');
            $table->index('author_id');

            $table->foreign('dokumen_id')->references('dokumen_id')->on('dokumen')->onDelete('cascade');
            $table->foreign('author_id')->references('author_id')->on('master_author')->onDelete('cascade');
        });

        Schema::create('dokumen_keyword', function (Blueprint $table) {
            $table->increments('dokumen_keyword_id');
            $table->unsignedInteger('dokumen_id');
            $table->unsignedInteger('keyword_id');

            $table->index('dokumen_id');
            $table->index('keyword_id');

            $table->foreign('dokumen_id')->references('dokumen_id')->on('dokumen')->onDelete('cascade');
            $table->foreign('keyword_id')->references('keyword_id')->on('master_keyword')->onDelete('cascade');
        });

        Schema::create('log_review', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('dokumen_id');
            $table->unsignedInteger('reviewer_id');
            $table->timestamp('tgl_review')->nullable()->useCurrent();
            $table->text('catatan_review')->nullable();
            $table->unsignedInteger('status_sebelum')->nullable();
            $table->unsignedInteger('status_sesudah')->nullable();

            $table->index('dokumen_id');
            $table->index('reviewer_id');
            $table->index('status_sebelum');
            $table->index('status_sesudah');

            $table->foreign('dokumen_id')->references('dokumen_id')->on('dokumen')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('status_sebelum')->references('status_id')->on('master_status_dokumen')->nullOnDelete();
            $table->foreign('status_sesudah')->references('status_id')->on('master_status_dokumen')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_review');
        Schema::dropIfExists('dokumen_keyword');
        Schema::dropIfExists('dokumen_author');
    }
};
