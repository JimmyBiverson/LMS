<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use App\Models\Level;
use App\Models\Tag;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\SupportTicket;
use App\Models\Bundle;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Faq;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\HeroSection;
use App\Models\Page;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Certificate;
use App\Models\Noticeboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComprehensiveLmsTest extends TestCase
{
    use RefreshDatabase;

    // ─── Helper methods ─────────────────────────────────────────────

    private function createUser(string $role, array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'role' => $role,
            'password' => Hash::make('password123'),
            'status' => 'active',
        ], $overrides));
    }

    private function createCourse(array $overrides = []): Course
    {
        $data = array_merge([
            'user_id' => $this->createUser('instructor')->id,
            'title' => 'Test Course',
            'description' => 'Description',
            'payment_type' => 'free',
            'price' => 0,
            'status' => 'Active',
            'slug' => 'test-course-' . uniqid(),
        ], $overrides);

        return Course::create($data);
    }

    private function createEnrollment(array $overrides = []): Enrollment
    {
        $data = array_merge([
            'user_id' => $this->createUser('student')->id,
            'course_id' => $this->createCourse()->id,
            'amount_paid' => 0,
            'status' => 'in_progress',
        ], $overrides);

        return Enrollment::create($data);
    }

    // ════════════════════════════════════════════════════════════════
    // PHASE 1: ACCOUNT CREATION
    // ════════════════════════════════════════════════════════════════

    public function test_student_can_register()
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'student@test.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'student@test.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => 'student',
            'status' => 'active',
        ]);
    }

    public function test_instructor_can_register()
    {
        $response = $this->post('/register', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'instructor@test.com',
            'phone' => '0987654321',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'instructor',
            'designation' => 'Senior Developer',
        ]);

        $response->assertRedirect('/instructor');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'instructor@test.com',
            'designation' => 'Senior Developer',
            'role' => 'instructor',
        ]);
    }

    public function test_organization_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Acme Learning',
            'email' => 'org@test.com',
            'phone' => '5551234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'organization',
            'address' => '123 Business St, City',
        ]);

        $response->assertRedirect('/org');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'org@test.com',
            'name' => 'Acme Learning',
            'role' => 'organization',
        ]);
    }

    public function test_registration_validates_required_fields()
    {
        $response = $this->post('/register', [
            'role' => 'student',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'phone']);
    }

    public function test_registration_rejects_duplicate_email()
    {
        $this->createUser('student', ['email' => 'dupe@test.com']);

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'dupe@test.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_requires_designation_for_instructor()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'inst@test.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'instructor',
        ]);

        $response->assertSessionHasErrors(['designation']);
    }

    public function test_registration_requires_name_for_organization()
    {
        $response = $this->post('/register', [
            'email' => 'org2@test.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'organization',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_registration_rejects_password_mismatch()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@test.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'role' => 'student',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_become_instructor_standalone_works()
    {
        $response = $this->post('/become-instructor', [
            'first_name' => 'Bob',
            'last_name' => 'Builder',
            'email' => 'bob@test.com',
            'phone' => '1112223333',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'designation' => 'Architect',
            'about' => 'I love building things',
        ]);

        $response->assertRedirect('/instructor');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'bob@test.com',
            'role' => 'instructor',
            'bio' => 'I love building things',
        ]);
    }

    // ════════════════════════════════════════════════════════════════
    // PHASE 2: LOGIN
    // ════════════════════════════════════════════════════════════════

    public function test_student_can_login()
    {
        $this->createUser('student', ['email' => 'student-login@test.com']);

        $response = $this->post('/login', [
            'email' => 'student-login@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_instructor_can_login()
    {
        $this->createUser('instructor', ['email' => 'inst-login@test.com']);

        $response = $this->post('/login', [
            'email' => 'inst-login@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/instructor');
        $this->assertAuthenticated();
    }

    public function test_organization_can_login()
    {
        $this->createUser('organization', ['email' => 'org-login@test.com']);

        $response = $this->post('/login', [
            'email' => 'org-login@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/org');
        $this->assertAuthenticated();
    }

    public function test_admin_can_login_via_admin_endpoint()
    {
        $this->createUser('admin', ['email' => 'admin@test.com']);

        $response = $this->post('/admin/admin-login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_fails_for_inactive_user()
    {
        $this->createUser('student', [
            'email' => 'inactive@test.com',
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password()
    {
        $this->createUser('student', ['email' => 'badpw@test.com']);

        $response = $this->post('/login', [
            'email' => 'badpw@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_forgot_password_sends_reset_link()
    {
        $this->createUser('student', ['email' => 'reset@test.com']);

        $response = $this->post('/forgot-password', [
            'email' => 'reset@test.com',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_logout_works()
    {
        $user = $this->createUser('student');
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // ════════════════════════════════════════════════════════════════
    // PHASE 3: STUDENT FLOWS
    // ════════════════════════════════════════════════════════════════

    public function test_student_dashboard_shows_stats()
    {
        $student = $this->createUser('student');
        $course = $this->createCourse();
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 0,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($student)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_student_can_enroll_in_free_course()
    {
        $student = $this->createUser('student');
        $course = $this->createCourse(['slug' => 'test-course']);

        $response = $this->actingAs($student)->post('/enroll/' . $course->id);

        $response->assertRedirect('/courses/test-course');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 0,
            'status' => 'in_progress',
        ]);
    }

    public function test_student_cannot_enroll_twice()
    {
        $student = $this->createUser('student');
        $course = $this->createCourse(['slug' => 'test-course-2']);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 0,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($student)->post('/enroll/' . $course->id);

        $response->assertRedirect('/courses/test-course-2');
        $response->assertSessionHas('info');
    }

    public function test_non_student_cannot_enroll()
    {
        $instructor = $this->createUser('instructor');
        $course = $this->createCourse();

        $response = $this->actingAs($instructor)->post('/enroll/' . $course->id);

        $response->assertStatus(403);
    }

    public function test_student_can_toggle_lesson_completion()
    {
        $student = $this->createUser('student');
        $course = Course::create([
            'user_id' => $this->createUser('instructor')->id,
            'title' => 'Course',
            'description' => 'Desc',
            'payment_type' => 'free',
            'price' => 0,
            'status' => 'Active',
            'slug' => 'course-' . uniqid(),
        ]);
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lesson 1',
            'content' => 'Content',
            'status' => 'published',
        ]);
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 0,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($student)->post('/lessons/' . $lesson->id . '/toggle-completion');

        // Toggles return a redirect back
        $response->assertStatus(302);

        $this->assertDatabaseHas('lesson_completions', [
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_student_can_take_and_submit_quiz()
    {
        $student = $this->createUser('student');
        $instructor = $this->createUser('instructor');
        $course = Course::create([
            'user_id' => $instructor->id,
            'title' => 'Quiz Course',
            'description' => 'Desc',
            'payment_type' => 'free',
            'price' => 0,
            'status' => 'Active',
            'slug' => 'quiz-course-' . uniqid(),
        ]);
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'title' => 'Test Quiz',
            'passing_score' => 50,
            'status' => 'published',
        ]);
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'What is 2+2?',
            'type' => 'multiple_choice',
            'options' => ['3', '4', '5', '6'],
            'correct_answer' => '4',
            'marks' => 10,
        ]);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 0,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($student)->get('/dashboard/quizzes/' . $quiz->id . '/take');
        $response->assertStatus(200);

        $response = $this->actingAs($student)->post('/dashboard/quizzes/' . $quiz->id . '/submit', [
            'answers' => [$question->id => '4'],
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('quiz_results', [
            'user_id' => $student->id,
            'quiz_id' => $quiz->id,
        ]);
    }

    public function test_student_can_submit_assignment()
    {
        $student = $this->createUser('student');
        $instructor = $this->createUser('instructor');
        $course = Course::create([
            'user_id' => $instructor->id,
            'title' => 'Assignment Course',
            'description' => 'Desc',
            'payment_type' => 'free',
            'price' => 0,
            'status' => 'Active',
            'slug' => 'assign-course-' . uniqid(),
        ]);
        $assignment = Assignment::create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'title' => 'Test Assignment',
            'description' => 'Do this',
            'total_marks' => 100,
            'status' => 'published',
            'due_date' => now()->addDays(7),
        ]);

        $response = $this->actingAs($student)->post('/dashboard/assignments/' . $assignment->id . '/submit', [
            'submission_text' => 'Here is my work',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('assignment_submissions', [
            'user_id' => $student->id,
            'assignment_id' => $assignment->id,
        ]);
    }

    public function test_student_can_write_course_review()
    {
        $student = $this->createUser('student');
        $course = $this->createCourse();
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 0,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($student)->post('/dashboard/course-review/' . $course->id, [
            'rating' => 5,
            'review' => 'Great course!',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'rating' => 5,
        ]);
    }

    public function test_student_can_manage_wishlist()
    {
        $student = $this->createUser('student');
        $course = $this->createCourse();

        // Add to wishlist (redirect back)
        $response = $this->actingAs($student)->post('/dashboard/wishlists/toggle/' . $course->id);
        $response->assertStatus(302);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($student)->get('/dashboard/wishlists');
        $response->assertStatus(200);
    }

    public function test_student_can_create_support_ticket()
    {
        $student = $this->createUser('student');

        $response = $this->actingAs($student)->post('/dashboard/supports', [
            'subject' => 'Need help',
            'message' => 'I need assistance with a course.',
            'category' => 'General',
            'priority' => 'Medium',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $student->id,
            'subject' => 'Need help',
        ]);
    }

    public function test_student_can_update_profile()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'first_name' => 'Old',
            'last_name' => 'Name',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($student)->post('/profile', [
            'first_name' => 'New',
            'last_name' => 'Name',
            'phone' => '9998887777',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'first_name' => 'New',
            'phone' => '9998887777',
        ]);
    }

    public function test_student_can_change_password()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'password' => Hash::make('currentpassword'),
        ]);

        $response = $this->actingAs($student)->post('/dashboard/settings', [
            'current_password' => 'currentpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertTrue(Hash::check('newpassword123', $student->fresh()->password));
    }

    // ════════════════════════════════════════════════════════════════
    // PHASE 4: INSTRUCTOR FLOWS
    // ════════════════════════════════════════════════════════════════

    public function test_instructor_dashboard_shows_stats()
    {
        $instructor = $this->createUser('instructor');
        $this->createCourse(['user_id' => $instructor->id]);

        $response = $this->actingAs($instructor)->get('/instructor');
        $response->assertStatus(200);
    }

    public function test_instructor_can_create_course()
    {
        $instructor = $this->createUser('instructor');
        $category = Category::create(['name' => 'Web Dev', 'slug' => 'web-dev', 'status' => 'active']);
        $level = Level::create(['name' => 'Beginner', 'slug' => 'beginner', 'status' => 'active']);
        $tag = Tag::create(['name' => 'PHP', 'slug' => 'php']);

        Storage::fake('public');
        $thumbnail = UploadedFile::fake()->image('course.jpg', 300, 300);

        $response = $this->actingAs($instructor)->post('/instructor/courses', [
            'title' => 'Introduction to Laravel',
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => [$tag->id],
            'description' => 'A comprehensive Laravel course.',
            'outcomes' => 'Learn Laravel',
            'requirements' => 'Basic PHP',
            'payment_type' => 'paid',
            'price' => 49.99,
            'sale_price' => 29.99,
            'duration' => '10 hours',
            'status' => 'Active',
            'thumbnail' => $thumbnail,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('courses', [
            'title' => 'Introduction to Laravel',
            'user_id' => $instructor->id,
            'status' => 'Active',
        ]);
    }

    public function test_instructor_can_edit_course()
    {
        $instructor = $this->createUser('instructor');
        $course = Course::create([
            'user_id' => $instructor->id,
            'title' => 'Original Title',
            'description' => 'Original',
            'payment_type' => 'free',
            'price' => 0,
            'status' => 'Active',
            'slug' => 'original-' . uniqid(),
        ]);

        $response = $this->actingAs($instructor)->post('/instructor/courses/edit/' . $course->id, [
            'title' => 'Updated Title',
            'description' => 'Updated description.',
            'payment_type' => 'free',
            'status' => 'Draft',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Title',
            'status' => 'Draft',
        ]);
    }

    public function test_instructor_can_add_and_delete_lesson()
    {
        $instructor = $this->createUser('instructor');
        $course = $this->createCourse(['user_id' => $instructor->id]);

        $response = $this->actingAs($instructor)->post('/instructor/courses/' . $course->id . '/lessons', [
            'title' => 'Introduction to PHP',
            'content' => 'This lesson covers PHP basics.',
            'video_url' => 'https://example.com/video.mp4',
            'duration' => '15:00',
            'is_free_preview' => true,
            'status' => 'published',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'title' => 'Introduction to PHP',
        ]);

        $lesson = Lesson::first();
        $response = $this->actingAs($instructor)->post(
            '/instructor/courses/' . $course->id . '/lessons/' . $lesson->id . '/delete'
        );
        $response->assertRedirect();
        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
    }

    public function test_instructor_can_create_quiz()
    {
        $instructor = $this->createUser('instructor');
        $course = $this->createCourse(['user_id' => $instructor->id]);

        $response = $this->actingAs($instructor)->post('/instructor/courses/' . $course->id . '/quizzes', [
            'title' => 'PHP Basics Quiz',
            'passing_score' => 70,
            'time_limit' => 30,
            'status' => 'published',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('quizzes', [
            'course_id' => $course->id,
            'title' => 'PHP Basics Quiz',
        ]);
    }

    public function test_instructor_can_add_quiz_question()
    {
        $instructor = $this->createUser('instructor');
        $course = $this->createCourse(['user_id' => $instructor->id]);
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'title' => 'Quiz',
            'passing_score' => 50,
            'status' => 'published',
        ]);

        $response = $this->actingAs($instructor)->post('/instructor/quizzes/' . $quiz->id . '/questions', [
            'question' => 'What is PHP?',
            'type' => 'multiple_choice',
            'options' => ['Programming language', 'Database', 'Framework', 'OS'],
            'correct_answer' => 'Programming language',
            'marks' => 10,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('quiz_questions', [
            'quiz_id' => $quiz->id,
            'question' => 'What is PHP?',
        ]);
    }

    public function test_instructor_can_delete_quiz_question()
    {
        $instructor = $this->createUser('instructor');
        $course = $this->createCourse(['user_id' => $instructor->id]);
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'title' => 'Quiz',
            'passing_score' => 50,
            'total_marks' => 10,
            'status' => 'published',
        ]);
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Q?',
            'type' => 'multiple_choice',
            'options' => ['A', 'B'],
            'correct_answer' => 'A',
            'marks' => 10,
        ]);

        $response = $this->actingAs($instructor)->post('/instructor/quizzes/questions/' . $question->id . '/delete');
        $response->assertRedirect();
        $this->assertDatabaseMissing('quiz_questions', ['id' => $question->id]);
    }

    public function test_instructor_can_create_and_grade_assignment()
    {
        $instructor = $this->createUser('instructor');
        $student = $this->createUser('student');
        $course = $this->createCourse(['user_id' => $instructor->id]);

        $response = $this->actingAs($instructor)->post('/instructor/courses/' . $course->id . '/assignments', [
            'title' => 'Build a REST API',
            'description' => 'Create a RESTful API using Laravel',
            'total_marks' => 100,
            'status' => 'published',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('assignments', [
            'course_id' => $course->id,
            'title' => 'Build a REST API',
        ]);

        $assignment = Assignment::first();

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $student->id,
            'submission_text' => 'My submission',
        ]);

        $response = $this->actingAs($instructor)->post('/instructor/submissions/' . $submission->id . '/grade', [
            'score' => 85,
            'feedback' => 'Good work!',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('assignment_submissions', [
            'id' => $submission->id,
            'score' => 85,
            'feedback' => 'Good work!',
        ]);
    }

    public function test_instructor_can_view_students()
    {
        $instructor = $this->createUser('instructor');
        $student = $this->createUser('student');
        $course = $this->createCourse(['user_id' => $instructor->id]);
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 50,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($instructor)->get('/instructor/students');
        $response->assertStatus(200);
        $response->assertSee($student->email);
    }

    public function test_instructor_can_view_earnings()
    {
        $instructor = $this->createUser('instructor');
        $student = $this->createUser('student');
        $course = $this->createCourse(['user_id' => $instructor->id, 'payment_type' => 'paid', 'price' => 50]);
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 50,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($instructor)->get('/instructor/earnings');
        $response->assertStatus(200);
    }

    // ════════════════════════════════════════════════════════════════
    // PHASE 5: ORGANIZATION FLOWS
    // ════════════════════════════════════════════════════════════════

    public function test_organization_can_create_sub_instructor()
    {
        $org = $this->createUser('organization');

        $response = $this->actingAs($org)->post('/org/instructors', [
            'first_name' => 'Sub',
            'last_name' => 'Instructor',
            'email' => 'sub-instructor@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'designation' => 'Junior Dev',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'sub-instructor@test.com',
            'role' => 'instructor',
            'organization_id' => $org->id,
        ]);
    }

    public function test_organization_can_create_course()
    {
        $org = $this->createUser('organization');

        $response = $this->actingAs($org)->post('/org/courses', [
            'title' => 'Org Course Title',
            'description' => 'Course by organization',
            'payment_type' => 'free',
            'status' => 'Active',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('courses', [
            'title' => 'Org Course Title',
            'user_id' => $org->id,
        ]);
    }

    public function test_organization_can_view_instructors()
    {
        $org = $this->createUser('organization');
        $instructor = User::factory()->create([
            'role' => 'instructor',
            'organization_id' => $org->id,
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($org)->get('/org/instructors');
        $response->assertStatus(200);
        $response->assertSee($instructor->email);
    }

    public function test_organization_has_financial_reports()
    {
        $org = $this->createUser('organization');
        $student = $this->createUser('student');
        $course = $this->createCourse(['user_id' => $org->id]);
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 100,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($org)->get('/org/financial');
        $response->assertStatus(200);
    }

    public function test_organization_can_manage_noticeboard()
    {
        $org = $this->createUser('organization');

        $response = $this->actingAs($org)->post('/org/noticeboard', [
            'title' => 'Important Notice',
            'content' => 'Please read this notice.',
            'status' => 'active',
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('noticeboards', [
            'title' => 'Important Notice',
            'user_id' => $org->id,
        ]);
    }

    // ════════════════════════════════════════════════════════════════
    // PHASE 6: ADMIN FLOWS
    // ════════════════════════════════════════════════════════════════

    public function test_admin_dashboard_shows_stats()
    {
        $admin = $this->createUser('admin');
        User::factory()->count(3)->create(['role' => 'student', 'password' => Hash::make('p')]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('3');
    }

    public function test_admin_can_manage_categories()
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->post('/admin/category', [
            'name' => 'Web Development',
            'status' => 'active',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Web Development']);

        $category = Category::first();

        $response = $this->actingAs($admin)->post('/admin/category/' . $category->id, [
            'name' => 'Web Dev Updated',
            'status' => 'active',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Web Dev Updated']);

        $response = $this->actingAs($admin)->post('/admin/category/' . $category->id . '/delete');
        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_manage_levels()
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->post('/admin/course/level', [
            'name' => 'Advanced',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('levels', ['name' => 'Advanced']);
    }

    public function test_admin_can_manage_tags()
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->post('/admin/course/tag', [
            'name' => 'PHP',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('tags', ['name' => 'PHP']);
    }

    public function test_admin_can_view_all_users()
    {
        $admin = $this->createUser('admin');
        User::factory()->create(['role' => 'student', 'email' => 'stu1@test.com', 'password' => Hash::make('p')]);
        User::factory()->create(['role' => 'instructor', 'email' => 'inst1@test.com', 'password' => Hash::make('p')]);
        User::factory()->create(['role' => 'organization', 'email' => 'org1@test.com', 'password' => Hash::make('p')]);

        $this->actingAs($admin)->get('/admin/students')->assertStatus(200)->assertSee('stu1@test.com');
        $this->actingAs($admin)->get('/admin/instructors')->assertStatus(200)->assertSee('inst1@test.com');
        $this->actingAs($admin)->get('/admin/organizations')->assertStatus(200)->assertSee('org1@test.com');
    }

    public function test_admin_can_create_manual_enrollment()
    {
        $admin = $this->createUser('admin');
        $student = $this->createUser('student');
        $course = $this->createCourse();

        $response = $this->actingAs($admin)->post('/admin/enrollment', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_admin_can_create_certificate()
    {
        $admin = $this->createUser('admin');
        $student = $this->createUser('student');
        $course = $this->createCourse();

        $response = $this->actingAs($admin)->post('/admin/certificate', [
            'title' => 'Completion Certificate',
            'course_id' => $course->id,
            'user_id' => $student->id,
            'description' => 'Certificate of completion',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('certificates', [
            'title' => 'Completion Certificate',
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_admin_can_manage_blogs()
    {
        $admin = $this->createUser('admin');
        $category = BlogCategory::create(['name' => 'Tutorials', 'slug' => 'tutorials', 'status' => 'active']);

        $response = $this->actingAs($admin)->post('/admin/blog', [
            'title' => 'Test Blog Post',
            'content' => 'This is blog content.',
            'blog_category_id' => $category->id,
            'status' => 'published',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('blogs', ['title' => 'Test Blog Post']);

        $blog = Blog::first();

        $response = $this->actingAs($admin)->post('/admin/blog/' . $blog->id, [
            'title' => 'Updated Blog Post',
            'content' => 'Updated content.',
            'blog_category_id' => $category->id,
            'status' => 'draft',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('blogs', ['title' => 'Updated Blog Post']);

        $response = $this->actingAs($admin)->post('/admin/blog/' . $blog->id . '/delete');
        $response->assertRedirect();
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }

    public function test_admin_can_manage_cms_content()
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->post('/admin/faq', [
            'question' => 'What is LMS?',
            'answer' => 'Learning Management System',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('faqs', ['question' => 'What is LMS?']);

        $this->actingAs($admin)->post('/admin/slider', [
            'title' => 'Welcome Slider',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('sliders', ['title' => 'Welcome Slider']);

        $this->actingAs($admin)->post('/admin/testimonial', [
            'name' => 'Happy Student',
            'content' => 'Great platform!',
            'rating' => 5,
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('testimonials', ['name' => 'Happy Student']);

        $this->actingAs($admin)->post('/admin/hero', [
            'title' => 'Main Hero',
            'page' => 'home',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('hero_sections', ['title' => 'Main Hero']);

        $this->actingAs($admin)->post('/admin/page', [
            'title' => 'About Us',
            'content' => 'About page content.',
            'status' => 'published',
        ]);
        $this->assertDatabaseHas('pages', ['title' => 'About Us']);
    }

    public function test_admin_can_manage_coupons_and_payment_methods()
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->post('/admin/marketing/coupon', [
            'code' => 'SAVE20',
            'discount' => 20,
            'discount_type' => 'percentage',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('coupons', ['code' => 'SAVE20']);

        $this->actingAs($admin)->post('/admin/payment-method', [
            'name' => 'PayPal',
            'type' => 'Online',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('payment_methods', ['name' => 'PayPal']);
    }

    public function test_admin_can_manage_support_tickets()
    {
        $admin = $this->createUser('admin');
        $student = $this->createUser('student');
        $ticket = SupportTicket::create([
            'user_id' => $student->id,
            'subject' => 'Help',
            'message' => 'I need help',
            'category' => 'General',
            'status' => 'Open',
            'priority' => 'Medium',
        ]);

        $response = $this->actingAs($admin)->get('/admin/support-ticket/ticket');
        $response->assertStatus(200);
        $response->assertSee('Help');

        $response = $this->actingAs($admin)->post('/admin/support-ticket/ticket/' . $ticket->id, [
            'status' => 'Closed',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('support_tickets', ['id' => $ticket->id, 'status' => 'Closed']);
    }

    public function test_admin_can_manage_reviews()
    {
        $admin = $this->createUser('admin');
        $student = $this->createUser('student');
        $course = $this->createCourse();
        $review = Review::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'rating' => 4,
            'review' => 'Good course',
        ]);

        $this->actingAs($admin)->get('/admin/review/course-review')->assertStatus(200);

        $this->actingAs($admin)->post('/admin/review/' . $review->id . '/approve');
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'is_approved' => 1]);

        $this->actingAs($admin)->post('/admin/review/' . $review->id . '/delete');
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_admin_can_update_settings()
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->post('/admin/backend-setting', [
            'app_name' => 'My LMS',
            'email' => 'admin@lms.com',
            'timezone' => 'UTC',
        ])->assertRedirect()->assertSessionHas('success');

        $this->actingAs($admin)->post('/admin/theme-setting', [
            'primary_color' => '#FF5733',
            'site_name' => 'My LMS Site',
        ])->assertRedirect()->assertSessionHas('success');
    }

    public function test_admin_can_manage_bundles()
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->post('/admin/course/bundle', [
            'title' => 'Full Stack Bundle',
            'description' => 'Complete bundle',
            'price' => 199.99,
            'status' => 'active',
        ])->assertRedirect();

        $this->assertDatabaseHas('bundles', ['title' => 'Full Stack Bundle']);
    }

    public function test_admin_can_manage_notification_templates()
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->post('/admin/notification', [
            'type' => 'email',
            'template_name' => 'welcome_email',
            'subject' => 'Welcome!',
            'body' => 'Welcome!',
            'status' => 'active',
        ])->assertRedirect();

        $this->assertDatabaseHas('notification_templates', ['template_name' => 'welcome_email']);
    }

    public function test_admin_can_manage_subjects()
    {
        $admin = $this->createUser('admin');
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech', 'status' => 'active']);

        $this->actingAs($admin)->post('/admin/subject', [
            'name' => 'Mathematics',
            'category_id' => $category->id,
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('subjects', ['name' => 'Mathematics']);
    }

    public function test_admin_can_manage_blog_categories()
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->post('/admin/blog/category', [
            'name' => 'Tutorials',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('blog_categories', ['name' => 'Tutorials']);
    }

    // ════════════════════════════════════════════════════════════════
    // PHASE 7: SECURITY & EDGE CASES
    // ════════════════════════════════════════════════════════════════

    public function test_role_middleware_blocks_unauthorized_access()
    {
        $student = $this->createUser('student');
        $instructor = $this->createUser('instructor');

        $this->actingAs($student)->get('/instructor')->assertStatus(403);
        $this->actingAs($student)->get('/admin')->assertStatus(403);
        $this->actingAs($instructor)->get('/admin')->assertStatus(403);
        $this->actingAs($instructor)->get('/dashboard')->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login()
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/instructor')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_public_pages_render_successfully()
    {
        $this->get('/')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
        $this->get('/forgot-password')->assertStatus(200);
        $this->get('/courses')->assertStatus(200);
        $this->get('/instructors')->assertStatus(200);
        $this->get('/organizations')->assertStatus(200);
        $this->get('/about-us')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
        $this->get('/privacy-policy')->assertStatus(200);
        $this->get('/terms-conditions')->assertStatus(200);
        $this->get('/cart')->assertStatus(200);
    }

    public function test_student_dashboard_views_render()
    {
        $student = $this->createUser('student');
        $course = $this->createCourse();
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount_paid' => 0,
            'status' => 'in_progress',
        ]);

        $routes = [
            '/dashboard',
            '/dashboard/my-enrolled-course',
            '/dashboard/purchase-course',
            '/dashboard/assignments',
            '/dashboard/wishlists',
            '/dashboard/notifications',
            '/dashboard/profile',
            '/dashboard/settings',
        ];

        foreach ($routes as $route) {
            $this->actingAs($student)->get($route)->assertStatus(200);
        }
    }

    public function test_instructor_dashboard_views_render()
    {
        $instructor = $this->createUser('instructor');
        $this->createCourse(['user_id' => $instructor->id]);

        $routes = [
            '/instructor',
            '/instructor/courses',
            '/instructor/students',
            '/instructor/earnings',
            '/instructor/supports',
            '/instructor/notifications',
            '/instructor/settings',
        ];

        foreach ($routes as $route) {
            $this->actingAs($instructor)->get($route)->assertStatus(200);
        }
    }

    public function test_admin_dashboard_views_render()
    {
        $admin = $this->createUser('admin');

        $routes = [
            '/admin',
            '/admin/course',
            '/admin/instructors',
            '/admin/students',
            '/admin/organizations',
            '/admin/enrollment/all',
            '/admin/certificate',
            '/admin/contact',
            '/admin/review/course-review',
            '/admin/support-ticket/ticket',
            '/admin/notification',
            '/admin/notification/history',
            '/admin/profile',
            '/admin/backend-setting',
            '/admin/theme-setting',
            '/admin/faq',
            '/admin/category',
            '/admin/subject',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)->get($route)->assertStatus(200);
        }
    }

    public function test_instructor_cannot_edit_others_courses()
    {
        $instructor1 = $this->createUser('instructor');
        $instructor2 = $this->createUser('instructor');
        $course = $this->createCourse(['user_id' => $instructor1->id]);

        $this->actingAs($instructor2)
            ->get('/instructor/courses/edit/' . $course->id)
            ->assertStatus(404);

        $this->actingAs($instructor2)
            ->post('/instructor/courses/edit/' . $course->id, [
                'title' => 'Hacked Title',
                'description' => 'Hacked',
                'payment_type' => 'free',
                'status' => 'Active',
            ])
            ->assertStatus(404);
    }
}
