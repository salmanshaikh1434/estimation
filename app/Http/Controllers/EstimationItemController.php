<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Services\CalculationService;
use Illuminate\Http\Request;

class EstimationItemController extends Controller
{
    protected CalculationService $calculationService;

    public function __construct(CalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Store a newly created estimation item.
     */
    public function store(Request $request, Estimation $estimation)
    {
        $validated = $request->validate([
            'rate_id' => 'required|integer',
            'rate_type' => 'required|in:dsr,ssr,wrd',
            'calculation_formula_id' => 'nullable|exists:calculation_formulas,id',
            'calculation_params' => 'nullable|array',
            'quantity' => 'required|numeric|min:0.001',
            'rate' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $validated['estimation_id'] = $estimation->id;
        
        // Set sort order
        $maxSortOrder = $estimation->items()->max('sort_order') ?? 0;
        $validated['sort_order'] = $maxSortOrder + 1;

        // Calculate quantity if formula is provided
        if ($validated['calculation_formula_id'] && $validated['calculation_params']) {
            $formula = \App\Models\CalculationFormula::find($validated['calculation_formula_id']);
            if ($formula) {
                $params = $validated['calculation_params'];
                $params['rate'] = $validated['rate'];
                
                $calculatedQty = $this->calculationService->calculate(
                    $formula->calculation_type,
                    $params,
                    $formula->formula
                );
                
                $validated['calculated_quantity'] = $calculatedQty / $validated['rate'];
                $validated['quantity'] = $validated['calculated_quantity'];
                $validated['amount'] = $calculatedQty;
                
                // Increment formula usage
                $formula->incrementUsage();
            }
        }

        $item = EstimationItem::create($validated);

        // Recalculate estimation totals
        $estimation->calculateTotals();

        return back()->with('success', 'Item added successfully.');
    }

    /**
     * Update the specified estimation item.
     */
    public function update(Request $request, EstimationItem $item)
    {
        $validated = $request->validate([
            'calculation_params' => 'nullable|array',
            'quantity' => 'required|numeric|min:0.001',
            'rate' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        // Recalculate if params changed
        if (isset($validated['calculation_params']) && $item->calculation_formula_id) {
            $formula = $item->calculationFormula;
            if ($formula) {
                $params = $validated['calculation_params'];
                $params['rate'] = $validated['rate'];
                
                $calculatedQty = $this->calculationService->calculate(
                    $formula->calculation_type,
                    $params,
                    $formula->formula
                );
                
                $validated['calculated_quantity'] = $calculatedQty / $validated['rate'];
                $validated['quantity'] = $validated['calculated_quantity'];
            }
        }

        $validated['amount'] = $validated['quantity'] * $validated['rate'];

        $item->update($validated);

        // Recalculate estimation totals
        $item->estimation->calculateTotals();

        return back()->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified estimation item.
     */
    public function destroy(EstimationItem $item)
    {
        $estimation = $item->estimation;
        $item->delete();

        // Recalculate estimation totals
        $estimation->calculateTotals();

        return back()->with('success', 'Item deleted successfully.');
    }

    /**
     * Reorder estimation items.
     */
    public function reorder(Request $request, Estimation $estimation)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:estimation_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $itemData) {
            EstimationItem::where('id', $itemData['id'])
                ->update(['sort_order' => $itemData['sort_order']]);
        }

        return back()->with('success', 'Items reordered successfully.');
    }
}
