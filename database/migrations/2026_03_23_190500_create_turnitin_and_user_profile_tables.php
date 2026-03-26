<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnitin', function (Blueprint $table) {
            $table->increments('id_turnitin');
            $table->unsignedInteger('id_divisi')->nullable();
            $table->string('turnitin_score', 10)->nullable();
            $table->string('turnitin_link', 500)->nullable();
            $table->string('file_turnitin', 500)->nullable();
            $table->unsignedInteger('uploader_id');
            $table->dateTime('created_at')->useCurrent();

            $table->index('id_divisi');
            $table->index('uploader_id');

            $table->foreign('id_divisi')->references('id_divisi')->on('master_divisi')->nullOnDelete();
            $table->foreign('uploader_id')->references('id_user')->on('users')->onDelete('cascade');
        });

        Schema::create('user_profile', function (Blueprint $table) {
            $table->increments('id_profile');
            $table->unsignedInteger('id_user');
            $table->string('foto_profil', 255)->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profile');
        Schema::dropIfExists('turnitin');
    }
};
