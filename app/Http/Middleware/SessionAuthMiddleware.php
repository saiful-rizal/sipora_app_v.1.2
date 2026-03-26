<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $sessionUser = $request->session()->get('auth_user');
        $isLoggedIn = is_array($sessionUser) && !empty($sessionUser['id_user']);

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
