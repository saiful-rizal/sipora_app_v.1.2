<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'mahasiswa1'],
            [
                'nama_lengkap' => 'Mahasiswa SIPORA',
                'nim' => '2026001',
                'email' => 'mahasiswa1@polije.ac.id',
                'password_hash' => password_hash('mahasiswa123', PASSWORD_ARGON2ID),
                'role' => 'mahasiswa',
                'status' => 'approved',
                'created_at' => now(),
            ]
        );
    }
}
