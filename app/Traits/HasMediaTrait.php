<?php

namespace App\Traits;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

trait HasMediaTrait
{
    /**
     * Upload a file to Cloudinary and return the public ID.
     *
     * @param  UploadedFile|string  $file
     * @param  string  $folder
     * @return string|null
     */
    public function uploadMedia($file, string $folder = 'media'): ?string
    {
        if (!$file) {
            return null;
        }

        $cloudinaryUrl = config('services.cloudinary.url');
        $cloudinary = new Cloudinary($cloudinaryUrl);

        $filePath = $file instanceof UploadedFile ? $file->getRealPath() : (string) $file;

        $response = $cloudinary->uploadApi()->upload($filePath, [
            'folder' => $folder,
        ]);

        return $response['public_id'];
    }

    /**
     * Get the full Cloudinary secure URL based on the public ID.
     *
     * @param  string|null  $publicId
     * @return string|null
     */
    public function getMedia(?string $publicId): ?string
    {
        if (!$publicId) {
            return null;
        }

        // If it's already a full URL, just return it
        if (str_starts_with($publicId, 'http://') || str_starts_with($publicId, 'https://')) {
            return $publicId;
        }

        $cloudinaryUrl = config('services.cloudinary.url');
        $cloudinary = new Cloudinary($cloudinaryUrl);

        return (string) $cloudinary->image($publicId)->toUrl();
    }
}
