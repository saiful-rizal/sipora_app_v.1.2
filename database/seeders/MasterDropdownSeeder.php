<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDropdownSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_sort_options')->upsert([
            ['id_sort' => 1, 'sort_key' => 'relevance', 'sort_label' => 'Relevansi', 'sort_scope' => 'search', 'sort_order' => 1],
            ['id_sort' => 2, 'sort_key' => 'newest', 'sort_label' => 'Terbaru', 'sort_scope' => 'both', 'sort_order' => 2],
            ['id_sort' => 3, 'sort_key' => 'oldest', 'sort_label' => 'Terlama', 'sort_scope' => 'both', 'sort_order' => 3],
            ['id_sort' => 4, 'sort_key' => 'title_az', 'sort_label' => 'Judul A-Z', 'sort_scope' => 'both', 'sort_order' => 4],
            ['id_sort' => 5, 'sort_key' => 'title_za', 'sort_label' => 'Judul Z-A', 'sort_scope' => 'both', 'sort_order' => 5],
        ], ['id_sort'], ['sort_key', 'sort_label', 'sort_scope', 'sort_order']);

        DB::table('master_user_roles')->upsert([
            ['id_role' => 1, 'role_key' => 'superadmin', 'role_label' => 'Super Admin', 'role_order' => 1],
            ['id_role' => 2, 'role_key' => 'admin', 'role_label' => 'Admin', 'role_order' => 2],
            ['id_role' => 3, 'role_key' => 'mahasiswa', 'role_label' => 'Mahasiswa', 'role_order' => 3],
        ], ['id_role'], ['role_key', 'role_label', 'role_order']);

        DB::table('master_user_statuses')->upsert([
            ['id_status' => 1, 'status_key' => 'pending', 'status_label' => 'Pending', 'status_order' => 1],
            ['id_status' => 2, 'status_key' => 'approved', 'status_label' => 'Approved', 'status_order' => 2],
            ['id_status' => 3, 'status_key' => 'rejected', 'status_label' => 'Rejected', 'status_order' => 3],
        ], ['id_status'], ['status_key', 'status_label', 'status_order']);
    }
}
