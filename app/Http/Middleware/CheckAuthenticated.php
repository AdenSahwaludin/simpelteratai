<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated in any guard
        if (Auth::guard('admin')->check() || Auth::guard('guru')->check() || Auth::guard('orangtua')->check()) {
            return $next($request);
        }

        return redirect('/');
    }
}
