<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categoriesData = Item::query()
            ->selectRaw('category, COUNT(*) as total_items, SUM(stock) as total_stock, SUM(price * stock) as total_value')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $uncategorizedCount = Item::whereNull('category')->orWhere('category', '')->count();

        return view('categories.index', [
            'categories' => $categoriesData,
            'uncategorizedCount' => $uncategorizedCount,
            'totalCategoriesCount' => $categoriesData->count(),
            'totalItemsCount' => Item::count(),
        ]);
    }
}
