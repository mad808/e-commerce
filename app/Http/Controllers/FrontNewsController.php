<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontNewsController extends Controller
{
    /**
     * Display a listing of active news.
     */
    public function index(): View
    {
        $news = News::active()
            ->latest()
            ->paginate(9);

        return view('client.news.index', compact('news'));
    }

    /**
     * Display the specified news article.
     */
    public function show(News $news): View
    {
        // Check if news is active
        if (!$news->is_active) {
            abort(404);
        }

        // --- VIEW INCREMENT LOGIC ---
        $sessionKey = 'viewed_news_' . $news->id;
        if (!session()->has($sessionKey)) {
            $news->increment('views');
            session()->put($sessionKey, true);
        }
        // ---------------------------

        // Get recent news excluding current
        $recentNews = News::active()
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(3)
            ->get();

        return view('client.news.show', compact('news', 'recentNews'));
    }
}
