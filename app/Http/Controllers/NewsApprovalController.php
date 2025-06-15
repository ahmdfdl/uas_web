<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:approve-news');
    }

    public function index()
    {
        $pendingNews = News::where('status', 'pending')
            ->with(['author', 'category'])
            ->latest()
            ->get();

        return view('dashboard.editor', compact('pendingNews'));
    }

    public function approve(News $news)
    {
        $news->status = 'published';
        $news->save();

        NewsApproval::create([
            'news_id'   => $news->id,
            'editor_id' => Auth::id(),
            'action'    => 'approved',
            'notes'     => 'OK',
            'action_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Berhasil');
    }

    public function reject(News $news, Request $request)
    {
        $notes = $request->input('notes', 'Berita ditolak');

        $news->status = 'rejected';
        $news->save();

        NewsApproval::create([
            'news_id'   => $news->id,
            'editor_id' => Auth::id(),
            'action'    => 'rejected',
            'notes'     => $notes,
            'action_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Berhasil');
    }
}
