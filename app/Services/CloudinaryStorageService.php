<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryStorageService
{
    /**
     * Upload an image to Cloudinary.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array{url: string, public_id: string}
     */
    public function uploadImage(UploadedFile $file, string $folder = 'inventory/images'): array
    {
        $uploaded = Cloudinary::upload($file->getRealPath(), [
            'folder' => $folder,
        ]);

        return [
            'url' => $uploaded->getSecurePath(),
            'public_id' => $uploaded->getPublicId(),
        ];
    }

    /**
     * Upload a video to Cloudinary.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array{url: string, public_id: string}
     */
    public function uploadVideo(UploadedFile $file, string $folder = 'inventory/videos'): array
    {
        $uploaded = Cloudinary::uploadVideo($file->getRealPath(), [
            'folder' => $folder,
        ]);

        return [
            'url' => $uploaded->getSecurePath(),
            'public_id' => $uploaded->getPublicId(),
        ];
    }

    /**
     * Delete an asset from Cloudinary.
     *
     * @param string $publicId
     * @param string $resourceType 'image' or 'video'
     * @return bool
     */
    public function delete(string $publicId, string $resourceType = 'image'): bool
    {
        try {
            $options = [];
            if ($resourceType === 'video') {
                $options['resource_type'] = 'video';
            }

            Cloudinary::destroy($publicId, $options);
            return true;
        } catch (\Exception $e) {
            // Silently catch or log errors so database deletes/updates are not blocked
            logger()->error("Failed to delete Cloudinary asset ({$publicId}): " . $e->getMessage());
            return false;
        }
    }
}
