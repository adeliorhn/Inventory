<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');
        $itemId = $request->query('item_id');
        $search = $request->query('search');

        $query = StockMovement::query()->with('item')->latest('occurred_at');

        if ($type) {
            $query->where('type', $type);
        }

        if ($itemId) {
            $query->where('item_id', $itemId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('actor', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhereHas('item', function ($iq) use ($search) {
                        $iq->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        $movements = $query->paginate(12)->withQueryString();

        return view('stock-history.index', [
            'movements' => $movements,
            'items' => Item::query()->orderBy('name')->get(),
            'filters' => [
                'type' => $type,
                'item_id' => $itemId,
                'search' => $search,
            ],
            'totalMovements' => StockMovement::count(),
            'totalIn' => StockMovement::where('type', 'in')->sum('quantity'),
            'totalOut' => StockMovement::where('type', 'out')->sum('quantity'),
        ]);
    }
}
