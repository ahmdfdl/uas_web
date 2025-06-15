<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class DashboardController extends Controller
{
    public function admin() {
        return view('dashboard.admin');
    }

    public function editor() {
        $pendingNews = News::where('status', 'pending')
            ->with(['author', 'category'])
            ->latest()
            ->get();

        return view('dashboard.editor', compact('pendingNews'));
    }

    public function wartawan() {
        return view('dashboard.wartawan');
    }

    public function user() {
        return view('dashboard.user');
    }
}
