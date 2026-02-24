<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Unit;
use App\Models\UtilityCategory;
use App\Models\UnitUtilityConfig;
use App\Models\UtilityEntry;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UnitUtilityConfigController extends Controller
{
    public function index(Request $request, Unit $unit)
    {
        $year = $request->input('year', now()->year);

        // Ensure period entry exists or create it (default OPEN)
        $period = \App\Models\NebenkostenPeriod::firstOrCreate(
            ['unit_id' => $unit->id, 'year' => $year],
            ['status' => 'OPEN']
        );

        $unit->load([
            'utilityConfigs' => function ($query) use ($year) {
                $query->where('year', $year);
            },
            'utilityConfigs.category',
            'property'
        ]);

        $availableCategories = UtilityCategory::whereDoesntHave('unitConfigs', function ($query) use ($unit, $year) {
            $query->where('unit_id', $unit->id)->where('year', $year);
        })->get();

        return view('admin.units.utilities.index', compact('unit', 'availableCategories', 'year', 'period'));
    }

    public function store(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'utility_category_id' => 'required|exists:utility_categories,id',
            'price_per_unit' => 'nullable|numeric|min:0',
            'calculation_method' => 'nullable|string|max:255',
            'year' => 'required|integer|min:2000|max:2099',
        ]);

        $unit->utilityConfigs()->create($validated);

        return redirect()->route('admin.units.utilities.index', ['unit' => $unit, 'year' => $validated['year']])
            ->with('success', 'Utility assigned successfully.');
    }

    public function destroy(Unit $unit, UnitUtilityConfig $config)
    {
        if ($config->unit_id !== $unit->id) {
            abort(403);
        }

        $config->delete();

        return redirect()->route('admin.units.utilities.index', $unit)->with('success', 'Utility assignment removed.');
    }

    public function edit(Unit $unit, UnitUtilityConfig $config)
    {
        if ($config->unit_id !== $unit->id) {
            abort(403);
        }
        return view('admin.units.utilities.edit', compact('unit', 'config'));
    }

    public function update(Request $request, Unit $unit, UnitUtilityConfig $config)
    {
        if ($config->unit_id !== $unit->id) {
            abort(403);
        }

        $validated = $request->validate([
            'price_per_unit' => 'nullable|numeric|min:0',
            'calculation_method' => 'nullable|string|max:255',
        ]);

        $config->update($validated);

        return redirect()->route('admin.units.utilities.index', $unit)->with('success', 'Utility configuration updated.');
    }

    public function showEntries(Unit $unit, UnitUtilityConfig $config)
    {
        if ($config->unit_id !== $unit->id) {
            abort(403);
        }

        $entries = $config->entries()->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.units.utilities.entries', compact('unit', 'config', 'entries'));
    }

    public function createEntry(Unit $unit, UnitUtilityConfig $config)
    {
        if ($config->unit_id !== $unit->id) {
            abort(403);
        }
        return view('admin.units.utilities.create-entry', compact('unit', 'config'));
    }

    public function storeEntry(Request $request, Unit $unit, UnitUtilityConfig $config)
    {
        if ($config->unit_id !== $unit->id) {
            abort(403);
        }

        $year = $config->year;
        $period = \App\Models\NebenkostenPeriod::where('unit_id', $unit->id)->where('year', $year)->first();

        if ($period && in_array($period->status, ['LOCKED', 'PUBLISHED'])) {
            return back()->with('error', 'Cannot add entries to a finalized period.');
        }

        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'value' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        // Chronological Validation: Block if a LATER reading exists for this unit & category
        $laterEntryExists = UtilityEntry::where('unit_id', $unit->id)
            ->where('utility_category_id', $config->utility_category_id)
            ->where('date', '>', $validated['date'])
            ->exists();

        if ($laterEntryExists) {
            return back()->withErrors(['date' => 'A meter reading already exists for a later date. Please maintain chronological order.']);
        }

        // Auto-calculate cost logic
        if (!isset($validated['cost']) && $config->price_per_unit) {
            if ($config->category->input_type === 'meter_reading') {
                $lastEntry = UtilityEntry::where('unit_id', $config->unit_id)
                    ->where('utility_category_id', $config->utility_category_id)
                    ->where('date', '<=', $validated['date'])
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastEntry) {
                    $diff = $validated['value'] - $lastEntry->value;
                    if ($diff > 0) {
                        $validated['cost'] = $diff * $config->price_per_unit;
                    }
                }
            } else {
                $validated['cost'] = $validated['value'] * $config->price_per_unit;
            }
        } elseif (!isset($validated['cost']) && !$config->price_per_unit) {
            if ($config->category->input_type !== 'meter_reading') {
                $validated['cost'] = $validated['value'];
            }
        }

        $entryData = [
            'unit_id' => $config->unit_id,
            'utility_category_id' => $config->utility_category_id,
            'date' => $validated['date'],
            'value' => $validated['value'],
            'cost' => $validated['cost'] ?? null,
        ];

        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('utility-proofs', 'public');
            $entryData['proof_image_path'] = $path;
        }

        UtilityEntry::create($entryData);

        return redirect()->route('admin.units.utilities.entries', ['unit' => $unit, 'config' => $config])
            ->with('success', 'Entry created successfully.');
    }

    public function editEntry(Unit $unit, UnitUtilityConfig $config, UtilityEntry $entry)
    {
        if ($config->unit_id !== $unit->id || $entry->unit_id !== $unit->id || $entry->utility_category_id !== $config->utility_category_id) {
            abort(403);
        }

        return view('admin.units.utilities.edit-entry', compact('unit', 'config', 'entry'));
    }

    public function updateEntry(Request $request, Unit $unit, UnitUtilityConfig $config, UtilityEntry $entry)
    {
        if ($config->unit_id !== $unit->id || $entry->unit_id !== $unit->id || $entry->utility_category_id !== $config->utility_category_id) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'value' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0', // Admin can manually override cost
            'proof_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('proof_image')) {
            if ($entry->proof_image_path) {
                Storage::disk('public')->delete($entry->proof_image_path);
            }
            $validated['proof_image_path'] = $request->file('proof_image')->store('utility-proofs', 'public');
        }

        // If cost is not provided, maybe recalculate?
        // For now, let's assume if Admin edits, they act with authority.
        // If they want to recalculate, logic serves them to input it or we keep existing logic.
        // Let's implement basic recalculation if ONLY value changes and cost is null/empty?
        // Or simpler: Just update what is passed.
        // Let's default cost to old cost if not present, or null.
        // Actually the form should probably pre-fill existing cost.

        $entry->update($validated);

        return redirect()->route('admin.units.utilities.entries', ['unit' => $unit, 'config' => $config])
            ->with('success', 'Entry updated successfully.');
    }

    public function destroyEntry(Unit $unit, UnitUtilityConfig $config, UtilityEntry $entry)
    {
        if ($config->unit_id !== $unit->id || $entry->unit_id !== $unit->id || $entry->utility_category_id !== $config->utility_category_id) {
            abort(403);
        }

        if ($entry->proof_image_path) {
            Storage::disk('public')->delete($entry->proof_image_path);
        }

        $entry->delete();

        return redirect()->route('admin.units.utilities.entries', ['unit' => $unit, 'config' => $config])
            ->with('success', 'Entry deleted.');
    }
}
