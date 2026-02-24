<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Validation\Rule;

use App\Traits\HasMedia;

class UnitController extends Controller
{
    use HasMedia;

    public function index(Property $property = null)
    {
        // If property is provided, show units for that property
        $query = Unit::with('property');
        if ($property) {
            $query->where('property_id', $property->id);
        }
        $units = $query->latest()->paginate(10);

        return view('admin.units.index', compact('units', 'property'));
    }

    public function create()
    {
        $properties = Property::all();
        return view('admin.units.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:50',
            'floor' => 'nullable|integer',
            'size' => 'required|numeric',
            'status' => ['required', Rule::in(['rented', 'for_sale', 'for_rent', 'sold', 'archived'])],
            'price' => 'nullable|numeric',
            'photos.*' => 'image|max:2048',
        ]);

        $unit = Unit::create($validated);

        if ($request->hasFile('photos')) {
            // Need HasMedia trait in UnitController or generic. Let's assume using facade or injecting.
            // Wait, UnitController doesn't use HasMedia yet. We need to add it.
            // But let's check imports. Trait is App\Traits\HasMedia.
            foreach ($request->file('photos') as $photo) {
                // Assuming we use HasMedia trait, calling directly if imported.
                // We'll add 'use HasMedia' in the next step or manually here.
                // Since I can't edit multiple parts easily, I will just call a helper or assume trait.
                // Let's add the trait line in a separate edit or assume I can add it here?
                // I'll assume I'll add the trait in top of class separately.
                $path = $this->uploadMedia($photo, 'units');
                $unit->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.units.index')->with('success', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        $properties = Property::all();
        $tenants = \App\Models\User::where('role', 'tenant')->get();
        return view('admin.units.edit', compact('unit', 'properties', 'tenants'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'nullable|exists:users,id',
            'unit_number' => 'required|string|max:50',
            'floor' => 'nullable|integer',
            'size' => 'required|numeric',
            'status' => ['required', Rule::in(['rented', 'for_sale', 'for_rent', 'sold', 'archived'])],
            'price' => 'nullable|numeric',
            'photos.*' => 'image|max:2048',
        ]);

        if (!empty($validated['user_id'])) {
            $validated['status'] = 'rented';
        }

        $unit->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $this->uploadMedia($photo, 'units');
                $unit->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        foreach ($unit->photos as $photo) {
            $this->deleteMedia($photo->path);
            $photo->delete();
        }
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'Unit deleted successfully.');
    }

    public function destroyPhoto(\App\Models\Photo $photo)
    {
        $this->deleteMedia($photo->path);
        $photo->delete();
        return back()->with('success', 'Photo removed.');
    }
}
