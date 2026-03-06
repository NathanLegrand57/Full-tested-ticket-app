<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ShowImageManager
{
    private static ?self $instance = null;

    /**
     * Retrieves the unique instance (Singleton).
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
    }

    /**
     * Handles the upload of the show image.
     */
    public function storeShowImage(UploadedFile $file, ?string $oldPath = null): string
    {
        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        // Unique filename and dedicated folder
        $path = $file->store('shows', 'public');

        return $path;
    }
}



