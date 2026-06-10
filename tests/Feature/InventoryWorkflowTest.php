<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Item;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
    }

    public function test_item_can_be_recorded_and_low_stock_alert_is_created(): void
    {
        $response = $this->post(route('items.store'), [
            'sku' => 'BRG-100',
            'name' => 'Mouse Wireless',
            'category' => 'Elektronik',
            'unit' => 'pcs',
            'location' => 'Gudang IT',
            'stock' => 2,
            'min_stock' => 5,
            'description' => 'Perangkat input cadangan.',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('items', [
            'sku' => 'BRG-100',
            'stock' => 2,
            'min_stock' => 5,
        ]);

        $this->assertDatabaseHas('alerts', [
            'type' => 'low_stock',
            'status' => 'open',
        ]);
    }

    public function test_stock_movement_updates_stock_and_report_page_loads(): void
    {
        $item = Item::create([
            'sku' => 'BRG-101',
            'name' => 'Keyboard',
            'unit' => 'pcs',
            'stock' => 10,
            'min_stock' => 3,
        ]);

        $response = $this->post(route('stock-movements.store'), [
            'item_id' => $item->id,
            'type' => 'out',
            'quantity' => 4,
            'actor' => 'Operasional',
            'note' => 'Distribusi perangkat.',
            'occurred_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertSame(6, $item->refresh()->stock);
        $this->assertDatabaseHas('stock_movements', [
            'item_id' => $item->id,
            'type' => 'out',
            'quantity' => 4,
            'stock_before' => 10,
            'stock_after' => 6,
        ]);

        $this->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Laporan stok dan pergerakan barang');
    }

    public function test_team_message_can_be_sent_and_resolved(): void
    {
        $this->post(route('messages.store'), [
            'sender_name' => 'Gudang',
            'recipient_team' => 'Pembelian',
            'subject' => 'Restock tinta',
            'body' => 'Tinta printer perlu ditambah minggu ini.',
            'priority' => 'urgent',
        ])->assertRedirect(route('dashboard'));

        $message = Message::firstOrFail();

        $this->post(route('messages.resolve', $message))
            ->assertRedirect(route('dashboard'));

        $this->assertSame('done', $message->refresh()->status);
    }

    public function test_alert_can_be_marked_as_read(): void
    {
        $item = Item::create([
            'sku' => 'BRG-102',
            'name' => 'Kertas Thermal',
            'unit' => 'roll',
            'stock' => 1,
            'min_stock' => 5,
        ]);

        $alert = Alert::create([
            'item_id' => $item->id,
            'type' => 'low_stock',
            'severity' => 'warning',
            'title' => 'Stok rendah',
            'body' => 'Kertas thermal hampir habis.',
            'status' => 'open',
        ]);

        $this->post(route('alerts.read', $alert))
            ->assertRedirect(route('dashboard'));

        $this->assertSame('read', $alert->refresh()->status);
    }
}
