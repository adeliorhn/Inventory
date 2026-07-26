<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Item;
use App\Models\Message;
use App\Models\StockMovement;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $items = Item::query()
            ->withCount('movements')
            ->orderByDesc('created_at')
            ->paginate(8);

        $lowStockItems = Item::query()
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->take(6)
            ->get();

        // 7-day stock movement chart data
        $chartDays = [];
        $stockInTrends = [];
        $stockOutTrends = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $chartDays[] = $date->translatedFormat('d M');

            $stockInTrends[] = StockMovement::whereDate('occurred_at', $dateKey)
                ->where('type', 'in')
                ->sum('quantity');

            $stockOutTrends[] = StockMovement::whereDate('occurred_at', $dateKey)
                ->where('type', 'out')
                ->sum('quantity');
        }

        $categories = Item::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('inventory.dashboard', [
            'items' => $items,
            'lowStockItems' => $lowStockItems,
            'allItems' => Item::query()->orderBy('name')->get(),
            'recentMovements' => StockMovement::query()
                ->with('item')
                ->latest('occurred_at')
                ->take(8)
                ->get(),
            'alerts' => Alert::query()
                ->with('item')
                ->whereIn('status', ['open', 'read'])
                ->latest()
                ->take(6)
                ->get(),
            'messages' => Message::query()
                ->latest()
                ->take(6)
                ->get(),
            'categories' => $categories,
            'chartDays' => $chartDays,
            'stockInTrends' => $stockInTrends,
            'stockOutTrends' => $stockOutTrends,
            'summary' => [
                'items' => Item::query()->count(),
                'totalStock' => (int) Item::query()->sum('stock'),
                'lowStock' => Item::query()->whereColumn('stock', '<=', 'min_stock')->count(),
                'todayMovements' => StockMovement::query()->whereDate('occurred_at', Carbon::today())->count(),
                'openMessages' => Message::query()->where('status', 'open')->count(),
            ],
        ]);
    }
}
