<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            if ($request->routeIs('password.change') || $request->routeIs('password.update.forced') || $request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
