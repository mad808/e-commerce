<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->image = asset('storage/' . $item->image);
                $item->formatted_date = $item->created_at->format('d.m.Y H:i');
                return $item;
            });

        return response()->json(['status' => true, 'data' => $news]);
    }
}
