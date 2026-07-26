<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiPageNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_multi_page_routes_are_accessible_and_render_correctly(): void
    {
        $user = User::factory()->create();

        // 1. Dashboard
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Inventory');

        // 2. Produk
        $response = $this->actingAs($user)->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Produk');

        // 3. Stok
        $response = $this->actingAs($user)->get(route('stocks.index'));
        $response->assertStatus(200);
        $response->assertSee('Stok Barang Inventory');

        // 4. Riwayat Stok
        $response = $this->actingAs($user)->get(route('stock-history.index'));
        $response->assertStatus(200);
        $response->assertSee('Riwayat Mutasi Stok');

        // 5. Kategori
        $response = $this->actingAs($user)->get(route('categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Kategori Produk');

        // 6. Upload Gambar
        $response = $this->actingAs($user)->get(route('uploads.index'));
        $response->assertStatus(200);
        $response->assertSee('Upload Gambar dan Media');

        // 7. Laporan
        $response = $this->actingAs($user)->get(route('reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Laporan stok dan pergerakan barang');

        // 8. Pengaturan
        $response = $this->actingAs($user)->get(route('settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan dan Profil Admin');
    }
}
