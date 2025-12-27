<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Display a listing of news.
     */
    public function index()
    {
        $news = News::latest()->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating new news.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'body' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5048',
            'video' => 'nullable|mimes:mp4,mov,ogg|max:500480',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'title' => $validated['title'],
                'summary' => $validated['summary'],
                'body' => $validated['body'],
                'is_active' => $request->has('is_active'),
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('news', 'public');
            }

            if ($request->hasFile('video')) {
                $data['video_url'] = $request->file('video')->store('news/videos', 'public');
            }

            News::create($data);

            DB::commit();

            return redirect()
                ->route('admin.news.index');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Habar döredilmedi. Gaýtadan synanyşyň.');
        }
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:50048',
            'video' => 'nullable|mimes:mp4,mov,ogg|max:50480',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'title' => $validated['title'],
                'summary' => $validated['summary'],
                'body' => $validated['body'],
                'is_active' => $request->has('is_active'),
            ];

            if ($request->hasFile('image')) {
                if ($news->image && Storage::disk('public')->exists($news->image)) {
                    Storage::disk('public')->delete($news->image);
                }

                $data['image'] = $request->file('image')->store('news', 'public');
            }

            if ($request->hasFile('video')) {
                if ($news->video_url && Storage::disk('public')->exists($news->video_url)) {
                    Storage::disk('public')->delete($news->video_url);
                }
                $data['video_url'] = $request->file('video')->store('news/videos', 'public');
            }

            $news->update($data);

            DB::commit();

            return redirect()
                ->route('admin.news.index')
                ->with('success', 'Habar üstünlikli täzelendi!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Habar täzelenmedi. Gaýtadan synanyşyň.');
        }
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(News $news)
    {
        try {
            DB::beginTransaction();

            // Delete image
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            $news->delete();

            DB::commit();

            return back()->with('success', 'Habar üstünlikli pozuldy!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Habar pozulmady. Gaýtadan synanyşyň.');
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(News $news)
    {
        try {
            $news->update(['is_active' => !$news->is_active]);

            return back()->with('success', 'Status üstünlikli üýtgedildi!');
        } catch (\Exception $e) {
            return back()->with('error', 'Status üýtgedilmedi.');
        }
    }
}
