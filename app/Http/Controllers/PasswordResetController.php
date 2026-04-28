<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetLinkMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class PasswordResetController extends Controller
{
    private int $tokenExpiryMinutes = 60;

    public function showForgotForm(): View
    {
        return view('auth.forgot_password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($validated['email']));

        $user = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if ($user && !str_ends_with($email, '.ac.id')) {
            return back()->withInput()->with('forgot_error', 'Akses ditolak. Hanya email kampus (.ac.id) yang dapat digunakan.');
        }

        if ($user) {
            $rawToken = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $rawToken);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $hashedToken,
                    'created_at' => now(),
                ]
            );

            $resetLink = route('password.reset', ['token' => $rawToken, 'email' => $email]);

            try {
                Mail::to($email, $user->nama_lengkap ?? $email)
                    ->send(new PasswordResetLinkMail(
                        namaPengguna: $user->nama_lengkap ?? $email,
                        resetLink: $resetLink,
                        expiryMinutes: $this->tokenExpiryMinutes,
                    ));
            } catch (Throwable $e) {
                report($e);

                if (app()->environment('local')) {
                    return back()
                        ->with('forgot_success', 'SMTP lokal tidak aktif. Untuk testing, gunakan link reset di bawah ini. Link tetap berlaku selama 60 menit.')
                        ->with('reset_link', $resetLink)
                        ->with('mail_fallback', true);
                }

                return back()
                    ->withInput()
                    ->with('forgot_error', 'Gagal mengirim email reset kata sandi. Periksa konfigurasi email terlebih dahulu.');
            }

            return back()->with('forgot_success', 'Jika email Anda terdaftar, link reset kata sandi telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        }

        return back()->with('forgot_success', 'Jika email Anda terdaftar, link reset kata sandi telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
    }

    public function showResetForm(Request $request, string $token): View
    {
        $email = strtolower(trim((string) $request->query('email', '')));

        $validation = $this->validateToken($email, $token);

        return view('auth.reset_password', [
            'isValid' => $validation['valid'],
            'errorMessage' => $validation['message'],
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = strtolower(trim($validated['email']));
        $token = $validated['token'];

        $validation = $this->validateToken($email, $token);
        if (!$validation['valid']) {
            return back()->withInput()->with('reset_error', $validation['message']);
        }

        $user = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (!$user) {
            return redirect()->route('password.forgot')->with('forgot_error', 'Akun tidak ditemukan.');
        }

        DB::table('users')
            ->where('id_user', $user->id_user)
            ->update([
                'password_hash' => password_hash($validated['password'], PASSWORD_ARGON2ID),
            ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')->with('reset_success', 'Kata sandi berhasil diubah. Silakan login dengan kata sandi baru.');
    }

    private function validateToken(string $email, string $token): array
    {
        if ($email === '' || $token === '') {
            return [
                'valid' => false,
                'message' => 'Link reset tidak valid.',
            ];
        }

        $tokenRow = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$tokenRow) {
            return [
                'valid' => false,
                'message' => 'Link reset tidak valid atau sudah digunakan.',
            ];
        }

        $createdAt = Carbon::parse($tokenRow->created_at);
        $isExpired = $createdAt->diffInMinutes(now()) > $this->tokenExpiryMinutes;
        if ($isExpired) {
            return [
                'valid' => false,
                'message' => 'Link reset sudah kadaluarsa. Silakan ajukan ulang.',
            ];
        }

        $hashedIncomingToken = hash('sha256', $token);
        if (!hash_equals((string) $tokenRow->token, $hashedIncomingToken)) {
            return [
                'valid' => false,
                'message' => 'Token reset tidak cocok.',
            ];
        }

        return [
            'valid' => true,
            'message' => null,
        ];
    }
}
