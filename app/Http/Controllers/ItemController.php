<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\CloudinaryStorageService;
use App\Services\InventoryAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function store(
        Request $request,
        InventoryAlertService $alerts,
        CloudinaryStorageService $cloudinaryService
    ): RedirectResponse {
        $validated = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $uploadedImage = $cloudinaryService->uploadImage($request->file('image'));
            $validated['image_url'] = $uploadedImage['url'];
            $validated['image_public_id'] = $uploadedImage['public_id'];
        }

        if ($request->hasFile('video')) {
            $uploadedVideo = $cloudinaryService->uploadVideo($request->file('video'));
            $validated['video_url'] = $uploadedVideo['url'];
            $validated['video_public_id'] = $uploadedVideo['public_id'];
        }

        $item = Item::create($validated);

        $alerts->syncLowStockAlert($item);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    public function update(
        Request $request,
        Item $item,
        InventoryAlertService $alerts,
        CloudinaryStorageService $cloudinaryService
    ): RedirectResponse {
        $validated = $this->validatedData($request, $item);

        if ($request->hasFile('image')) {
            if ($item->image_public_id) {
                $cloudinaryService->delete($item->image_public_id, 'image');
            }
            $uploadedImage = $cloudinaryService->uploadImage($request->file('image'));
            $validated['image_url'] = $uploadedImage['url'];
            $validated['image_public_id'] = $uploadedImage['public_id'];
        }

        if ($request->hasFile('video')) {
            if ($item->video_public_id) {
                $cloudinaryService->delete($item->video_public_id, 'video');
            }
            $uploadedVideo = $cloudinaryService->uploadVideo($request->file('video'));
            $validated['video_url'] = $uploadedVideo['url'];
            $validated['video_public_id'] = $uploadedVideo['public_id'];
        }

        $item->update($validated);

        $alerts->syncLowStockAlert($item->refresh());

        return redirect()
            ->route('dashboard')
            ->with('status', 'Data produk berhasil diperbarui.');
    }

    public function toggleStatus(Item $item): RedirectResponse
    {
        $item->update(['is_active' => ! $item->is_active]);

        $statusText = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('dashboard')
            ->with('status', "Status produk {$item->name} berhasil {$statusText}.");
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Produk berhasil dihapus dari inventory.');
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
            'barcode' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:120'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov', 'max:51200'],
        ]);
    }
}
