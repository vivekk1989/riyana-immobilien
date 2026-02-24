<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UnitUtilityConfig;
use App\Models\UtilityEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UtilityEntryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $allUnits = $user->units;
        $defaultYear = now()->year;
        $year = $request->input('year', $defaultYear);

        if ($allUnits->isEmpty()) {
            return view('tenant.utilities.no-unit');
        }

        // Determine current unit
        $unitId = $request->query('unit_id');
        if ($unitId) {
            $unit = $allUnits->firstWhere('id', $unitId);
            if (!$unit) {
                // Requested unit not owned by user, fall back to first
                $unit = $allUnits->first();
            }
        } else {
            $unit = $allUnits->first();
        }

        // Get Period for status
        $period = \App\Models\NebenkostenPeriod::where('unit_id', $unit->id)->where('year', $year)->first();

        // Load configs specific for that year (or fallback to empty if not set)
        // Actually, configs are versioned by year.
        $unit->load([
            'utilityConfigs' => function ($query) use ($year) {
                $query->where('year', $year);
            },
            'utilityConfigs.category'
        ]);

        $entries = UtilityEntry::where('unit_id', $unit->id)
            ->whereYear('date', $year)
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('tenant.utilities.index', compact('unit', 'allUnits', 'entries', 'year', 'period'));
    }

    public function create(UnitUtilityConfig $config)
    {
        // Check ownership
        if ($config->unit->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tenant.utilities.create', compact('config'));
    }

    public function store(Request $request, UnitUtilityConfig $config)
    {
        if ($config->unit->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if period is locked
        $year = $config->year;
        $period = \App\Models\NebenkostenPeriod::where('unit_id', $config->unit_id)->where('year', $year)->first();
        if ($period && in_array($period->status, ['LOCKED', 'PUBLISHED'])) {
            return back()->with('error', 'This period is finalized. No new entries can be added.');
        }

        if ($config->category->input_type !== 'meter_reading') {
            abort(403, 'Fixed cost entries are managed by the administrator.');
        }

        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'value' => 'required|numeric|min:0',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        // Chronological Validation
        $laterEntryExists = UtilityEntry::where('unit_id', $config->unit_id)
            ->where('utility_category_id', $config->utility_category_id)
            ->where('date', '>', $validated['date'])
            ->exists();

        if ($laterEntryExists) {
            return back()->withErrors(['date' => 'A meter reading already exists for a later date. Please maintain chronological order.']);
        }

        $cost = null;
        if ($config->category->input_type === 'meter_reading') {
            // Calculate cost based on diff
            $lastEntry = UtilityEntry::where('unit_id', $config->unit_id)
                ->where('utility_category_id', $config->utility_category_id)
                ->where('date', '<=', $validated['date'])
                ->latest('date')
                ->latest('id')
                ->first();

            if ($lastEntry) {
                $diff = $validated['value'] - $lastEntry->value;
                if ($diff > 0 && $config->price_per_unit) {
                    $cost = $diff * $config->price_per_unit;
                }
            }
        } elseif ($config->category->input_type === 'fixed_cost') {
            // If price_per_unit is set, use it as valid cost, or use value if it represents cost
            // Plan said: "Fixed Cost: simple entry". Usually means value is the cost or 1 * price.
            // Let's assume for fixed cost input, the 'value' entered is just 1 (count) or the cost itself?
            // Review plan: "FixedCost: Direct cost entry or 1 * price."
            // Let's assume value entered IS the cost if price_per_unit is null, else value * price.
            if ($config->price_per_unit) {
                $cost = $validated['value'] * $config->price_per_unit;
            } else {
                $cost = $validated['value']; // User enters cost directly
            }
        }

        $entryData = [
            'unit_id' => $config->unit_id,
            'utility_category_id' => $config->utility_category_id,
            'date' => $validated['date'],
            'value' => $validated['value'],
            'cost' => $cost,
        ];

        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('utility-proofs', 'public');
            $entryData['proof_image_path'] = $path;
        }

        UtilityEntry::create($entryData);

        return redirect()->route('tenant.utilities.index', ['unit_id' => $config->unit_id])->with('success', 'Entry recorded successfully.');
    }
}
