<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use App\Services\CourseEnhancementService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourseEnhancementServiceTest extends TestCase
{
    use RefreshDatabase;

    private CourseEnhancementService $service;
    private Course $course;
    private User $instructor;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Cache::flush();

        $this->service = new CourseEnhancementService();

        // Create test data
        $this->instructor = User::factory()->create([
            'role' => 'instructor',
        ]);

        $category = Category::factory()->create();
        $level = Level::factory()->create();

        $this->course = Course::factory()->create([
            'user_id' => $this->instructor->id,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'title' => 'Test Course',
            'thumbnail' => null,
            'preview_video' => null,
        ]);
    }

    public function test_attaches_preview_video_successfully(): void
    {
        $video = UploadedFile::fake()->create('preview.mp4', 50000, 'video/mp4');

        $videoPath = $this->service->attachPreviewVideo($this->course, $video);

        $this->assertNotNull($videoPath);
        $this->assertStringContainsString('courses/preview-videos', $videoPath);
        Storage::disk('public')->assertExists($videoPath);

        $this->course->refresh();
        $this->assertEquals($videoPath, $this->course->preview_video);
    }

    public function test_validates_preview_video_file_size(): void
    {
        // Create a video larger than 100MB (exceeds MAX_PREVIEW_VIDEO_SIZE)
        $largeVideo = UploadedFile::fake()->create('preview.mp4', 110000, 'video/mp4');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Preview video file size must be less than 100MB');

        $this->service->attachPreviewVideo($this->course, $largeVideo);
    }

    public function test_validates_preview_video_format(): void
    {
        $invalidVideo = UploadedFile::fake()->create('preview.avi', 5000, 'video/x-msvideo');
        $invalidVideo = UploadedFile::fake()->create('preview.mkv', 5000, 'video/x-matroska');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid video format');

        $this->service->attachPreviewVideo($this->course, $invalidVideo);
    }

    public function test_replaces_existing_preview_video(): void
    {
        // Upload first video
        $firstVideo = UploadedFile::fake()->create('preview1.mp4', 5000, 'video/mp4');
        $firstPath = $this->service->attachPreviewVideo($this->course, $firstVideo);

        Storage::disk('public')->assertExists($firstPath);

        // Upload second video
        $secondVideo = UploadedFile::fake()->create('preview2.mp4', 5000, 'video/mp4');
        $secondPath = $this->service->attachPreviewVideo($this->course, $secondVideo);

        // First video should be deleted
        Storage::disk('public')->assertMissing($firstPath);
        // Second video should exist
        Storage::disk('public')->assertExists($secondPath);

        $this->course->refresh();
        $this->assertEquals($secondPath, $this->course->preview_video);
    }

    public function test_generates_thumbnail_successfully(): void
    {
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 300, 300);

        $thumbnailPath = $this->service->generateThumbnail($this->course, $thumbnail);

        $this->assertNotNull($thumbnailPath);
        $this->assertStringContainsString('courses/thumbnails', $thumbnailPath);
        Storage::disk('public')->assertExists($thumbnailPath);

        $this->course->refresh();
        $this->assertEquals($thumbnailPath, $this->course->thumbnail);
        $this->assertNotNull($this->course->thumbnail_updated_at);
    }

    public function test_replaces_existing_thumbnail(): void
    {
        // Upload first thumbnail
        $firstThumbnail = UploadedFile::fake()->image('thumb1.jpg', 300, 300);
        $firstPath = $this->service->generateThumbnail($this->course, $firstThumbnail);

        Storage::disk('public')->assertExists($firstPath);

        // Upload second thumbnail
        $secondThumbnail = UploadedFile::fake()->image('thumb2.jpg', 300, 300);
        $secondPath = $this->service->generateThumbnail($this->course, $secondThumbnail);

        // First thumbnail should be deleted
        Storage::disk('public')->assertMissing($firstPath);
        // Second thumbnail should exist
        Storage::disk('public')->assertExists($secondPath);

        $this->course->refresh();
        $this->assertEquals($secondPath, $this->course->thumbnail);
    }

    public function test_updates_thumbnail_timestamp_when_no_file_provided(): void
    {
        $this->course->update(['thumbnail_updated_at' => null]);

        $result = $this->service->generateThumbnail($this->course, null);

        $this->course->refresh();
        $this->assertNotNull($this->course->thumbnail_updated_at);
        $this->assertEquals($this->course->thumbnail ?? '', $result);
    }

    public function test_returns_preview_video_url_for_local_storage(): void
    {
        $video = UploadedFile::fake()->create('preview.mp4', 5000, 'video/mp4');
        $videoPath = $this->service->attachPreviewVideo($this->course, $video);

        $url = $this->service->getPreviewVideoUrl($this->course);

        $this->assertNotNull($url);
        $this->assertStringContainsString('storage', $url);
        $this->assertStringContainsString('courses/preview-videos', $url);
    }

    public function test_returns_null_for_preview_url_when_no_video(): void
    {
        $url = $this->service->getPreviewVideoUrl($this->course);

        $this->assertNull($url);
    }

    public function test_updates_course_metadata_successfully(): void
    {
        $metadata = [
            'description' => 'Updated description',
            'outcomes' => 'Learn advanced concepts',
            'requirements' => 'Basic knowledge required',
            'is_featured' => true,
            'duration' => '10 weeks',
        ];

        $updatedCourse = $this->service->updateCourseMetadata($this->course, $metadata);

        $this->assertEquals('Updated description', $updatedCourse->description);
        $this->assertEquals('Learn advanced concepts', $updatedCourse->outcomes);
        $this->assertEquals('Basic knowledge required', $updatedCourse->requirements);
        $this->assertTrue($updatedCourse->is_featured);
        $this->assertEquals('10 weeks', $updatedCourse->duration);
    }

    public function test_filters_disallowed_metadata_fields(): void
    {
        $metadata = [
            'description' => 'Updated description',
            'price' => 9999.99, // This should be filtered out
            'status' => 'published', // This should be filtered out
        ];

        $updatedCourse = $this->service->updateCourseMetadata($this->course, $metadata);

        $this->assertEquals('Updated description', $updatedCourse->description);
        // Price and status should remain unchanged
        $this->assertNotEquals(9999.99, $updatedCourse->price);
    }

    public function test_gets_course_with_enhancements(): void
    {
        // Set up course with enhancements
        $video = UploadedFile::fake()->create('preview.mp4', 5000, 'video/mp4');
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 300, 300);

        $this->service->attachPreviewVideo($this->course, $video);
        $this->service->generateThumbnail($this->course, $thumbnail);
        $this->service->updateCourseMetadata($this->course, [
            'is_featured' => true,
            'outcomes' => 'Master the subject',
        ]);

        $enhancedData = $this->service->getCourseWithEnhancements($this->course->id);

        $this->assertIsArray($enhancedData);
        $this->assertEquals($this->course->id, $enhancedData['id']);
        $this->assertEquals($this->course->title, $enhancedData['title']);
        $this->assertNotNull($enhancedData['thumbnail']);
        $this->assertNotNull($enhancedData['preview_video_url']);
        $this->assertTrue($enhancedData['is_featured']);
        $this->assertEquals('Master the subject', $enhancedData['outcomes']);
        $this->assertIsArray($enhancedData['instructor']);
        $this->assertEquals($this->instructor->name, $enhancedData['instructor']['name']);
        $this->assertIsArray($enhancedData['category']);
        $this->assertIsArray($enhancedData['tags']);
    }

    public function test_throws_exception_for_non_existent_course(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->service->getCourseWithEnhancements(99999);
    }

    public function test_clears_preview_url_cache_on_video_update(): void
    {
        $video = UploadedFile::fake()->create('preview.mp4', 5000, 'video/mp4');
        
        // First attach should cache the URL
        $this->service->attachPreviewVideo($this->course, $video);
        $firstUrl = $this->service->getPreviewVideoUrl($this->course);

        // Attach a new video should clear the cache
        $newVideo = UploadedFile::fake()->create('preview2.mp4', 5000, 'video/mp4');
        $this->service->attachPreviewVideo($this->course, $newVideo);

        // The cache should be cleared, so we get a new URL
        $secondUrl = $this->service->getPreviewVideoUrl($this->course);

        $this->assertNotEquals($firstUrl, $secondUrl);
    }
}
