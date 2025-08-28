<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function ensure_folder_exists(string $path, string $disk = 'src'): void
    {
        if (!Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->makeDirectory($path);
        }
    }

    public static function storeResizedImage(
        UploadedFile $file,
        string $directory,
        string $prefix = '',
        int $maxWidth = 1280,
        string $disk = 'src'
    ): string {
        // Pastikan folder tersedia
        self::ensure_folder_exists($directory, $disk);

        // Buat nama file unik
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid($prefix, true) . '.' . $extension;

        // Buat instance manager untuk membaca file
        $image = ImageManager::imagick()->read($file->getPathname());

        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth); // maintain aspect ratio
        }

        // Encode ulang
        $encoded = $image->encode();

        // Simpan ke disk
        Storage::disk($disk)->put("{$directory}/{$filename}", $encoded);

        return $filename;
    }
}
