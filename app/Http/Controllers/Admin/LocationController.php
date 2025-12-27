<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->get();
        return view('admin.locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'delivery_price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        Location::create($data);
        return back()->with('success', 'Location added successfully!');
    }

    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'delivery_price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $location->update($data);
        return back()->with('success', 'Location updated!');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Location deleted!');
    }

    // Add this inside the LocationController class
    public function toggleStatus(Location $location)
    {
        try {
            $location->update([
                'is_active' => !$location->is_active
            ]);

            return response()->json([
                'success' => true,
                'is_active' => $location->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
