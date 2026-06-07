<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Item;
use App\Models\Message;
use App\Models\StockMovement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $items = Item::query()
            ->withCount('movements')
            ->orderBy('name')
            ->paginate(8);

        return view('inventory.dashboard', [
            'items' => $items,
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
            'summary' => [
                'items' => Item::query()->count(),
                'lowStock' => Item::query()->whereColumn('stock', '<=', 'min_stock')->count(),
                'todayMovements' => StockMovement::query()->whereDate('occurred_at', today())->count(),
                'openMessages' => Message::query()->where('status', 'open')->count(),
            ],
        ]);
    }
}
