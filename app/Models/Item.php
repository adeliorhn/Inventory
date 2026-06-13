<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'category',
        'unit',
        'location',
        'stock',
        'min_stock',
        'description',
        'image_url',
        'image_public_id',
        'video_url',
        'video_public_id',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'min_stock' => 'integer',
        ];
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    protected static function booted(): void
    {
        static::deleting(function (Item $item) {
            $storageService = resolve(\App\Services\CloudinaryStorageService::class);

            if ($item->image_public_id) {
                $storageService->delete($item->image_public_id, 'image');
            }

            if ($item->video_public_id) {
                $storageService->delete($item->video_public_id, 'video');
            }
        });
    }
}
