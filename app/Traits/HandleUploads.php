<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;
use Exception;

trait HandleUploads
{
    /**
     * Store a thumbnail with validation and error handling
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null Path to stored file or null on failure
     * @throws Exception
     */
    public function storeThumbnail(UploadedFile $file, string $folder = 'courses/thumbnails'): ?string
    {
        try {
            // Validate file is actually an image
            if (!$this->isValidImage($file)) {
                throw new Exception('File is not a valid image');
            }

            // Validate file size (max 5MB for thumbnails)
            if ($file->getSize() > 5242880) { // 5MB
                throw new Exception('Thumbnail file size must be less than 5MB');
            }

            // Ensure directory exists
            $this->ensureDirectoryExists($folder);

            // Process image and convert to WebP
            $img = Image::decode($file->getRealPath());
            $img->resizeDown(1200, 675);

            $timestamp = now()->timestamp;
            $random = \Illuminate\Support\Str::random(8);
            $filename = "{$timestamp}_{$random}.webp";
            $path = "{$folder}/{$filename}";

            $stored = Storage::disk('public')->put($path, $img->encodeUsingFileExtension('webp', quality: 85)->toString());

            if (!$stored) {
                throw new Exception('Failed to store thumbnail file');
            }

            Log::channel('uploads')->info("Thumbnail uploaded successfully: {$path}");
            return $path;
        } catch (Exception $e) {
            Log::channel('uploads')->error("Thumbnail upload failed: " . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);
            throw $e;
        }
    }

    /**
     * Store a video file with validation
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null
     * @throws Exception
     */
    public function storeVideo(UploadedFile $file, string $folder = 'lessons/videos'): ?string
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension());
            $validExtensions = ['mp4', 'mov', 'avi', 'webm', 'ogg'];

            if (!in_array($extension, $validExtensions)) {
                throw new Exception('Invalid video format. Allowed: MP4, MOV, AVI, WebM, OGG');
            }

            // Validate file size (max 500MB for videos)
            if ($file->getSize() > 524288000) { // 500MB
                throw new Exception('Video file size must be less than 500MB');
            }

            // Ensure directory exists
            $this->ensureDirectoryExists($folder);

            // Generate unique filename preserving extension
            $filename = $this->generateUniqueFilename($file);
            $stored = Storage::disk('public')->putFileAs($folder, $file, $filename);

            if (!$stored) {
                $stored = $file->storeAs($folder, $filename, 'public');
            }

            if (!$stored) {
                throw new Exception('Failed to store video file');
            }

            Log::channel('uploads')->info("Video uploaded successfully: {$stored}");
            return $stored;
        } catch (Exception $e) {
            Log::channel('uploads')->error("Video upload failed: " . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'extension' => $extension,
            ]);
            throw $e;
        }
    }

    /**
     * Store a document file with validation
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null
     * @throws Exception
     */
    public function storeDocument(UploadedFile $file, string $folder = 'lessons/documents'): ?string
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension());
            $validExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];

            if (!in_array($extension, $validExtensions)) {
                throw new Exception('Invalid document format. Allowed: PDF, Word, PowerPoint, Excel');
            }

            // Validate file size (max 50MB for documents)
            if ($file->getSize() > 52428800) { // 50MB
                throw new Exception('Document file size must be less than 50MB');
            }

            // Ensure directory exists
            $this->ensureDirectoryExists($folder);

            // Generate unique filename
            $filename = $this->generateUniqueFilename($file);
            $stored = Storage::disk('public')->putFileAs($folder, $file, $filename);

            if (!$stored) {
                $stored = $file->storeAs($folder, $filename, 'public');
            }

            if (!$stored) {
                throw new Exception('Failed to store document file');
            }

            Log::channel('uploads')->info("Document uploaded successfully: {$stored}");
            return $stored;
        } catch (Exception $e) {
            Log::channel('uploads')->error("Document upload failed: " . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'extension' => $extension,
            ]);
            throw $e;
        }
    }

    /**
     * Validate if file is a valid image
     *
     * @param UploadedFile $file
     * @return bool
     */
    protected function isValidImage(UploadedFile $file): bool
    {
        $validMimes = ['image/jpeg', 'image/png', 'image/webp'];
        return in_array($file->getMimeType(), $validMimes);
    }

    /**
     * Generate unique filename while preserving extension
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $timestamp = now()->timestamp;
        $random = \Illuminate\Support\Str::random(8);
        $extension = $file->getClientOriginalExtension();
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Ensure upload directory exists, create if not
     *
     * @param string $folder
     * @return void
     */
    protected function ensureDirectoryExists(string $folder): void
    {
        try {
            Storage::disk('public')->makeDirectory($folder);
        } catch (Exception $e) {
            Log::channel('uploads')->warning("Could not ensure directory exists: " . $e->getMessage());
        }
    }

    /**
     * Get a friendly upload error message for a file field.
     *
     * @param string $field
     * @param string $label
     * @return string|null
     */
    public function getFileUploadErrorMessage(string $field, string $label): ?string
    {
        if (!isset($_FILES[$field])) {
            return null;
        }

        $error = $_FILES[$field]['error'] ?? UPLOAD_ERR_OK;
        if ($error === UPLOAD_ERR_OK || $error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $maxUpload = ini_get('upload_max_filesize');
        $maxPost = ini_get('post_max_size');

        return match ($error) {
            UPLOAD_ERR_INI_SIZE => "{$label} is larger than the server upload limit ({$maxUpload}). Please choose a smaller file.",
            UPLOAD_ERR_FORM_SIZE => "{$label} exceeds the form upload limit ({$maxPost}). Please choose a smaller file.",
            UPLOAD_ERR_PARTIAL => "{$label} was only partially uploaded. Please try again.",
            UPLOAD_ERR_NO_TMP_DIR => "{$label} could not be uploaded because the temporary folder is missing.",
            UPLOAD_ERR_CANT_WRITE => "{$label} could not be saved to disk. Please contact the site administrator.",
            UPLOAD_ERR_EXTENSION => "{$label} upload was blocked by a PHP extension.",
            default => "{$label} failed to upload. Please try again.",
        };
    }

    /**
     * Delete a file from storage
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::channel('uploads')->info("File deleted successfully: {$path}");
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::channel('uploads')->error("File deletion failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file size in human readable format
     *
     * @param int $bytes
     * @return string
     */
    public function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
