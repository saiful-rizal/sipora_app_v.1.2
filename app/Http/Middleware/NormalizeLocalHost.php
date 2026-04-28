<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NormalizeLocalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!app()->environment('local')) {
            return $next($request);
        }

        $host = $request->getHost();

        if (!in_array($host, ['127.0.0.1', '::1'], true)) {
            return $next($request);
        }

        $port = $request->getPort();
        $portSuffix = $port && !in_array($port, [80, 443], true) ? ':' . $port : '';

        $redirectUrl = $request->getScheme() . '://localhost' . $portSuffix . $request->getRequestUri();

        // Use 307 so POST requests (e.g. Google auth finalize) keep method and body.
        return redirect()->to($redirectUrl, 307);
    }
}
