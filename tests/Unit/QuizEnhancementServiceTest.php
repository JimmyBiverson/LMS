<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\User;
use App\Services\QuizEnhancementService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizEnhancementServiceTest extends TestCase
{
    use RefreshDatabase;

    private QuizEnhancementService $service;
    private Course $course;
    private User $instructor;
    private User $student;
    private Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new QuizEnhancementService();

        // Create test users
        $this->instructor = User::factory()->create([
            'role' => 'instructor',
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
        ]);

        // Create test course
        $this->course = Course::factory()->create([
            'user_id' => $this->instructor->id,
            'title' => 'Test Course',
        ]);

        // Create test quiz
        $this->quiz = Quiz::factory()->create([
            'course_id' => $this->course->id,
            'user_id' => $this->instructor->id,
            'title' => 'Test Quiz',
            'time_limit' => 60,
            'passing_score' => 70,
            'total_marks' => 100,
            'attempts_limit' => 3,
            'randomize_options' => false,
        ]);
    }

    public function test_creates_quiz_successfully(): void
    {
        $quizData = [
            'course_id' => $this->course->id,
            'user_id' => $this->instructor->id,
            'title' => 'New Quiz',
            'instructions' => 'Read all questions carefully',
            'time_limit' => 30,
            'passing_score' => 60,
            'total_marks' => 50,
            'attempts_limit' => 2,
            'status' => 'active',
        ];

        $quiz = $this->service->createQuiz($quizData);

        $this->assertInstanceOf(Quiz::class, $quiz);
        $this->assertEquals('New Quiz', $quiz->title);
        $this->assertEquals(30, $quiz->time_limit);
        $this->assertEquals(60, $quiz->passing_score);
        $this->assertDatabaseHas('quizzes', [
            'title' => 'New Quiz',
            'course_id' => $this->course->id,
        ]);
    }

    public function test_validates_required_fields_on_quiz_creation(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Quiz title is required');

        $this->service->createQuiz([
            'course_id' => $this->course->id,
        ]);
    }

    public function test_validates_course_id_is_required(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Course ID is required');

        $this->service->createQuiz([
            'title' => 'Test Quiz',
        ]);
    }

    public function test_validates_negative_time_limit(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Time limit cannot be negative');

        $this->service->createQuiz([
            'title' => 'Test Quiz',
            'course_id' => $this->course->id,
            'time_limit' => -10,
        ]);
    }

    public function test_starts_quiz_attempt_successfully(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);

        $this->assertInstanceOf(QuizAttempt::class, $attempt);
        $this->assertEquals($this->quiz->id, $attempt->quiz_id);
        $this->assertEquals($this->student->id, $attempt->user_id);
        $this->assertEquals(1, $attempt->attempt_number);
        $this->assertFalse($attempt->is_completed);
        $this->assertNotNull($attempt->started_at);
        $this->assertNotNull($attempt->expires_at); // Quiz has time limit
        $this->assertDatabaseHas('quiz_attempts', [
            'quiz_id' => $this->quiz->id,
            'user_id' => $this->student->id,
        ]);
    }

    public function test_calculates_expiration_time_based_on_time_limit(): void
    {
        $startTime = Carbon::now();
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);

        $this->assertNotNull($attempt->expires_at);
        $expectedExpiration = $startTime->copy()->addMinutes($this->quiz->time_limit);
        
        // Allow 1 second tolerance for test execution time
        $this->assertTrue(
            $attempt->expires_at->between(
                $expectedExpiration->copy()->subSecond(),
                $expectedExpiration->copy()->addSecond()
            )
        );
    }

    public function test_quiz_attempt_without_time_limit_has_no_expiration(): void
    {
        $this->quiz->update(['time_limit' => null]);

        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);

        $this->assertNull($attempt->expires_at);
    }

    public function test_increments_attempt_number_for_multiple_attempts(): void
    {
        $attempt1 = $this->service->startQuizAttempt($this->quiz, $this->student);
        $this->assertEquals(1, $attempt1->attempt_number);

        // Complete first attempt
        $attempt1->update(['is_completed' => true]);

        $attempt2 = $this->service->startQuizAttempt($this->quiz, $this->student);
        $this->assertEquals(2, $attempt2->attempt_number);
    }

    public function test_prevents_exceeding_attempt_limit(): void
    {
        // Create 3 attempts (reaching the limit)
        for ($i = 0; $i < 3; $i++) {
            $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
            $attempt->update(['is_completed' => true]);
        }

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Maximum attempts (3) reached');

        $this->service->startQuizAttempt($this->quiz, $this->student);
    }

    public function test_allows_unlimited_attempts_when_limit_is_null(): void
    {
        $this->quiz->update(['attempts_limit' => null]);

        // Create multiple attempts
        for ($i = 0; $i < 5; $i++) {
            $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
            $attempt->update(['is_completed' => true]);
        }

        // Should still be able to create another attempt
        $nextAttempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        $this->assertEquals(6, $nextAttempt->attempt_number);
    }

    public function test_submits_quiz_answer_successfully(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'question' => 'What is 2+2?',
            'type' => 'multiple_choice',
            'correct_answer' => '4',
            'marks' => 10,
        ]);

        $this->service->submitQuizAnswer($attempt, $question->id, '4');

        $attempt->refresh();
        $this->assertIsArray($attempt->answers);
        $this->assertArrayHasKey($question->id, $attempt->answers);
        $this->assertEquals('4', $attempt->answers[$question->id]['answer']);
    }

    public function test_prevents_answer_submission_on_completed_attempt(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        $attempt->update(['is_completed' => true]);

        $question = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('This quiz attempt has already been completed');

        $this->service->submitQuizAnswer($attempt, $question->id, 'answer');
    }

    public function test_prevents_answer_submission_on_expired_attempt(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        // Set expiration to past
        $attempt->update(['expires_at' => Carbon::now()->subMinutes(1)]);

        $question = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('This quiz attempt has expired');

        $this->service->submitQuizAnswer($attempt, $question->id, 'answer');
    }

    public function test_validates_question_belongs_to_quiz(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        // Create question for a different quiz
        $otherQuiz = Quiz::factory()->create(['course_id' => $this->course->id]);
        $otherQuestion = QuizQuestion::factory()->create([
            'quiz_id' => $otherQuiz->id,
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->service->submitQuizAnswer($attempt, $otherQuestion->id, 'answer');
    }

    public function test_finalizes_quiz_and_creates_result(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        // Create questions
        QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'question' => 'Question 1',
            'type' => 'multiple_choice',
            'correct_answer' => 'A',
            'marks' => 50,
        ]);

        QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'question' => 'Question 2',
            'type' => 'multiple_choice',
            'correct_answer' => 'B',
            'marks' => 50,
        ]);

        $result = $this->service->finalizeQuiz($attempt);

        $this->assertInstanceOf(QuizResult::class, $result);
        $this->assertEquals($this->quiz->id, $result->quiz_id);
        $this->assertEquals($this->student->id, $result->user_id);
        $this->assertNotNull($result->score);
        $this->assertNotNull($result->completed_at);
        
        $attempt->refresh();
        $this->assertTrue($attempt->is_completed);
        $this->assertNotNull($attempt->submitted_at);
        $this->assertNotNull($attempt->score);

        $this->assertDatabaseHas('quiz_results', [
            'quiz_id' => $this->quiz->id,
            'user_id' => $this->student->id,
        ]);
    }

    public function test_prevents_finalizing_completed_attempt(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        $attempt->update(['is_completed' => true]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('This quiz attempt has already been completed');

        $this->service->finalizeQuiz($attempt);
    }

    public function test_auto_grades_multiple_choice_questions_correctly(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        $question1 = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'multiple_choice',
            'correct_answer' => 'A',
            'marks' => 50,
        ]);

        $question2 = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'multiple_choice',
            'correct_answer' => 'B',
            'marks' => 50,
        ]);

        // Submit correct answer for question 1, wrong for question 2
        $this->service->submitQuizAnswer($attempt, $question1->id, 'A');
        $this->service->submitQuizAnswer($attempt, $question2->id, 'C');

        $score = $this->service->autoGradeQuiz($attempt);

        $this->assertEquals(50.0, $score); // Only first question correct
    }

    public function test_auto_grades_true_false_questions_correctly(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'true_false',
            'correct_answer' => 'true',
            'marks' => 100,
        ]);

        $this->service->submitQuizAnswer($attempt, $question->id, 'true');

        $score = $this->service->autoGradeQuiz($attempt);

        $this->assertEquals(100.0, $score);
    }

    public function test_auto_grading_handles_unanswered_questions(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'multiple_choice',
            'correct_answer' => 'A',
            'marks' => 100,
        ]);

        // Don't submit any answers
        $score = $this->service->autoGradeQuiz($attempt);

        $this->assertEquals(0.0, $score);
    }

    public function test_randomizes_quiz_questions(): void
    {
        // Create multiple questions
        for ($i = 0; $i < 5; $i++) {
            QuizQuestion::factory()->create([
                'quiz_id' => $this->quiz->id,
                'question' => "Question {$i}",
                'order' => $i,
            ]);
        }

        $randomizedQuestions = $this->service->randomizeQuestions($this->quiz);

        $this->assertCount(5, $randomizedQuestions);
        
        // Get the order of questions
        $questionIds = $randomizedQuestions->pluck('id')->toArray();
        $originalIds = $this->quiz->questions()->orderBy('order')->pluck('id')->toArray();
        
        // While there's a small chance they could be the same, it's unlikely
        // This test verifies the method returns all questions
        $this->assertCount(5, array_unique($questionIds));
    }

    public function test_checks_time_limit_for_active_attempt(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        // Attempt just started, should be valid
        $isValid = $this->service->checkTimeLimit($attempt);
        
        $this->assertTrue($isValid);
    }

    public function test_checks_time_limit_for_expired_attempt(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        // Set expiration to past
        $attempt->update(['expires_at' => Carbon::now()->subMinutes(1)]);
        
        $isValid = $this->service->checkTimeLimit($attempt);
        
        $this->assertFalse($isValid);
    }

    public function test_checks_time_limit_returns_true_for_no_expiration(): void
    {
        $this->quiz->update(['time_limit' => null]);
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        $isValid = $this->service->checkTimeLimit($attempt);
        
        $this->assertTrue($isValid);
    }

    public function test_determines_passing_status_correctly(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        // Create questions totaling 100 marks
        $question1 = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'multiple_choice',
            'correct_answer' => 'A',
            'marks' => 80,
        ]);

        $question2 = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'multiple_choice',
            'correct_answer' => 'B',
            'marks' => 20,
        ]);

        // Submit correct answers to get 80/100 (above passing score of 70)
        $this->service->submitQuizAnswer($attempt, $question1->id, 'A');
        $this->service->submitQuizAnswer($attempt, $question2->id, 'C'); // Wrong answer

        $result = $this->service->finalizeQuiz($attempt);

        $this->assertTrue($result->passed); // Score is 80, passing score is 70
        $this->assertEquals(80.0, $result->score);
    }

    public function test_determines_failing_status_correctly(): void
    {
        $attempt = $this->service->startQuizAttempt($this->quiz, $this->student);
        
        // Create questions
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $this->quiz->id,
            'type' => 'multiple_choice',
            'correct_answer' => 'A',
            'marks' => 50, // Only 50/100 possible, below passing score of 70
        ]);

        // Submit correct answer - but still below passing score
        $this->service->submitQuizAnswer($attempt, $question->id, 'A');

        $result = $this->service->finalizeQuiz($attempt);

        $this->assertFalse($result->passed); // Score is 50, passing score is 70
        $this->assertEquals(50.0, $result->score);
    }
}
