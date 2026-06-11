<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    // 📊 1. ஃப்ளட்டர் டேஷ்போர்டுக்கு தேவையான அட்வான்ஸ்டு KPI விபரங்கள்
    public function getDashboardMetrics()
    {
        $totalAssets = Asset::sum('quantity');
        $totalValue = Asset::selectRaw('SUM(value * quantity) as total')->value('total') ?? 0;
        $inRepair = Asset::where('status', 'In Repair')->sum('quantity');
        $damaged = Asset::where('status', 'Damaged')->sum('quantity');

        return response()->json([
            'total_assets' => (int)$totalAssets,
            'total_value' => (float)$totalValue,
            'in_repair' => (int)$inRepair,
            'damaged' => (int)$damaged,
        ], 200);
    }

    // 📄 2. அனைத்து அசெட்களையும் எடுத்தல்
    public function index() 
    {
        return response()->json(Asset::orderBy('created_at', 'desc')->get(), 200);
    }

    // 💾 3. புதிய அசெட் உருவாக்குதல் (With Quantity, Status & Image)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_name'    => 'required|string|max:255',
            'category'      => 'required|string|max:255',
            'serial_number' => 'required|string|unique:assets,serial_number',
            'purchase_date' => 'required|date',
            'value'         => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1',
            'status'        => 'required|string|in:Active,In Repair,Damaged,Retired',
            'image'         => 'nullable|string' // ஃப்ளட்டரில் இருந்து Base64 அல்லது URL ஆக வாங்க ஏதுவாக string ஆக மாற்றப்பட்டுள்ளது
        ]);

        $asset = Asset::create($validated);
        return response()->json(['message' => 'Asset Created Successfully', 'data' => $asset], 201);
    }

    // 🔍 4. தனிப்பட்ட ஒரு அசெட் விபரம் பார்கக்
    public function show($id)
    {
        $asset = Asset::findOrFail($id);
        return response()->json($asset, 200);
    }

    // 🔄 5. அசெட் விபரங்களை முழுமையாக அப்டேட் செய்தல்
    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            'asset_name'    => 'required|string|max:255',
            'category'      => 'required|string|max:255',
            'serial_number' => 'required|string|unique:assets,serial_number,' . $id,
            'purchase_date' => 'required|date',
            'value'         => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1',
            'status'        => 'required|string|in:Active,In Repair,Damaged,Retired',
            'image'         => 'nullable|string'
        ]);

        $asset->update($validated);
        return response()->json(['message' => 'Asset Updated Successfully', 'data' => $asset], 200);
    }

    // ❌ 6. அசெட்டை நீக்குதல்
    public function destroy($id) 
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        return response()->json(['message' => 'Asset Deleted Successfully'], 200);
    }
}