<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guard = $guards[0] ?? null;

        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'editor':
                    return redirect('/editor/dashboard');
                case 'wartawan':
                    return redirect('/wartawan/dashboard');
                default:
                    return redirect('/user/dashboard');
            }
        }

        return $next($request);
    }
}