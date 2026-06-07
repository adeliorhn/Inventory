<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\InventoryAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function store(Request $request, InventoryAlertService $alerts): RedirectResponse
    {
        $item = Item::create($this->validatedData($request));

        $alerts->syncLowStockAlert($item);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Barang berhasil dicatat.');
    }

    public function update(Request $request, Item $item, InventoryAlertService $alerts): RedirectResponse
    {
        $item->update($this->validatedData($request, $item));

        $alerts->syncLowStockAlert($item->refresh());

        return redirect()
            ->route('dashboard')
            ->with('status', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Barang dihapus dari inventory.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Item $item = null): array
    {
        return $request->validate([
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('items', 'sku')->ignore($item),
            ],
            'name' => ['required', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:120'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
