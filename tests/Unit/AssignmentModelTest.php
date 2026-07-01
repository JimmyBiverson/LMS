<?php

namespace Tests\Unit;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AssignmentModelTest extends TestCase
{
    use RefreshDatabase;

    private Assignment $assignment;
    private User $instructor;
    private Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        // Create instructor and course
        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->course = Course::factory()->create(['user_id' => $this->instructor->id]);

        // Create a basic assignment
        $this->assignment = Assignment::create([
            'course_id' => $this->course->id,
            'user_id' => $this->instructor->id,
            'title' => 'Test Assignment',
            'description' => 'Test Description',
            'instructions' => 'Test Instructions',
            'due_date' => Carbon::now()->addDays(7),
            'total_marks' => 100,
            'status' => 'active',
            'max_file_size_mb' => 10,
            'allowed_file_types' => ['pdf', 'docx', 'txt'],
            'late_submission_allowed' => true,
            'late_penalty_percent' => 10.00,
        ]);
    }

    public function test_validates_file_size_correctly(): void
    {
        // Create a mock file that is 15MB (exceeds limit)
        $largeFile = UploadedFile::fake()->create('document.pdf', 15360); // 15MB in KB

        $result = $this->assignment->validateFileUpload($largeFile);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('exceeds maximum allowed size', $result['error']);
    }

    public function test_accepts_file_within_size_limit(): void
    {
        // Create a mock file that is 5MB (within limit)
        $validFile = UploadedFile::fake()->create('document.pdf', 5120); // 5MB in KB

        $result = $this->assignment->validateFileUpload($validFile);

        $this->assertTrue($result['valid']);
        $this->assertNull($result['error']);
    }

    public function test_validates_file_type_correctly(): void
    {
        // Create a file with disallowed extension
        $invalidFile = UploadedFile::fake()->create('image.jpg', 1024);

        $result = $this->assignment->validateFileUpload($invalidFile);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('is not allowed', $result['error']);
    }

    public function test_accepts_allowed_file_types(): void
    {
        $validFile = UploadedFile::fake()->create('document.pdf', 1024);

        $result = $this->assignment->validateFileUpload($validFile);

        $this->assertTrue($result['valid']);
        $this->assertNull($result['error']);
    }

    public function test_accepts_all_file_types_when_no_restrictions_set(): void
    {
        $this->assignment->update(['allowed_file_types' => null]);
        
        $anyFile = UploadedFile::fake()->create('file.xyz', 1024);

        $result = $this->assignment->validateFileUpload($anyFile);

        $this->assertTrue($result['valid']);
        $this->assertNull($result['error']);
    }

    public function test_detects_when_deadline_has_passed(): void
    {
        $this->assignment->update(['due_date' => Carbon::now()->subDays(1)]);

        $this->assertTrue($this->assignment->isDeadlinePassed());
    }

    public function test_detects_when_deadline_has_not_passed(): void
    {
        $this->assignment->update(['due_date' => Carbon::now()->addDays(7)]);

        $this->assertFalse($this->assignment->isDeadlinePassed());
    }

    public function test_returns_false_for_deadline_check_when_no_due_date_set(): void
    {
        $this->assignment->update(['due_date' => null]);

        $this->assertFalse($this->assignment->isDeadlinePassed());
    }

    public function test_detects_late_submission(): void
    {
        $this->assignment->update(['due_date' => Carbon::now()->subHours(2)]);
        
        $this->assertTrue($this->assignment->isSubmissionLate());
    }

    public function test_detects_on_time_submission(): void
    {
        $this->assignment->update(['due_date' => Carbon::now()->addDays(1)]);
        
        $this->assertFalse($this->assignment->isSubmissionLate());
    }

    public function test_checks_late_submission_with_custom_date(): void
    {
        $this->assignment->update(['due_date' => Carbon::parse('2024-01-15 12:00:00')]);
        
        $submissionDate = Carbon::parse('2024-01-16 14:00:00');
        
        $result = $this->assignment->checkLateSubmission($submissionDate);

        $this->assertTrue($result['accepted']); // late_submission_allowed is true
        $this->assertEquals(26, $result['hours_late']); // 26 hours late
    }

    public function test_rejects_late_submission_when_not_allowed(): void
    {
        $this->assignment->update([
            'due_date' => Carbon::now()->subHours(5),
            'late_submission_allowed' => false,
        ]);

        $result = $this->assignment->checkLateSubmission();

        $this->assertFalse($result['accepted']);
        $this->assertGreaterThan(0, $result['hours_late']);
        $this->assertStringContainsString('not allowed', $result['message']);
    }

    public function test_accepts_late_submission_when_allowed(): void
    {
        $this->assignment->update([
            'due_date' => Carbon::now()->subHours(3),
            'late_submission_allowed' => true,
        ]);

        $result = $this->assignment->checkLateSubmission();

        $this->assertTrue($result['accepted']);
        $this->assertGreaterThan(0, $result['hours_late']);
    }

    public function test_calculates_late_penalty_correctly(): void
    {
        $this->assignment->update([
            'due_date' => Carbon::now()->subHours(2),
            'late_submission_allowed' => true,
            'late_penalty_percent' => 10.00,
        ]);

        $originalScore = 90;
        $result = $this->assignment->calculateLatePenalty($originalScore);

        $this->assertEquals(81.0, $result['penalized_score']); // 90 - (90 * 0.10) = 81
        $this->assertEquals(9.0, $result['penalty_applied']);
        $this->assertTrue($result['is_late']);
    }

    public function test_does_not_apply_penalty_for_on_time_submission(): void
    {
        $this->assignment->update(['due_date' => Carbon::now()->addDays(1)]);

        $originalScore = 85;
        $result = $this->assignment->calculateLatePenalty($originalScore);

        $this->assertEquals(85.0, $result['penalized_score']);
        $this->assertEquals(0.0, $result['penalty_applied']);
        $this->assertFalse($result['is_late']);
    }

    public function test_does_not_apply_penalty_when_no_penalty_percent_set(): void
    {
        $this->assignment->update([
            'due_date' => Carbon::now()->subHours(1),
            'late_submission_allowed' => true,
            'late_penalty_percent' => null,
        ]);

        $originalScore = 95;
        $result = $this->assignment->calculateLatePenalty($originalScore);

        $this->assertEquals(95.0, $result['penalized_score']);
        $this->assertEquals(0.0, $result['penalty_applied']);
        $this->assertTrue($result['is_late']);
    }

    public function test_ensures_penalized_score_does_not_go_negative(): void
    {
        $this->assignment->update([
            'due_date' => Carbon::now()->subHours(1),
            'late_submission_allowed' => true,
            'late_penalty_percent' => 50.00,
        ]);

        $originalScore = 10;
        $result = $this->assignment->calculateLatePenalty($originalScore);

        $this->assertEquals(5.0, $result['penalized_score']);
        $this->assertEquals(5.0, $result['penalty_applied']);
    }

    public function test_calculates_time_remaining_correctly(): void
    {
        $futureDate = Carbon::now()->addDays(2)->addHours(3);
        $this->assignment->update(['due_date' => $futureDate]);

        $result = $this->assignment->getTimeRemaining();

        $this->assertFalse($result['expired']);
        $this->assertEquals(2, $result['days']);
        // Hours can be 2 or 3 depending on execution timing
        $this->assertGreaterThanOrEqual(2, $result['hours']);
        $this->assertLessThanOrEqual(3, $result['hours']);
        $this->assertStringContainsString('from now', $result['human_readable']);
    }

    public function test_detects_expired_deadline(): void
    {
        $pastDate = Carbon::now()->subDays(1);
        $this->assignment->update(['due_date' => $pastDate]);

        $result = $this->assignment->getTimeRemaining();

        $this->assertTrue($result['expired']);
        $this->assertStringContainsString('passed', $result['human_readable']);
    }

    public function test_handles_no_deadline_in_time_remaining(): void
    {
        $this->assignment->update(['due_date' => null]);

        $result = $this->assignment->getTimeRemaining();

        $this->assertFalse($result['expired']);
        $this->assertNull($result['days']);
        $this->assertEquals('No deadline', $result['human_readable']);
    }

    public function test_accepts_submissions_before_deadline(): void
    {
        $this->assignment->update(['due_date' => Carbon::now()->addDays(3)]);

        $result = $this->assignment->acceptsSubmissions();

        $this->assertTrue($result['accepts_submissions']);
        $this->assertNull($result['reason']);
    }

    public function test_rejects_submissions_after_deadline_when_late_not_allowed(): void
    {
        $this->assignment->update([
            'due_date' => Carbon::now()->subDays(1),
            'late_submission_allowed' => false,
        ]);

        $result = $this->assignment->acceptsSubmissions();

        $this->assertFalse($result['accepts_submissions']);
        $this->assertStringContainsString('not allowed', $result['reason']);
    }

    public function test_accepts_late_submissions_when_allowed(): void
    {
        $this->assignment->update([
            'due_date' => Carbon::now()->subDays(1),
            'late_submission_allowed' => true,
        ]);

        $result = $this->assignment->acceptsSubmissions();

        $this->assertTrue($result['accepts_submissions']);
        $this->assertStringContainsString('Late submission', $result['reason']);
    }

    public function test_formats_allowed_file_types_correctly(): void
    {
        $formatted = $this->assignment->getAllowedFileTypesFormatted();

        $this->assertEquals('PDF, DOCX, TXT', $formatted);
    }

    public function test_returns_all_file_types_when_no_restrictions(): void
    {
        $this->assignment->update(['allowed_file_types' => null]);

        $formatted = $this->assignment->getAllowedFileTypesFormatted();

        $this->assertEquals('All file types', $formatted);
    }

    public function test_casts_due_date_to_datetime(): void
    {
        $this->assertInstanceOf(Carbon::class, $this->assignment->due_date);
    }

    public function test_casts_allowed_file_types_to_array(): void
    {
        $this->assertIsArray($this->assignment->allowed_file_types);
    }

    public function test_casts_late_submission_allowed_to_boolean(): void
    {
        $this->assertIsBool($this->assignment->late_submission_allowed);
    }

    public function test_has_course_relationship(): void
    {
        $this->assertInstanceOf(Course::class, $this->assignment->course);
        $this->assertEquals($this->course->id, $this->assignment->course->id);
    }
}
