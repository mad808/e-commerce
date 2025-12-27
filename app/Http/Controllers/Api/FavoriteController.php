<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()
            ->get()
            ->map(function($p) {
                $p->image = asset('storage/' . $p->image);
                return $p;
            });

        return response()->json(['status' => true, 'data' => $favorites]);
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $user = $request->user();
        $id = $request->product_id;

        if ($user->favorites()->where('product_id', $id)->exists()) {
            $user->favorites()->detach($id);
            $status = 'removed';
        } else {
            $user->favorites()->attach($id);
            $status = 'added';
        }

        return response()->json(['status' => true, 'message' => $status]);
    }
}