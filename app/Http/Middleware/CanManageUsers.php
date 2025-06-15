<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanManageUsers
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->canManageUsers()) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola pengguna');
        }

        return $next($request);
    }
}
