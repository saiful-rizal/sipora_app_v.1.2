<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'superadmin'],
            [
                'nama_lengkap' => 'Super Admin SIPORA',
                'nim' => null,
                'email' => 'superadmin@sipora.ac.id',
                'password_hash' => password_hash('SuperAdmin123!', PASSWORD_ARGON2ID),
                'role' => 'superadmin',
                'status' => 'approved',
                'created_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['username' => 'adminbiasa'],
            [
                'nama_lengkap' => 'Admin SIPORA',
                'nim' => null,
                'email' => 'admin@sipora.ac.id',
                'password_hash' => password_hash('AdminBiasa123!', PASSWORD_ARGON2ID),
                'role' => 'admin',
                'status' => 'approved',
                'created_at' => now(),
            ]
        );
    }
}
