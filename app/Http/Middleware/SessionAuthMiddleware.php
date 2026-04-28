<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $sessionUser = $request->session()->get('auth_user');
        $isLoggedIn = is_array($sessionUser) && !empty($sessionUser['id_user']);

        if (!$isLoggedIn && Auth::check()) {
            $authUser = Auth::user();

            $sessionUser = [
                'user_id' => $authUser->id_user,
                'id_user' => $authUser->id_user,
                'username' => $authUser->username,
                'email' => $authUser->email,
                'role' => $authUser->role,
                'nama_lengkap' => $authUser->nama_lengkap,
                'login_time' => now()->timestamp,
            ];

            $request->session()->put('auth_user', $sessionUser);
            $isLoggedIn = true;
        }

        if (!$isLoggedIn) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
