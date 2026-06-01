<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index() {
    return response()->json(Asset::all(), 200);
}

   public function store(Request $request)
{
    $validated = $request->validate([
        'asset_name' => 'required|string',
        'category' => 'required|string',
        'serial_number' => 'required|unique:assets',
        'purchase_date' => 'required|date',
        'value' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('assets', 'public');
        $validated['image'] = $path;
    }

    $asset = Asset::create($validated);
    return response()->json($asset);
}

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        return response()->json($asset, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'asset_name' => 'required',
            'value' => 'required|numeric'
        ]);

        $asset->update($request->all());
        return response()->json($asset);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
    $asset = Asset::findOrFail($id);
    $asset->delete();
    return response()->json(['message' => 'Asset Deleted'], 200);
    }
}
