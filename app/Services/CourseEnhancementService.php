<?php

namespace App\Services;

use App\Models\Course;
use App\Traits\HandleUploads;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Course Enhancement Service
 * 
 * Handles enhanced course features including multimedia and previews.
 * Manages course thumbnails, preview videos, signed URLs for media access,
 * and course metadata updates.
 */
class CourseEnhancementService
{
    use HandleUploads;

    /**
     * Cache TTL for preview video URLs (in seconds)
     */
    private const CACHE_TTL_PREVIEW_URL = 3600; // 1 hour

    /**
     * Maximum file size for preview videos (in bytes)
     * Default: 100MB
     */
    private const MAX_PREVIEW_VIDEO_SIZE = 104857600;

    /**
     * Allowed video formats for preview videos
     */
    private const ALLOWED_VIDEO_FORMATS = ['mp4', 'mov', 'avi', 'webm', 'ogg'];

    /**
     * Attach a preview video to a course.
     * 
     * Validates the video file (size, format) and stores it.
     * Updates the course with the video path and clears related caches.
     * 
     * @param Course $course The course to attach the video to
     * @param UploadedFile $video The uploaded video file
     * @return string The path to the stored video
     * @throws Exception If validation or upload fails
     */
    public function attachPreviewVideo(Course $course, UploadedFile $video): string
    {
        // Validate video file
        $this->validatePreviewVideo($video);

        try {
            // Delete existing preview video if present
            if ($course->preview_video) {
                $this->deleteFile($course->preview_video);
            }

            // Store the video in a course-specific folder
            $folder = "courses/preview-videos";
            $videoPath = $this->storeVideo($video, $folder);

            // Update course with new video path
            $course->update([
                'preview_video' => $videoPath,
            ]);

            // Clear preview URL cache for this course
            $this->clearPreviewUrlCache($course->id);

            Log::info("Preview video attached to course", [
                'course_id' => $course->id,
                'video_path' => $videoPath,
            ]);

            return $videoPath;
        } catch (Exception $e) {
            Log::error("Failed to attach preview video", [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Generate or upload a thumbnail for a course.
     * 
     * If a thumbnail file is provided, it will be validated and stored.
     * If no thumbnail is provided, a placeholder or default thumbnail can be used.
     * 
     * @param Course $course The course to generate/upload thumbnail for
     * @param UploadedFile|null $thumbnail The uploaded thumbnail file (optional)
     * @return string The path to the stored thumbnail
     * @throws Exception If upload fails
     */
    public function generateThumbnail(Course $course, ?UploadedFile $thumbnail = null): string
    {
        try {
            if ($thumbnail) {
                // Delete existing thumbnail if present
                if ($course->thumbnail) {
                    $this->deleteFile($course->thumbnail);
                }

                // Store the new thumbnail
                $folder = "courses/thumbnails";
                $thumbnailPath = $this->storeThumbnail($thumbnail, $folder);

                // Update course with new thumbnail
                $course->update([
                    'thumbnail' => $thumbnailPath,
                    'thumbnail_updated_at' => now(),
                ]);

                Log::info("Thumbnail uploaded for course", [
                    'course_id' => $course->id,
                    'thumbnail_path' => $thumbnailPath,
                ]);

                return $thumbnailPath;
            } else {
                // If no thumbnail provided, update only the timestamp
                // (Useful for tracking when thumbnail generation was attempted)
                $course->update([
                    'thumbnail_updated_at' => now(),
                ]);

                return $course->thumbnail ?? '';
            }
        } catch (Exception $e) {
            Log::error("Failed to generate thumbnail", [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get a preview video URL for a course.
     * 
     * For local storage, returns a standard storage URL.
     * For cloud storage (S3), generates a signed/temporary URL with expiration.
     * Results are cached to reduce overhead.
     * 
     * @param Course $course The course to get the preview URL for
     * @param int $expiresIn Number of seconds until the URL expires (default: 3600)
     * @return string|null The preview video URL or null if no video exists
     */
    public function getPreviewVideoUrl(Course $course, int $expiresIn = 3600): ?string
    {
        if (!$course->preview_video) {
            return null;
        }

        // Check if we're using cloud storage (S3)
        $disk = config('filesystems.default');
        
        if ($disk === 's3') {
            // For S3, generate a temporary signed URL and cache it
            $cacheKey = $this->getPreviewUrlCacheKey($course->id);
            
            return Cache::remember($cacheKey, min($expiresIn, self::CACHE_TTL_PREVIEW_URL), function () use ($course, $expiresIn) {
                try {
                    return Storage::disk('s3')->temporaryUrl(
                        $course->preview_video,
                        now()->addSeconds($expiresIn)
                    );
                } catch (Exception $e) {
                    Log::error("Failed to generate signed URL for preview video", [
                        'course_id' => $course->id,
                        'error' => $e->getMessage(),
                    ]);
                    return null;
                }
            });
        } else {
            // For local storage, return the public URL
            return Storage::url($course->preview_video);
        }
    }

    /**
     * Update course metadata.
     * 
     * Updates various course fields including description, outcomes, requirements,
     * featured status, and custom metadata JSON field.
     * 
     * @param Course $course The course to update
     * @param array $metadata Array of metadata to update
     * @return Course The updated course model
     * @throws Exception If update fails
     */
    public function updateCourseMetadata(Course $course, array $metadata): Course
    {
        try {
            // Define allowed metadata fields
            $allowedFields = [
                'description',
                'outcomes',
                'requirements',
                'is_featured',
                'metadata',
                'duration',
            ];

            // Filter metadata to only include allowed fields
            $updateData = array_intersect_key($metadata, array_flip($allowedFields));

            // Validate and update
            $course->update($updateData);

            // Clear course-related caches
            $this->clearCourseCache($course->id);

            Log::info("Course metadata updated", [
                'course_id' => $course->id,
                'fields' => array_keys($updateData),
            ]);

            return $course->fresh();
        } catch (Exception $e) {
            Log::error("Failed to update course metadata", [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get course with enhancements.
     * 
     * Retrieves a course with all its enhancements including preview video URL,
     * enrollment count, average rating, and related data.
     * Results are structured for frontend consumption.
     * 
     * @param int $courseId The ID of the course to retrieve
     * @return array Enhanced course data
     * @throws Exception If course not found
     */
    public function getCourseWithEnhancements(int $courseId): array
    {
        $course = Course::with([
            'instructor',
            'categoryRelation',
            'level',
            'tags',
        ])->findOrFail($courseId);

        // Build enhanced course data
        $enhancedData = [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->description,
            'price' => $course->price,
            'sale_price' => $course->sale_price,
            'display_price' => $course->displayPrice(),
            'is_free' => $course->isFree(),
            'has_sale' => $course->hasSale(),
            'duration' => $course->duration,
            'status' => $course->status,
            'level' => $course->level ? [
                'id' => $course->level->id,
                'name' => $course->level->name,
            ] : null,
            'thumbnail' => $course->thumbnail ? Storage::url($course->thumbnail) : null,
            'thumbnail_updated_at' => $course->thumbnail_updated_at?->toIso8601String(),
            'preview_video_url' => $this->getPreviewVideoUrl($course),
            'enrollment_count' => $course->enrollment_count ?? $course->enrollments()->count(),
            'average_rating' => $course->averageRating(),
            'is_featured' => $course->is_featured,
            'outcomes' => $course->outcomes,
            'requirements' => $course->requirements,
            'instructor' => [
                'id' => $course->instructor->id,
                'name' => $course->instructor->name,
                'email' => $course->instructor->email,
            ],
            'category' => $course->categoryRelation ? [
                'id' => $course->categoryRelation->id,
                'name' => $course->categoryRelation->name,
            ] : null,
            'tags' => $course->tags->map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])->toArray(),
            'metadata' => $course->metadata,
            'created_at' => $course->created_at->toIso8601String(),
            'updated_at' => $course->updated_at->toIso8601String(),
        ];

        return $enhancedData;
    }

    /**
     * Validate a preview video file.
     * 
     * Checks file size and format against allowed constraints.
     * 
     * @param UploadedFile $video The video file to validate
     * @return void
     * @throws Exception If validation fails
     */
    private function validatePreviewVideo(UploadedFile $video): void
    {
        // Check file size
        if ($video->getSize() > self::MAX_PREVIEW_VIDEO_SIZE) {
            $maxSizeMB = self::MAX_PREVIEW_VIDEO_SIZE / 1048576;
            throw new Exception("Preview video file size must be less than {$maxSizeMB}MB");
        }

        // Check file format
        $extension = strtolower($video->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_VIDEO_FORMATS)) {
            $allowed = implode(', ', array_map('strtoupper', self::ALLOWED_VIDEO_FORMATS));
            throw new Exception("Invalid video format. Allowed formats: {$allowed}");
        }

        // Additional mime type check
        $mime = $video->getMimeType();
        $validMimes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'video/ogg'];
        if (!in_array($mime, $validMimes)) {
            throw new Exception("Invalid video MIME type: {$mime}");
        }
    }

    /**
     * Get the cache key for a course preview URL.
     * 
     * @param int $courseId The course ID
     * @return string The cache key
     */
    private function getPreviewUrlCacheKey(int $courseId): string
    {
        return "course:preview_url:{$courseId}";
    }

    /**
     * Clear the preview URL cache for a course.
     * 
     * @param int $courseId The course ID
     * @return void
     */
    private function clearPreviewUrlCache(int $courseId): void
    {
        Cache::forget($this->getPreviewUrlCacheKey($courseId));
    }

    /**
     * Clear all course-related caches.
     * 
     * @param int $courseId The course ID
     * @return void
     */
    private function clearCourseCache(int $courseId): void
    {
        // Clear preview URL cache
        $this->clearPreviewUrlCache($courseId);
    }
}
