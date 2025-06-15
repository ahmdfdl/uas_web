<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait ImageUpload
{
    /**
     * Upload single image
     */
    public function uploadImage(UploadedFile $file, $directory = 'uploads', $resize = null)
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $filename;

        // If resize is specified, resize the image
        if ($resize) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getPathname());
            
            // Resize image
            if (is_array($resize)) {
                $image->resize($resize[0], $resize[1]);
            } else {
                $image->resize($resize, $resize);
            }
            
            // Save resized image
            Storage::disk('public')->put($path, (string) $image->encode());
        } else {
            // Save original image
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $filename;
    }

    /**
     * Upload multiple images
     */
    public function uploadImages(array $files, $directory = 'uploads', $resize = null)
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedFiles[] = $this->uploadImage($file, $directory, $resize);
            }
        }

        return $uploadedFiles;
    }

    /**
     * Delete image
     */
    public function deleteImage($filename, $directory = 'uploads')
    {
        if ($filename) {
            $path = $directory . '/' . $filename;
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Delete multiple images
     */
    public function deleteImages(array $filenames, $directory = 'uploads')
    {
        foreach ($filenames as $filename) {
            $this->deleteImage($filename, $directory);
        }
    }

    /**
     * Update image (delete old, upload new)
     */
    public function updateImage(UploadedFile $newFile, $oldFilename = null, $directory = 'uploads', $resize = null)
    {
        // Delete old image if exists
        if ($oldFilename) {
            $this->deleteImage($oldFilename, $directory);
        }

        // Upload new image
        return $this->uploadImage($newFile, $directory, $resize);
    }

    /**
     * Get image URL
     */
    public function getImageUrl($filename, $directory = 'uploads', $default = null)
    {
        if ($filename && Storage::disk('public')->exists($directory . '/' . $filename)) {
            return asset('storage/' . $directory . '/' . $filename);
        }

        return $default ?: asset('assets/img/default-image.jpg');
    }

    /**
     * Create thumbnail
     */
    public function createThumbnail(UploadedFile $file, $directory = 'uploads', $thumbSize = 300)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());
        
        // Generate filenames
        $originalFilename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $thumbFilename = 'thumb_' . $originalFilename;
        
        // Save original
        Storage::disk('public')->putFileAs($directory, $file, $originalFilename);
        
        // Create and save thumbnail
        $thumbnail = $image->resize($thumbSize, $thumbSize);
        Storage::disk('public')->put($directory . '/' . $thumbFilename, (string) $thumbnail->encode());
        
        return [
            'original' => $originalFilename,
            'thumbnail' => $thumbFilename
        ];
    }

    /**
     * Validate image
     */
    public function validateImage(UploadedFile $file, $maxSize = 2048, $allowedTypes = ['jpeg', 'jpg', 'png', 'gif'])
    {
        $errors = [];

        // Check file size (in KB)
        if ($file->getSize() > $maxSize * 1024) {
            $errors[] = "File size must be less than {$maxSize}KB";
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            $errors[] = "File type must be: " . implode(', ', $allowedTypes);
        }

        return empty($errors) ? true : $errors;
    }
}