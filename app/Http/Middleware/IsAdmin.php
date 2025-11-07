<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user || empty($user->is_admin) || !$user->is_admin) {
            // jika bukan admin, redirect ke login admin
            return redirect()->route('admin.login')->withErrors(['email' => 'Silahkan login sebagai admin untuk mengakses halaman ini.']);
        }

        return $next($request);
    }
}
