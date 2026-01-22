<?php

namespace App\Http\Controllers;

use App\Models\EstimationItem;
use App\Models\EstimationMeasurement;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Get measurements for an estimation item.
     */
    public function index(EstimationItem $item)
    {
        $measurements = $item->measurements;
        
        return response()->json($measurements);
    }

    /**
     * Store measurements for an estimation item.
     */
    public function store(Request $request, EstimationItem $item)
    {
        $validated = $request->validate([
            'measurements' => 'required|array',
            'measurements.*.length' => 'nullable|numeric|min:0',
            'measurements.*.breadth' => 'nullable|numeric|min:0',
            'measurements.*.height' => 'nullable|numeric|min:0',
            'measurements.*.number' => 'required|integer|min:1',
            'measurements.*.remarks' => 'nullable|string|max:500',
        ]);

        // Delete existing measurements
        $item->measurements()->delete();

        // Create new measurements
        foreach ($validated['measurements'] as $index => $data) {
            $item->measurements()->create([
                'row_number' => $index + 1,
                'length' => $data['length'] ?? null,
                'breadth' => $data['breadth'] ?? null,
                'height' => $data['height'] ?? null,
                'number' => $data['number'],
                'remarks' => $data['remarks'] ?? null,
                'sort_order' => $index,
            ]);
        }

        // Recalculate item amount based on new measurements
        $item->calculateAmount();

        return back()->with('success', 'Measurements saved successfully.');
    }

    /**
     * Delete all measurements for an item.
     */
    public function destroy(EstimationItem $item)
    {
        $item->measurements()->delete();
        
        // Recalculate with original quantity
        $item->calculateAmount();

        return back()->with('success', 'Measurements deleted successfully.');
    }
}
