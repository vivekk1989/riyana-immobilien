<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasMedia
{
    /**
     * Upload a file and return the path.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $disk
     * @return string|false
     */
    public function uploadMedia(UploadedFile $file, string $folder = 'uploads', string $disk = 'public')
    {
        return $file->store($folder, $disk);
    }

    /**
     * Delete a file from storage.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function deleteMedia(string $path, string $disk = 'public')
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
}
