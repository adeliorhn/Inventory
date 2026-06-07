<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockMovement;
use App\Services\InventoryAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function store(Request $request, InventoryAlertService $alerts): RedirectResponse
    {
        $data = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'type' => ['required', Rule::in(['in', 'out', 'adjustment'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'actor' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
            'occurred_at' => ['nullable', 'date'],
        ]);

        $item = null;

        DB::transaction(function () use ($data, &$item): void {
            $item = Item::query()->whereKey($data['item_id'])->lockForUpdate()->firstOrFail();
            $stockBefore = $item->stock;

            $stockAfter = match ($data['type']) {
                'in' => $stockBefore + $data['quantity'],
                'out' => $stockBefore - $data['quantity'],
                'adjustment' => $data['quantity'],
            };

            if ($stockAfter < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok keluar melebihi stok tersedia.',
                ]);
            }

            StockMovement::create([
                'item_id' => $item->id,
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'actor' => $data['actor'] ?? null,
                'note' => $data['note'] ?? null,
                'occurred_at' => $data['occurred_at'] ?? now(),
            ]);

            $item->update(['stock' => $stockAfter]);
        });

        if ($item !== null) {
            $alerts->syncLowStockAlert($item->refresh());
        }

        return redirect()
            ->route('dashboard')
            ->with('status', 'Mutasi stok berhasil dicatat.');
    }
}
