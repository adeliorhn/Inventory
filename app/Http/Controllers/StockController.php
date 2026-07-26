<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $filter = $request->query('filter');

        $query = Item::query()->orderBy('stock');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($filter === 'low_stock') {
            $query->whereColumn('stock', '<=', 'min_stock');
        } elseif ($filter === 'safe') {
            $query->whereColumn('stock', '>', 'min_stock');
        } elseif ($filter === 'out_of_stock') {
            $query->where('stock', 0);
        }

        $items = $query->paginate(10)->withQueryString();

        return view('stocks.index', [
            'items' => $items,
            'allItems' => Item::query()->orderBy('name')->get(),
            'filters' => [
                'search' => $search,
                'filter' => $filter,
            ],
            'totalItems' => Item::count(),
            'totalStockSum' => (int) Item::sum('stock'),
            'lowStockCount' => Item::whereColumn('stock', '<=', 'min_stock')->count(),
            'outOfStockCount' => Item::where('stock', 0)->count(),
        ]);
    }
}
