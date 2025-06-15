<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanApproveNews
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->canApproveNews()) {
            abort(403, 'Anda tidak memiliki izin untuk menyetujui berita');
        }

        return $next($request);
    }
}
