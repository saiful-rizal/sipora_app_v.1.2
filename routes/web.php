<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\BrowserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DocumentManagementController;
use App\Http\Controllers\DocumentExtraController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminMasterDataController;
use App\Http\Controllers\AdminDokumenController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/dashboard');

// ── AUTH ROUTES (tidak perlu login) ───────────────────────────────────────
Route::redirect('/auth', '/login');
Route::get('/login',  [AuthPageController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthPageController::class, 'login'])->name('login.submit');

Route::get('/register',  [AuthPageController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthPageController::class, 'register'])->name('register.submit');

Route::get('/forgot-password',        [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password',       [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',        [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::redirect('/lupa-password', '/forgot-password');

Route::post('/auth/login',             [AuthPageController::class, 'login'])->name('auth.login');
Route::post('/auth/register',          [AuthPageController::class, 'register'])->name('auth.register');
Route::post('/auth/check-user-exists', [AuthPageController::class, 'checkUserExists'])->name('auth.check-user');
Route::post('/auth/google-auth',       [AuthPageController::class, 'googleAuth'])->name('auth.google');
Route::post('/auth/logout',            [AuthPageController::class, 'logout'])->name('auth.logout');

// ── USER ROUTES (session.auth, tanpa prefix) ──────────────────────────────
Route::middleware(['session.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard',        [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/detail', [DashboardController::class, 'getDetail'])->name('dashboard.get-detail');

    // Upload
    Route::get('/upload',       [UploadController::class, 'index'])->name('upload.index');
    Route::post('/upload',      [UploadController::class, 'store'])->name('upload.store');
    Route::get('/upload/prodi', [UploadController::class, 'getProdi'])->name('upload.get-prodi');

    // Browser
    Route::get('/browser',        [BrowserController::class, 'index'])->name('browser.index');
    Route::get('/browser/detail', [BrowserController::class, 'getDetail'])->name('browser.get-detail');
    Route::get('/browser/prodi',  [BrowserController::class, 'getProdi'])->name('browser.get-prodi');

    // Search
    Route::get('/search',        [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/detail', [SearchController::class, 'getDetail'])->name('search.get-detail');

    // Document Management
    Route::get('/documents/my',              [DocumentManagementController::class, 'myDocuments'])->name('documents.my');
    Route::get('/documents/history',         [DocumentManagementController::class, 'uploadHistory'])->name('documents.history');
    Route::get('/documents/history/export',  [DocumentExtraController::class, 'exportHistory'])->name('documents.history.export');
    Route::get('/documents/turnitin',        [DocumentExtraController::class, 'turnitin'])->name('documents.turnitin');
    Route::get('/documents/turnitin/export', [DocumentExtraController::class, 'exportTurnitin'])->name('documents.turnitin.export');
    Route::get('/documents/{id}/detail',     [DocumentExtraController::class, 'documentDetail'])->name('documents.detail');
    Route::get('/documents/{id}/download',   [DocumentExtraController::class, 'downloadDocument'])->name('documents.download');
    Route::delete('/documents/{id}',         [DocumentManagementController::class, 'deleteDocument'])->name('documents.delete');
});

// ── ADMIN ROUTES (session.auth, prefix /admin) ────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['session.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminMasterDataController::class, 'dashboard'])->name('dashboard');
    Route::get('/master-data', fn () => redirect()->route('admin.dashboard'))->name('master-data');

    // Master Data
    Route::get('/jurusan', [AdminMasterDataController::class, 'jurusanIndex'])->name('jurusan.index');
    Route::get('/prodi',   [AdminMasterDataController::class, 'prodiIndex'])->name('prodi.index');
    Route::get('/tema',    [AdminMasterDataController::class, 'temaIndex'])->name('tema.index');
    Route::get('/users',   [AdminMasterDataController::class, 'usersIndex'])->name('users.index');

    Route::put('/users/{id}',         [AdminMasterDataController::class, 'updateUser'])->name('users.update');
    Route::post('/users/store-admin', [AdminMasterDataController::class, 'storeAdmin'])->name('users.store-admin');
    Route::delete('/users/{id}',      [AdminMasterDataController::class, 'deleteUser'])->name('users.delete');

    Route::get('/users/report', function () {
        return view('admin.users_report', [
            'activeMenu'  => 'users_report',
            'displayName' => session('auth_user.nama_lengkap') ?? 'Admin',
        ]);
    })->name('users.report');

    Route::put('/jurusan/{id}',    [AdminMasterDataController::class, 'updateJurusan'])->name('jurusan.update');
    Route::delete('/jurusan/{id}', [AdminMasterDataController::class, 'deleteJurusan'])->name('jurusan.delete');

    Route::put('/prodi/{id}',    [AdminMasterDataController::class, 'updateProdi'])->name('prodi.update');
    Route::delete('/prodi/{id}', [AdminMasterDataController::class, 'deleteProdi'])->name('prodi.delete');

    Route::put('/tema/{id}',    [AdminMasterDataController::class, 'updateTema'])->name('tema.update');
    Route::delete('/tema/{id}', [AdminMasterDataController::class, 'deleteTema'])->name('tema.delete');

    // Profile
    Route::get('/profile',           [AdminMasterDataController::class, 'profile'])->name('profile');
    Route::post('/profile/update',   [AdminMasterDataController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AdminMasterDataController::class, 'updatePassword'])->name('profile.password');

    // ── Dokumen Admin ──────────────────────────────────────────────────────
    Route::get('/documents',                 [AdminDokumenController::class, 'index'])->name('documents.index');
    Route::get('/documents/{id}/detail',     [AdminDokumenController::class, 'detail'])->name('dokumen.detail');
    Route::put('/documents/{id}/approve',    [AdminDokumenController::class, 'approve'])->name('dokumen.approve');
    Route::put('/documents/{id}/reject',     [AdminDokumenController::class, 'reject'])->name('dokumen.reject');
    Route::put('/documents/{id}/revoke',     [AdminDokumenController::class, 'revoke'])->name('dokumen.revoke');
    Route::delete('/documents/{id}/destroy', [AdminDokumenController::class, 'destroy'])->name('dokumen.destroy');
    // ──────────────────────────────────────────────────────────────────────

    // Report Views
    Route::get('/documents/report', function () {
        return view('admin.documents_report', [
            'activeMenu'  => 'documents_report',
            'displayName' => session('auth_user.nama_lengkap') ?? 'Admin',
        ]);
    })->name('documents.report');
});

// ── LOGOUT ────────────────────────────────────────────────────────────────
Route::middleware(['auth'])->post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
