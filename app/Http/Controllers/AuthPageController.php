<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AuthPageController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $role = (string) (Auth::user()->role ?? '');

            return in_array($role, ['superadmin', 'admin', 'Admin', 'SuperAdmin'], true)
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:3'],
        ], [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.min' => 'Kata sandi minimal 3 karakter.',
        ]);

        $identity = strtolower(trim($validated['username']));

        $user = DB::table('users')
            ->where(function ($query) use ($identity) {
                $query->whereRaw('LOWER(username) = ?', [$identity])
                    ->orWhereRaw('LOWER(email) = ?', [$identity])
                    ->orWhereRaw('LOWER(nim) = ?', [$identity]);
            })
            ->first();

        if (!$user) {
            return back()->withInput()->with('login_error', 'Akun tidak ditemukan.');
        }

        if ($user->status === 'pending') {
            return back()->withInput()->with('login_error', 'Akun Anda masih menunggu persetujuan admin. Silakan coba lagi nanti.');
        }

        if ($user->status === 'rejected') {
            return back()->withInput()->with('login_error', 'Akun Anda ditolak oleh admin. Hubungi admin untuk informasi lebih lanjut.');
        }

        if (!password_verify($validated['password'], $user->password_hash)) {
            return back()->withInput()->with('login_error', 'Username atau password salah.');
        }

        $request->session()->regenerate();
        $request->session()->put('auth_user', [
            'user_id' => $user->id_user,
            'id_user' => $user->id_user,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'nama_lengkap' => $user->nama_lengkap,
            'login_time' => now()->timestamp,
        ]);

        $authUser = User::query()->find($user->id_user);
        if ($authUser) {
            Auth::login($authUser, false);
        }

        if ($request->boolean('remember')) {
            cookie()->queue(cookie('username', $user->username, 60 * 24 * 30, null, null, false, true, false, 'Strict'));
        }

        $role = (string) ($user->role ?? '');
        if (in_array($role, ['superadmin', 'admin', 'Admin', 'SuperAdmin'], true)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'min:3'],
            'nomor_induk' => ['required', 'string', 'min:5'],
            'username' => ['required', 'string', 'min:3', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'confirmPassword' => ['required', 'same:password'],
        ], [
            'nama_lengkap.min' => 'Nama lengkap harus diisi dan minimal 3 karakter.',
            'nomor_induk.min' => 'Nomor induk harus diisi dan minimal 5 karakter.',
            'username.min' => 'Username harus diisi dan minimal 3 karakter.',
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
            'email.email' => 'Email tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
            'confirmPassword.same' => 'Password dan konfirmasi tidak sama.',
        ]);

        $normalizedFullname = strtolower(trim($validated['nama_lengkap']));
        $normalizedEmail = strtolower(trim($validated['email']));
        $normalizedUsername = strtolower(trim($validated['username']));

        $existing = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [$normalizedEmail])
            ->orWhereRaw('LOWER(username) = ?', [$normalizedUsername])
            ->orWhereRaw('LOWER(nama_lengkap) = ?', [$normalizedFullname])
            ->first();

        if ($existing) {
            $existingEmail = strtolower(trim((string) $existing->email));
            $existingUsername = strtolower(trim((string) $existing->username));
            $existingFullname = strtolower(trim((string) $existing->nama_lengkap));

            if ($existingEmail === $normalizedEmail && $existingUsername === $normalizedUsername && $existingFullname === $normalizedFullname) {
                return back()->withInput()->with('register_error', 'Nama, email, dan username sudah terdaftar.');
            }
            if ($existingFullname === $normalizedFullname && $existingEmail === $normalizedEmail && $existingUsername !== $normalizedUsername) {
                return back()->withInput()->with('register_error', 'Nama & email sudah terdaftar.');
            }
            if ($existingFullname === $normalizedFullname && $existingUsername === $normalizedUsername && $existingEmail !== $normalizedEmail) {
                return back()->withInput()->with('register_error', 'Nama & username sudah terdaftar.');
            }
            if ($existingEmail === $normalizedEmail && $existingUsername === $normalizedUsername) {
                return back()->withInput()->with('register_error', 'Email dan username sudah terdaftar.');
            }
            if ($existingFullname === $normalizedFullname) {
                return back()->withInput()->with('register_error', 'Nama lengkap sudah terdaftar.');
            }
            if ($existingEmail === $normalizedEmail) {
                return back()->withInput()->with('register_error', 'Email sudah terdaftar.');
            }
            if ($existingUsername === $normalizedUsername) {
                return back()->withInput()->with('register_error', 'Username sudah terdaftar.');
            }

            return back()->withInput()->with('register_error', 'Email atau username sudah terdaftar.');
        }

        DB::table('users')->insert([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nim' => $validated['nomor_induk'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password_hash' => password_hash($validated['password'], PASSWORD_ARGON2ID),
            'role' => 'mahasiswa',
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return back()->with('register_success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan admin sebelum bisa login.');
    }

    public function checkUserExists(Request $request): JsonResponse
    {
        $username = strtolower(trim((string) $request->input('username', '')));
        $email = strtolower(trim((string) $request->input('email', '')));
        $fullName = strtolower(trim((string) $request->input('nama_lengkap', '')));

        $fields = [];

        if ($username !== '' && DB::table('users')->whereRaw('LOWER(username) = ?', [$username])->exists()) {
            $fields[] = 'username';
        }

        if ($email !== '' && DB::table('users')->whereRaw('LOWER(email) = ?', [$email])->exists()) {
            $fields[] = 'email';
        }

        if ($fullName !== '' && DB::table('users')->whereRaw('LOWER(nama_lengkap) = ?', [$fullName])->exists()) {
            $fields[] = 'nama_lengkap';
        }

        return response()->json([
            'success' => true,
            'exists' => !empty($fields),
            'fields' => $fields,
        ]);
    }

    private const FIREBASE_WEB_API_KEY = 'AIzaSyBxnwFSK4HFjCpHu2ZtTT3dqExVhCiYStM';

    public function googleAuth(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $firebaseUser = $this->resolveFirebaseUser($validated['token']);

        if (!$firebaseUser) {
            return response()->json([
                'success' => false,
                'message' => 'Token Google tidak valid atau sudah kedaluwarsa.',
            ], 422);
        }

        $email = strtolower(trim((string) ($firebaseUser['email'] ?? '')));
        if ($email === '') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Google tidak memiliki email yang valid.',
            ], 422);
        }

        $existingUser = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        $displayName = trim((string) ($firebaseUser['displayName'] ?? ''));
        $photoUrl = trim((string) ($firebaseUser['photoUrl'] ?? ''));
        $firebaseUid = (string) ($firebaseUser['localId'] ?? '');
        $isEmailVerified = (bool) ($firebaseUser['emailVerified'] ?? false);

        $localUser = DB::transaction(function () use ($email, $displayName, $photoUrl, $firebaseUid, $existingUser) {

            if ($existingUser && in_array(strtolower((string) $existingUser->status), ['rejected'], true)) {
                return null;
            }

            $baseName = $displayName !== '' ? $displayName : Str::before($email, '@');
            $generatedUsername = $this->generateUniqueUsername($baseName, $email, $existingUser?->id_user ?? null);

            if ($existingUser) {
                DB::table('users')
                    ->where('id_user', $existingUser->id_user)
                    ->update([
                        'nama_lengkap' => $displayName !== '' ? $displayName : $existingUser->nama_lengkap,
                        'username' => $existingUser->username ?: $generatedUsername,
                        'status' => 'approved',
                    ]);

                return DB::table('users')
                    ->where('id_user', $existingUser->id_user)
                    ->first();
            }

            $id = DB::table('users')->insertGetId([
                'nama_lengkap' => $displayName !== '' ? $displayName : $generatedUsername,
                'nim' => null,
                'email' => $email,
                'username' => $generatedUsername,
                'password_hash' => Hash::make(Str::random(80)),
                'role' => 'mahasiswa',
                'status' => 'approved',
                'created_at' => now(),
            ], 'id_user');

            return DB::table('users')->where('id_user', $id)->first();
        });

        if (!$localUser) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Google ini ditolak oleh admin.',
            ], 403);
        }

        $request->session()->regenerate();
        $request->session()->put('auth_user', [
            'user_id' => $localUser->id_user,
            'id_user' => $localUser->id_user,
            'username' => $localUser->username,
            'email' => $localUser->email,
            'role' => $localUser->role,
            'nama_lengkap' => $localUser->nama_lengkap,
            'firebase_uid' => $firebaseUid,
            'email_verified' => $isEmailVerified,
            'photo_url' => $photoUrl,
            'login_time' => now()->timestamp,
        ]);

        $authUser = User::query()->find($localUser->id_user);
        if ($authUser) {
            Auth::login($authUser, false);
        }

        $redirectTo = in_array((string) $localUser->role, ['superadmin', 'admin', 'Admin', 'SuperAdmin'], true)
            ? route('admin.dashboard')
            : route('dashboard');

        return response()->json([
            'success' => true,
            'message' => 'Login Google berhasil.',
            'redirect' => $redirectTo,
            'user' => [
                'id_user' => $localUser->id_user,
                'username' => $localUser->username,
                'email' => $localUser->email,
                'nama_lengkap' => $localUser->nama_lengkap,
                'role' => $localUser->role,
            ],
        ]);
    }

    private function resolveFirebaseUser(string $idToken): ?array
    {
        $response = Http::asJson()->post(sprintf(
            'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=%s',
            self::FIREBASE_WEB_API_KEY
        ), [
            'idToken' => $idToken,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $users = $response->json('users');

        if (!is_array($users) || empty($users[0]) || !is_array($users[0])) {
            return null;
        }

        return $users[0];
    }

    private function generateUniqueUsername(string $name, string $email, ?int $ignoreUserId = null): string
    {
        $base = Str::slug($name, '_');

        if ($base === '') {
            $base = Str::slug(Str::before($email, '@'), '_');
        }

        if ($base === '') {
            $base = 'user';
        }

        $candidate = Str::limit($base, 50, '');
        $suffix = 1;

        while ($this->usernameExists($candidate, $ignoreUserId)) {
            $suffix++;
            $candidate = Str::limit($base, max(1, 50 - strlen((string) $suffix) - 1), '') . '_' . $suffix;
        }

        return $candidate;
    }

    private function usernameExists(string $username, ?int $ignoreUserId = null): bool
    {
        $query = DB::table('users')->whereRaw('LOWER(username) = ?', [strtolower($username)]);

        if ($ignoreUserId) {
            $query->where('id_user', '!=', $ignoreUserId);
        }

        return $query->exists();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget('auth_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        cookie()->queue(cookie()->forget('username'));

        return redirect()->route('login');
    }
}
