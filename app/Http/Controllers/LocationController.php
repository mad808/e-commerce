<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocationController extends Controller
{
    public function setLocation(Request $request)
    {
        $request->validate(['location_id' => 'required|exists:locations,id']);

        // 1. Save to Session (for guests)
        Session::put('location_id', $request->location_id);

        // Get location details to store name in session too for easy access
        $location = Location::find($request->location_id);
        Session::put('location_name', $location->name);
        Session::put('delivery_price', $location->delivery_price);

        // 2. Save to User (if logged in)
        if (Auth::check()) {
            $user = Auth::user();
            $user->location_id = $request->location_id;
            $user->save();
        }

        return response()->json(['status' => true, 'message' => 'Location updated']);
    }
}
