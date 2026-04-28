<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('actor_id')->nullable();
            $table->integer('doc_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('message')->nullable();
            $table->string('icon_type', 50)->nullable();
            $table->string('icon_class', 100)->nullable();
            $table->string('status_name', 100)->nullable();
            $table->boolean('is_read')->default(false);
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->increments('id_notif');
            $table->unsignedInteger('user_id');
            $table->string('judul', 255);
            $table->text('isi')->nullable();
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->timestamp('waktu')->useCurrent();

            $table->index('user_id');
            $table->foreign('user_id')->references('id_user')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('notifications');
    }
};
