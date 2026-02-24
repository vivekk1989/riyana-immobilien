<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Property;
use App\Traits\HasMedia;

class PropertyController extends Controller
{
    use HasMedia;

    public function index()
    {
        $properties = Property::latest()->paginate(10);
        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('admin.properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'photos.*' => 'image|max:2048',
        ]);

        $property = Property::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $this->uploadMedia($photo, 'properties');
                $property->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property created successfully.');
    }

    public function edit(Property $property)
    {
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'photos.*' => 'image|max:2048',
        ]);

        $property->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $this->uploadMedia($photo, 'properties');
                $property->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        foreach ($property->photos as $photo) {
            $this->deleteMedia($photo->path);
            $photo->delete();
        }
        $property->delete();

        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
    }

    public function destroyPhoto(\App\Models\Photo $photo)
    {
        $this->deleteMedia($photo->path);
        $photo->delete();
        return back()->with('success', 'Photo removed.');
    }
}
