<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UtilityCategory;
use Illuminate\Validation\Rule;

class UtilityCategoryController extends Controller
{
    public function index()
    {
        $categories = UtilityCategory::all();
        return view('admin.utilities.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.utilities.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:utility_categories',
            'input_type' => ['required', Rule::in(['meter_reading', 'fixed_cost'])],
        ]);

        UtilityCategory::create($validated);

        return redirect()->route('admin.utilities.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(UtilityCategory $category)
    {
        return view('admin.utilities.categories.edit', compact('category'));
    }

    public function update(Request $request, UtilityCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('utility_categories')->ignore($category->id)],
            'input_type' => ['required', Rule::in(['meter_reading', 'fixed_cost'])],
        ]);

        $category->update($validated);

        return redirect()->route('admin.utilities.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(UtilityCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.utilities.categories.index')->with('success', 'Category deleted successfully.');
    }
}
