<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'editor':
                return redirect()->route('editor.dashboard');
            case 'wartawan':
                return redirect()->route('wartawan.dashboard');
            case 'user':
                return redirect()->route('user.dashboard');
            default:
                abort(403, 'Akses tidak diizinkan.');
        }
    }
}

