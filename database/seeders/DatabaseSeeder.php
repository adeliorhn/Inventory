<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Item;
use App\Models\Message;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Inventory',
            'email' => 'admin@example.com',
        ]);

        $items = collect([
            [
                'sku' => 'BRG-001',
                'name' => 'Kabel LAN Cat 6',
                'category' => 'Elektronik',
                'unit' => 'roll',
                'location' => 'Gudang A',
                'stock' => 12,
                'min_stock' => 8,
                'description' => 'Kabel jaringan untuk instalasi kantor.',
            ],
            [
                'sku' => 'BRG-002',
                'name' => 'Kertas A4 80gsm',
                'category' => 'ATK',
                'unit' => 'rim',
                'location' => 'Rak 2',
                'stock' => 4,
                'min_stock' => 10,
                'description' => 'Persediaan kertas harian administrasi.',
            ],
            [
                'sku' => 'BRG-003',
                'name' => 'Tinta Printer Hitam',
                'category' => 'ATK',
                'unit' => 'botol',
                'location' => 'Rak 3',
                'stock' => 18,
                'min_stock' => 6,
                'description' => 'Tinta printer untuk area operasional.',
            ],
        ])->map(fn (array $item) => Item::create($item));

        foreach ($items as $item) {
            StockMovement::create([
                'item_id' => $item->id,
                'type' => 'adjustment',
                'quantity' => $item->stock,
                'stock_before' => 0,
                'stock_after' => $item->stock,
                'actor' => 'Seeder',
                'note' => 'Saldo awal inventory.',
                'occurred_at' => now()->subDays(2),
            ]);
        }

        StockMovement::create([
            'item_id' => $items[0]->id,
            'type' => 'out',
            'quantity' => 3,
            'stock_before' => 15,
            'stock_after' => 12,
            'actor' => 'Tim IT',
            'note' => 'Pemakaian instalasi ruang meeting.',
            'occurred_at' => now()->subDay(),
        ]);

        Alert::create([
            'item_id' => $items[1]->id,
            'type' => 'low_stock',
            'severity' => 'warning',
            'title' => 'Stok rendah',
            'body' => 'Kertas A4 80gsm tersisa 4 rim. Minimum 10 rim.',
            'status' => 'open',
        ]);

        Message::create([
            'sender_name' => 'Gudang',
            'recipient_team' => 'Pembelian',
            'subject' => 'Restock kertas A4',
            'body' => 'Mohon proses pembelian kertas A4 karena stok sudah melewati batas minimum.',
            'priority' => 'urgent',
            'status' => 'open',
        ]);
    }
}
