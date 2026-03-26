<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin', 'admin', 'mahasiswa') DEFAULT 'mahasiswa'");
    }

    public function down(): void
    {
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'superadmin'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'mahasiswa') DEFAULT 'mahasiswa'");
    }
};
