<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');

        $query = Item::query()->withCount('movements');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        } elseif ($status === 'low_stock') {
            $query->whereColumn('stock', '<=', 'min_stock');
        }

        $items = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        $categories = Item::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('products.index', [
            'items' => $items,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $category,
                'status' => $status,
            ],
            'totalCount' => Item::count(),
            'activeCount' => Item::where('is_active', true)->count(),
            'lowStockCount' => Item::whereColumn('stock', '<=', 'min_stock')->count(),
        ]);
    }
}
