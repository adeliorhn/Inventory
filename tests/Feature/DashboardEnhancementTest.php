<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardEnhancementTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_successfully_with_saas_layout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Inventory');
        $response->assertSee('StockFlow');
        $response->assertSee('Total Produk');
        $response->assertSee('Total Stok Unit');
        $response->assertSee('Aksi Cepat Admin');
    }

    public function test_admin_can_store_product_with_price_and_barcode(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('items.store'), [
            'sku' => 'SKU-TEST-99',
            'barcode' => '899999999',
            'name' => 'Kamera DSLR Professional',
            'category' => 'Elektronik',
            'unit' => 'unit',
            'location' => 'Gudang B',
            'stock' => 15,
            'min_stock' => 3,
            'price' => 15000000,
            'description' => 'Kamera DSLR High End',
        ]);

        $response->assertRedirect(route('dashboard'));

        $item = Item::where('sku', 'SKU-TEST-99')->first();
        $this->assertNotNull($item);
        $this->assertSame('899999999', $item->barcode);
        $this->assertEquals(15000000, $item->price);
        $this->assertTrue($item->is_active);
    }

    public function test_admin_can_toggle_product_active_status(): void
    {
        $user = User::factory()->create();
        $item = Item::create([
            'sku' => 'SKU-ACT-1',
            'name' => 'Produk Test',
            'unit' => 'pcs',
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->patch(route('items.toggle-status', $item));

        $response->assertRedirect(route('dashboard'));
        $this->assertFalse($item->refresh()->is_active);
    }

    public function test_admin_can_record_stock_movement(): void
    {
        $user = User::factory()->create();
        $item = Item::create([
            'sku' => 'SKU-MOV-1',
            'name' => 'Produk Mutasi',
            'unit' => 'pcs',
            'stock' => 10,
            'min_stock' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('stock-movements.store'), [
            'item_id' => $item->id,
            'type' => 'in',
            'quantity' => 5,
            'actor' => 'Admin Test',
            'note' => 'Restok barang dari supplier',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals(15, $item->refresh()->stock);
    }
}
