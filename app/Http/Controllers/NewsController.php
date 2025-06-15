<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role === 'wartawan') {
            $news = News::with(['author', 'category'])
                        ->where('author_id', Auth::id())
                        ->orderByDesc('created_at')
                        ->get();
        } else {
            $news = News::with(['author', 'category'])
                        ->orderByDesc('created_at')
                        ->get();
        }

        return view('news.index', compact('news'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $news = new News();
        $news->title = $request->title;
        $news->slug = Str::slug($request->title);
        $news->content = $request->content;
        $news->excerpt = $request->excerpt;
        $news->status = Auth::user()->role === 'admin' ? 'published' : 'draft';
        $news->author_id = Auth::id();
        $news->category_id = $request->category_id;

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = time() . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->storeAs('public/news', $filename);
            $news->thumbnail = $filename;
        }

        $news->save();

        return redirect()->route('news.index')->with('success', 'Berita berhasil disimpan!');
    }

    public function edit(News $news)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $news->author_id) {
            abort(403);
        }

        $categories = Category::all();
        return view('news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $news->author_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $news->title = $request->title;
        $news->slug = Str::slug($request->title);
        $news->content = $request->content;
        $news->excerpt = $request->excerpt;
        $news->category_id = $request->category_id;

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = time() . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->storeAs('public/news', $filename);
            $news->thumbnail = $filename;
        }

        $news->save();

        return redirect()->route('news.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy(News $news)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $news->author_id) {
            abort(403);
        }

        $news->delete();
        return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus');
    }

    /**
     * Wartawan mengajukan berita ke editor.
     */
    public function submit(int $id): RedirectResponse
    {
        $news = News::findOrFail($id);

        if (Auth::user()->role !== 'wartawan' || Auth::id() !== $news->author_id || $news->status !== 'draft') {
            abort(403);
        }

        $news->status = 'pending';
        $news->save();

        return redirect()->back()->with('success', 'Berita berhasil diajukan ke editor.');
    }

    /**
     * Admin atau editor menyetujui berita.
     */
    public function approve(int $id): RedirectResponse
    {
        $news = News::findOrFail($id);

        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403);
        }

        $news->status = 'published';
        $news->published_at = now();
        $news->save();

        return redirect()->back()->with('success', 'Berita berhasil di-approve dan dipublikasikan.');
    }
}
