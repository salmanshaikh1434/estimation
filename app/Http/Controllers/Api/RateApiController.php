<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DsrRate;
use App\Models\SsrRate;
use App\Models\WrdRate;
use App\Models\CalculationFormula;
use Illuminate\Http\Request;

class RateApiController extends Controller
{
    /**
     * Search rates by type
     */
    public function search(Request $request, string $type)
    {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 10);

        $model = match($type) {
            'dsr' => DsrRate::class,
            'ssr' => SsrRate::class,
            'wrd' => WrdRate::class,
            default => DsrRate::class,
        };

        $rates = $model::query()
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('description', 'like', "%{$query}%")
                             ->orWhere('item_code', 'like', "%{$query}%");
                });
            })
            ->limit($limit)
            ->get()
            ->map(fn ($rate) => [
                'id' => $rate->id,
                'item_code' => $rate->item_code,
                'description' => $rate->description,
                'unit' => $rate->unit,
                'rate_scheduled' => $rate->rate_scheduled,
                'rate_non_scheduled' => $rate->rate_non_scheduled,
                'category' => $rate->category,
                'sub_category' => $rate->sub_category,
            ]);

        return response()->json($rates);
    }

    /**
     * Get all calculation formulas
     */
    public function formulas(Request $request)
    {
        $formulas = CalculationFormula::active()
            ->orderBy('usage_count', 'desc')
            ->get()
            ->map(fn ($formula) => [
                'id' => $formula->id,
                'name' => $formula->name,
                'code' => $formula->code,
                'category' => $formula->category,
                'calculation_type' => $formula->calculation_type,
                'description' => $formula->description,
                'formula' => $formula->formula,
                'parameters' => $formula->parameters,
                'unit' => $formula->unit,
                'example' => $formula->example,
            ]);

        return response()->json($formulas);
    }

    /**
     * Get a specific rate by ID and type
     */
    public function getRate(string $type, int $id)
    {
        $model = match($type) {
            'dsr' => DsrRate::class,
            'ssr' => SsrRate::class,
            'wrd' => WrdRate::class,
            default => DsrRate::class,
        };

        $rate = $model::findOrFail($id);

        return response()->json([
            'id' => $rate->id,
            'item_code' => $rate->item_code,
            'description' => $rate->description,
            'unit' => $rate->unit,
            'rate_scheduled' => $rate->rate_scheduled,
            'rate_non_scheduled' => $rate->rate_non_scheduled,
            'category' => $rate->category,
            'sub_category' => $rate->sub_category,
            'remarks' => $rate->remarks,
        ]);
    }
}
