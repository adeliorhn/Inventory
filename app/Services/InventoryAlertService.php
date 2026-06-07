<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Item;

class InventoryAlertService
{
    public function syncLowStockAlert(Item $item): void
    {
        $activeAlert = Alert::query()
            ->where('item_id', $item->id)
            ->where('type', 'low_stock')
            ->whereIn('status', ['open', 'read'])
            ->latest()
            ->first();

        if ($item->is_low_stock && $activeAlert === null) {
            Alert::create([
                'item_id' => $item->id,
                'type' => 'low_stock',
                'severity' => $item->stock === 0 ? 'critical' : 'warning',
                'title' => 'Stok rendah',
                'body' => "{$item->name} tersisa {$item->stock} {$item->unit}. Minimum {$item->min_stock} {$item->unit}.",
                'status' => 'open',
            ]);

            return;
        }

        if (! $item->is_low_stock && $activeAlert !== null) {
            $activeAlert->update([
                'status' => 'resolved',
                'resolved_at' => now(),
            ]);
        }
    }
}
