<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('admin.layout', function ($view) {
            $authUser    = session('auth_user');
            $role        = strtolower($authUser['role'] ?? '');
            $displayRole = match($role) {
                'superadmin' => 'Super Admin',
                'admin'      => 'Admin',
                default      => ucfirst($role ?: 'Admin'),
            };
            $view->with([
                'displayName' => $authUser['nama_lengkap'] ?? 'Admin',
                'displayRole' => $displayRole,
            ]);
        });
    }
}
