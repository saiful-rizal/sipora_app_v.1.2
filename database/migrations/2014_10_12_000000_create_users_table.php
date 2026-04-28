<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('nama_lengkap', 100);
            $table->string('nim', 50)->nullable();
            $table->string('email', 100);
            $table->string('username', 50);
            $table->string('password_hash', 255);
            $table->enum('role', ['superadmin', 'admin', 'mahasiswa'])->default('mahasiswa');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('username');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
