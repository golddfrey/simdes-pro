<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfKepalaAuthenticated
{
    /**
     * Handle an incoming request.
     * If kepala_keluarga_id present in session, redirect to kepala dashboard.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('kepala_keluarga_id')) {
            return redirect()->route('kepala.dashboard');
        }

        return $next($request);
    }
}
