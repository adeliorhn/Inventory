<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Services\CloudinaryStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class CloudinaryMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
    }

    public function test_item_can_be_stored_with_media(): void
    {
        // Mock CloudinaryStorageService
        $mockService = Mockery::mock(CloudinaryStorageService::class);
        $mockService->shouldReceive('uploadImage')
            ->once()
            ->andReturn([
                'url' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg',
                'public_id' => 'sample_image_id',
            ]);
        $mockService->shouldReceive('uploadVideo')
            ->once()
            ->andReturn([
                'url' => 'https://res.cloudinary.com/demo/video/upload/sample.mp4',
                'public_id' => 'sample_video_id',
            ]);
        $this->app->instance(CloudinaryStorageService::class, $mockService);

        $response = $this->post(route('items.store'), [
            'sku' => 'BRG-999',
            'name' => 'Kamera DSLR',
            'category' => 'Elektronik',
            'unit' => 'unit',
            'location' => 'Rak A1',
            'stock' => 5,
            'min_stock' => 2,
            'description' => 'Kamera untuk dokumentasi.',
            'image' => UploadedFile::fake()->create('kamera.jpg', 100, 'image/jpeg'),
            'video' => UploadedFile::fake()->create('kamera.mp4', 1024, 'video/mp4'),
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('items', [
            'sku' => 'BRG-999',
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg',
            'image_public_id' => 'sample_image_id',
            'video_url' => 'https://res.cloudinary.com/demo/video/upload/sample.mp4',
            'video_public_id' => 'sample_video_id',
        ]);
    }

    public function test_item_media_are_deleted_with_item(): void
    {
        // Mock CloudinaryStorageService
        $mockService = Mockery::mock(CloudinaryStorageService::class);
        $mockService->shouldReceive('delete')
            ->once()
            ->with('sample_image_id', 'image')
            ->andReturn(true);
        $mockService->shouldReceive('delete')
            ->once()
            ->with('sample_video_id', 'video')
            ->andReturn(true);
        $this->app->instance(CloudinaryStorageService::class, $mockService);

        $item = Item::create([
            'sku' => 'BRG-998',
            'name' => 'Kamera Mirrorless',
            'unit' => 'unit',
            'stock' => 1,
            'min_stock' => 1,
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg',
            'image_public_id' => 'sample_image_id',
            'video_url' => 'https://res.cloudinary.com/demo/video/upload/sample.mp4',
            'video_public_id' => 'sample_video_id',
        ]);

        $item->delete();

        $this->assertDatabaseMissing('items', [
            'sku' => 'BRG-998',
        ]);
    }
}
