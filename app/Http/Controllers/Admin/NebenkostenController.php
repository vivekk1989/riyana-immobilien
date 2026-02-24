<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NebenkostenPeriod;
use App\Models\Unit;
use App\Models\UtilityEntry;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class NebenkostenController extends Controller
{
    public function finalizeYear(Unit $unit, $year)
    {
        $period = NebenkostenPeriod::firstOrCreate(
            ['unit_id' => $unit->id, 'year' => $year]
        );

        // Fetch Data for PDF
        $configs = $unit->utilityConfigs()->where('year', $year)->with('category')->get();

        $entries = UtilityEntry::where('unit_id', $unit->id)
            ->whereYear('date', $year)
            ->with('category')
            ->orderBy('date')
            ->get()
            ->groupBy('utility_category_id');

        $totalCost = 0;
        $breakdown = [];

        foreach ($configs as $config) {
            $catId = $config->utility_category_id;
            $catEntries = $entries[$catId] ?? collect();

            $catTotal = $catEntries->sum('cost');
            $totalCost += $catTotal;

            $breakdown[] = [
                'category' => $config->category->name,
                'total' => $catTotal,
                'entries' => $catEntries
            ];
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdfs.nebenkosten_statement', compact('unit', 'year', 'breakdown', 'totalCost'));
        $filename = 'Nebenkosten_' . $unit->id . '_' . $year . '.pdf';
        $path = 'nebenkosten/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        $period->update([
            'status' => 'LOCKED',
            'pdf_path' => $path
        ]);

        return back()->with('success', 'Year finalized and PDF generated.');
    }

    public function publishYear(Unit $unit, $year)
    {
        $period = NebenkostenPeriod::where('unit_id', $unit->id)->where('year', $year)->firstOrFail();

        if (!in_array($period->status, ['LOCKED', 'PUBLISHED'])) {
            return back()->with('error', 'Period must be FINALIZED before publishing.');
        }

        $period->update(['status' => 'PUBLISHED']);

        // Send Email to Tenant if exists
        $tenant = $unit->tenant;
        if ($tenant) {
            Mail::to($tenant)->send(new \App\Mail\NebenkostenPublished($period));
        }

        return back()->with('success', 'Statement published and emailed to tenant.');
    }

    public function unlockYear(Unit $unit, $year)
    {
        $period = NebenkostenPeriod::where('unit_id', $unit->id)->where('year', $year)->firstOrFail();

        if ($period->status === 'OPEN') {
            return back();
        }

        // Delete PDF
        if ($period->pdf_path) {
            Storage::disk('public')->delete($period->pdf_path);
        }

        $period->update([
            'status' => 'OPEN',
            'pdf_path' => null
        ]);

        return back()->with('success', 'Year unlocked. Editing re-enabled.');
    }
}
