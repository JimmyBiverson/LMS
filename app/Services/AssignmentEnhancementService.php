<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Traits\HandleUploads;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Assignment Enhancement Service
 * 
 * Manages enhanced assignment features including file uploads with validation,
 * deadline enforcement with late penalties, and rubric-based grading.
 */
class AssignmentEnhancementService
{
    use HandleUploads;

    /**
     * Maximum number of files allowed per submission
     */
    private const MAX_FILES_PER_SUBMISSION = 5;

    /**
     * Create a new assignment with enhanced features.
     * 
     * @param array $data Assignment data
     * @return Assignment The created assignment
     * @throws Exception If creation fails
     */
    public function createAssignment(array $data): Assignment
    {
        try {
            DB::beginTransaction();

            // Validate required fields
            $this->validateAssignmentData($data);

            // Process grading rubric if provided
            if (isset($data['grading_rubric']) && is_string($data['grading_rubric'])) {
                $data['grading_rubric'] = json_decode($data['grading_rubric'], true);
            }

            // Process allowed file types if provided
            if (isset($data['allowed_file_types']) && is_string($data['allowed_file_types'])) {
                $data['allowed_file_types'] = array_map('trim', explode(',', $data['allowed_file_types']));
            }

            // Create the assignment
            $assignment = Assignment::create($data);

            DB::commit();

            Log::info("Assignment created successfully", [
                'assignment_id' => $assignment->id,
                'course_id' => $assignment->course_id,
                'title' => $assignment->title,
            ]);

            return $assignment;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create assignment", [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Submit an assignment with file uploads.
     * 
     * Validates all files against assignment constraints (size, type, count),
     * stores files, and creates submission record.
     * 
     * @param Assignment $assignment The assignment to submit to
     * @param User $student The student submitting
     * @param array $files Array of UploadedFile objects
     * @param string|null $submissionText Optional submission text
     * @return AssignmentSubmission The created submission
     * @throws Exception If submission fails or validation fails
     */
    public function submitAssignment(
        Assignment $assignment,
        User $student,
        array $files,
        ?string $submissionText = null
    ): AssignmentSubmission {
        try {
            DB::beginTransaction();

            // Check if assignment accepts submissions
            $acceptanceStatus = $assignment->acceptsSubmissions();
            if (!$acceptanceStatus['accepts_submissions']) {
                throw new Exception($acceptanceStatus['reason']);
            }

            // Validate file count
            if (count($files) > self::MAX_FILES_PER_SUBMISSION) {
                throw new Exception("Maximum " . self::MAX_FILES_PER_SUBMISSION . " files allowed per submission");
            }

            // Validate and store each file
            $storedFiles = [];
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $validation = $assignment->validateFileUpload($file);
                    if (!$validation['valid']) {
                        throw new Exception($validation['error']);
                    }

                    $storedPath = $this->storeSubmissionFile($file, $assignment->id);
                    $storedFiles[] = [
                        'path' => $storedPath,
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];
                }
            }

            // Create submission record
            $submission = AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'user_id' => $student->id,
                'submission_text' => $submissionText,
                'file_url' => !empty($storedFiles) ? json_encode($storedFiles) : null,
                'status' => 'submitted',
                'submitted_at' => Carbon::now(),
            ]);

            DB::commit();

            Log::info("Assignment submitted successfully", [
                'submission_id' => $submission->id,
                'assignment_id' => $assignment->id,
                'user_id' => $student->id,
                'files_count' => count($storedFiles),
            ]);

            return $submission;
        } catch (Exception $e) {
            DB::rollBack();

            // Clean up any stored files on failure
            if (!empty($storedFiles)) {
                foreach ($storedFiles as $fileInfo) {
                    $this->deleteFile($fileInfo['path']);
                }
            }

            Log::error("Failed to submit assignment", [
                'assignment_id' => $assignment->id,
                'user_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Grade a submission with feedback and rubric support.
     * 
     * Applies late penalties if applicable, stores feedback,
     * and updates submission status.
     * 
     * @param AssignmentSubmission $submission The submission to grade
     * @param float $score The raw score before any penalties
     * @param string $feedback Feedback text for the student
     * @param array|null $rubricScores Optional rubric scores breakdown
     * @return void
     * @throws Exception If grading fails
     */
    public function gradeSubmission(
        AssignmentSubmission $submission,
        float $score,
        string $feedback,
        ?array $rubricScores = null
    ): void {
        try {
            DB::beginTransaction();

            $assignment = $submission->assignment;

            // Validate score is within bounds
            if ($score < 0 || $score > $assignment->total_marks) {
                throw new Exception("Score must be between 0 and {$assignment->total_marks}");
            }

            // Calculate final score with late penalty if applicable
            $finalScore = $this->calculateGrade($submission, $score);

            // Update submission with grade and feedback
            $submission->update([
                'score' => $finalScore,
                'feedback' => $feedback,
                'status' => 'graded',
                'graded_at' => Carbon::now(),
            ]);

            // Store rubric scores in metadata if provided
            if ($rubricScores) {
                $metadata = [
                    'rubric_scores' => $rubricScores,
                    'raw_score' => $score,
                    'final_score' => $finalScore,
                ];
                
                // Store metadata in a JSON field if available, or log it
                Log::info("Rubric scores applied", [
                    'submission_id' => $submission->id,
                    'metadata' => $metadata,
                ]);
            }

            DB::commit();

            Log::info("Assignment graded successfully", [
                'submission_id' => $submission->id,
                'assignment_id' => $assignment->id,
                'raw_score' => $score,
                'final_score' => $finalScore,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to grade submission", [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Calculate the final grade for a submission including late penalties.
     * 
     * @param AssignmentSubmission $submission The submission to calculate grade for
     * @param float|null $rawScore Optional raw score (uses submission score if null)
     * @return float The final calculated grade
     */
    public function calculateGrade(AssignmentSubmission $submission, ?float $rawScore = null): float
    {
        $assignment = $submission->assignment;
        $score = $rawScore ?? $submission->score ?? 0;

        // Apply late penalty if submission was late
        if ($submission->submitted_at) {
            $penaltyResult = $assignment->calculateLatePenalty($score, $submission->submitted_at);
            return $penaltyResult['penalized_score'];
        }

        return $score;
    }

    /**
     * Get a submission with all its files and metadata.
     * 
     * @param int $submissionId The submission ID
     * @return array Submission data with files
     * @throws Exception If submission not found
     */
    public function getSubmissionWithFiles(int $submissionId): array
    {
        $submission = AssignmentSubmission::with(['assignment', 'user'])
            ->findOrFail($submissionId);

        // Parse file URLs if stored as JSON
        $files = [];
        if ($submission->file_url) {
            $decoded = json_decode($submission->file_url, true);
            if (is_array($decoded)) {
                $files = array_map(function ($fileInfo) {
                    return [
                        'path' => $fileInfo['path'],
                        'original_name' => $fileInfo['original_name'],
                        'size' => $fileInfo['size'],
                        'size_formatted' => $this->formatBytes($fileInfo['size']),
                        'mime_type' => $fileInfo['mime_type'],
                        'url' => Storage::url($fileInfo['path']),
                    ];
                }, $decoded);
            }
        }

        return [
            'id' => $submission->id,
            'assignment' => [
                'id' => $submission->assignment->id,
                'title' => $submission->assignment->title,
                'total_marks' => $submission->assignment->total_marks,
                'due_date' => $submission->assignment->due_date?->toIso8601String(),
            ],
            'student' => [
                'id' => $submission->user->id,
                'name' => $submission->user->name,
                'email' => $submission->user->email,
            ],
            'submission_text' => $submission->submission_text,
            'files' => $files,
            'score' => $submission->score,
            'feedback' => $submission->feedback,
            'status' => $submission->status,
            'submitted_at' => $submission->submitted_at?->toIso8601String(),
            'graded_at' => $submission->graded_at?->toIso8601String(),
            'is_late' => $submission->assignment->isSubmissionLate($submission->submitted_at),
        ];
    }

    /**
     * Check the deadline status of an assignment.
     * 
     * @param Assignment $assignment The assignment to check
     * @return array Deadline status information
     */
    public function checkDeadline(Assignment $assignment): array
    {
        $timeRemaining = $assignment->getTimeRemaining();
        $acceptsSubmissions = $assignment->acceptsSubmissions();
        $lateSubmissionInfo = $assignment->checkLateSubmission();

        return [
            'has_deadline' => $assignment->due_date !== null,
            'due_date' => $assignment->due_date?->toIso8601String(),
            'time_remaining' => $timeRemaining,
            'accepts_submissions' => $acceptsSubmissions['accepts_submissions'],
            'submission_message' => $acceptsSubmissions['reason'],
            'late_submission_allowed' => $assignment->late_submission_allowed,
            'late_penalty_percent' => $assignment->late_penalty_percent,
            'late_submission_info' => $lateSubmissionInfo,
        ];
    }

    /**
     * Store a submission file.
     * 
     * @param UploadedFile $file The file to store
     * @param int $assignmentId The assignment ID
     * @return string The stored file path
     * @throws Exception If storage fails
     */
    private function storeSubmissionFile(UploadedFile $file, int $assignmentId): string
    {
        try {
            $folder = "assignments/submissions/{$assignmentId}";
            $this->ensureDirectoryExists($folder);

            $filename = $this->generateUniqueFilename($file);
            $stored = Storage::disk('public')->putFileAs($folder, $file, $filename);

            if (!$stored) {
                throw new Exception('Failed to store submission file');
            }

            Log::info("Submission file uploaded successfully", [
                'assignment_id' => $assignmentId,
                'path' => $stored,
            ]);

            return $stored;
        } catch (Exception $e) {
            Log::error("Submission file upload failed", [
                'assignment_id' => $assignmentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Validate assignment data before creation.
     * 
     * @param array $data The assignment data to validate
     * @return void
     * @throws Exception If validation fails
     */
    private function validateAssignmentData(array $data): void
    {
        if (empty($data['title'])) {
            throw new Exception('Assignment title is required');
        }

        if (empty($data['course_id'])) {
            throw new Exception('Course ID is required');
        }

        if (isset($data['total_marks']) && $data['total_marks'] <= 0) {
            throw new Exception('Total marks must be greater than 0');
        }

        if (isset($data['max_file_size_mb']) && $data['max_file_size_mb'] <= 0) {
            throw new Exception('Maximum file size must be greater than 0');
        }

        if (isset($data['late_penalty_percent']) && ($data['late_penalty_percent'] < 0 || $data['late_penalty_percent'] > 100)) {
            throw new Exception('Late penalty percent must be between 0 and 100');
        }
    }
}
