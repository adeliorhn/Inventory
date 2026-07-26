<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function index(): View
    {
        $itemsWithMedia = Item::query()
            ->whereNotNull('image_url')
            ->orWhereNotNull('video_url')
            ->orderByDesc('updated_at')
            ->get();

        $allItems = Item::query()->orderBy('name')->get();

        return view('uploads.index', [
            'itemsWithMedia' => $itemsWithMedia,
            'allItems' => $allItems,
            'totalMediaCount' => $itemsWithMedia->count(),
        ]);
    }
}
