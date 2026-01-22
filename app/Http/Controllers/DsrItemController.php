<?php

namespace App\Http\Controllers;

use App\Models\DsrItem;
use Illuminate\Http\Request;

class DsrItemController extends Controller
{
    /**
     * Display a listing of the DSR items.
     */
    public function index(Request $request)
    {
        $items = DsrItem::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->search($search);
            })
            ->when($request->input('dsr_type'), function ($query, $type) {
                $query->ofType($type);
            })
            ->when($request->input('category'), function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = DsrItem::distinct()->pluck('category')->filter();
        $dsrTypes = ['DSR', 'SSR', 'WRD'];

        return view('dsr-items.index', [
            'items' => $items,
            'categories' => $categories,
            'dsrTypes' => $dsrTypes,
            'filters' => $request->only(['search', 'dsr_type', 'category']),
        ]);
    }

    /**
     * Display the specified DSR item.
     */
    public function show(DsrItem $dsrItem)
    {
        return view('dsr-items.show', [
            'item' => $dsrItem,
        ]);
    }

    /**
     * Search DSR items (for API.AJAX requests).
     */
    public function search(Request $request)
    {
        $items = DsrItem::query()
            ->when($request->input('q'), function ($query, $search) {
                $query->search($search);
            })
            ->when($request->input('dsr_type'), function ($query, $type) {
                $query->ofType($type);
            })
            ->limit(50)
            ->get(['id', 'item_code', 'description', 'unit', 'rate_scheduled', 'rate_non_scheduled', 'dsr_type']);

        return response()->json($items);
    }
}
