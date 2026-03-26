<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthPageController extends Controller
{
    public function showLogin(): View
    {
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

        if (!str_ends_with(strtolower($validated['email']), '.ac.id')) {
            return back()->withInput()->with('register_error', 'Gunakan email kampus (.ac.id) untuk mendaftar.');
        }

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

    public function googleAuth(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Google Sign-In belum dikonfigurasi pada environment ini.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('auth_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        cookie()->queue(cookie()->forget('username'));

        return redirect()->route('login');
    }
}
