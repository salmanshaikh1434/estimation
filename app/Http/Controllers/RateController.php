<?php

namespace App\Http\Controllers;

use App\Models\DsrRate;
use App\Models\SsrRate;
use App\Models\WrdRate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * Display a listing of rates
     */
    public function index(Request $request)
    {
        $rateType = $request->input('rate_type', 'dsr');
        $search = $request->input('search');
        $category = $request->input('category');
        $perPage = $request->input('per_page', 20);

        // Get DSR rates
        $dsrQuery = DsrRate::query();
        if ($search) {
            $dsrQuery->search($search);
        }
        if ($category) {
            $dsrQuery->byCategory($category);
        }
        $dsrRates = $dsrQuery->paginate($perPage);

        // Get SSR rates
        $ssrQuery = SsrRate::query();
        if ($search) {
            $ssrQuery->search($search);
        }
        if ($category) {
            $ssrQuery->byCategory($category);
        }
        $ssrRates = $ssrQuery->paginate($perPage);

        // Get WRD rates
        $wrdQuery = WrdRate::query();
        if ($search) {
            $wrdQuery->search($search);
        }
        if ($category) {
            $wrdQuery->byCategory($category);
        }
        $wrdRates = $wrdQuery->paginate($perPage);

        return view('rates.index', [
            'dsr_rates' => [
                'data' => $dsrRates->items(),
                'total' => DsrRate::count(),
            ],
            'ssr_rates' => [
                'data' => $ssrRates->items(),
                'total' => SsrRate::count(),
            ],
            'wrd_rates' => [
                'data' => $wrdRates->items(),
                'total' => WrdRate::count(),
            ],
            'filters' => [
                'search' => $search,
                'category' => $category,
                'rate_type' => $rateType,
            ],
        ]);
    }

    /**
     * Display the specified rate.
     */
    public function show(Request $request, string $rateType, int $id)
    {
        $model = $this->getModel($rateType);
        $rate = $model::findOrFail($id);

        return view('rates.show', [
            'rate' => $rate,
            'rateType' => $rateType,
        ]);
    }

    /**
     * Search rates (for API.AJAX requests).
     */
    public function search(Request $request)
    {
        $rateType = $request->input('rate_type', 'dsr');
        $search = $request->input('q');

        $model = $this->getModel($rateType);
        
        $query = $model::query();

        if ($search) {
            $query->search($search);
        }

        $rates = $query->limit(50)
            ->get(['id', 'item_code', 'description', 'unit', 'rate_scheduled', 'rate_non_scheduled'])
            ->map(function ($rate) use ($rateType) {
                return [
                    'id' => $rate->id,
                    'item_code' => $rate->item_code,
                    'description' => $rate->description,
                    'unit' => $rate->unit,
                    'rate_scheduled' => $rate->rate_scheduled,
                    'rate_non_scheduled' => $rate->rate_non_scheduled,
                    'rate_type' => $rateType,
                ];
            });

        return response()->json($rates);
    }

    /**
     * Get the model class based on rate type.
     */
    private function getModel(string $rateType): string
    {
        return match($rateType) {
            'dsr' => DsrRate::class,
            'ssr' => SsrRate::class,
            'wrd' => WrdRate::class,
            default => DsrRate::class,
        };
    }
}
