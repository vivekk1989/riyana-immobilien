<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class PublicController extends Controller
{
    public function index()
    {
        // Welcome page - maybe show latest 3 properties?
        $latestUnits = Unit::with(['photos', 'property.photos'])
            ->whereIn('status', ['for_rent', 'for_sale'])
            ->latest()
            ->take(3)
            ->get();

        return view('public.welcome', compact('latestUnits'));
    }

    public function listings(Request $request)
    {
        $query = Unit::with(['photos', 'property.photos'])->whereIn('status', ['for_rent', 'for_sale']);

        // Simple filter example
        if ($request->has('status') && in_array($request->status, ['for_rent', 'for_sale'])) {
            $query->where('status', $request->status);
        }

        $units = $query->latest()->paginate(9);

        return view('public.listings.index', compact('units'));
    }

    public function show(Unit $unit)
    {
        // Ensure unit is active
        if (!in_array($unit->status, ['for_rent', 'for_sale', 'rented', 'sold'])) {
            // Maybe allow showing rented/sold for history, but definitely not archived if hidden
            // For now, let's show everything except archived
        }

        $unit->load(['photos', 'property.photos']);
        return view('public.listings.show', compact('unit'));
    }
}
