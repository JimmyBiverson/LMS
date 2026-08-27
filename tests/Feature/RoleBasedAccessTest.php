<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Faq;
use App\Models\HeroSection;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Level;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Tag;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $instructor;
    private User $org;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@edulab.test')->first();
        $this->instructor = User::where('email', 'instructor@edulab.test')->first();
        $this->org = User::where('email', 'org@edulab.test')->first();
        $this->student = User::where('email', 'student@edulab.test')->first();
    }

    // ─── Seed Data Verification ───────────────────────────────────────

    public function test_seed_data_was_created(): void
    {
        $this->assertNotNull($this->admin);
        $this->assertNotNull($this->instructor);
        $this->assertNotNull($this->org);
        $this->assertNotNull($this->student);

        $this->assertCount(4, Level::all());
        $this->assertCount(10, Tag::all());
        $this->assertCount(5, Category::all());
        $this->assertCount(4, Course::all());
        $this->assertGreaterThanOrEqual(3, Lesson::count());
        $this->assertGreaterThanOrEqual(4, Quiz::count());
        $this->assertGreaterThanOrEqual(3, QuizQuestion::count());
        $this->assertCount(4, Assignment::all());
        $this->assertCount(3, Enrollment::all());
        $this->assertGreaterThanOrEqual(4, Review::count());
        $this->assertCount(3, BlogCategory::all());
        $this->assertCount(5, Blog::all());
        $this->assertCount(2, Testimonial::all());
        $this->assertCount(1, HeroSection::all());
        $this->assertCount(1, Slider::all());
        $this->assertCount(3, Faq::all());
        $this->assertCount(2, Page::all());
        $this->assertCount(1, Coupon::all());
        $this->assertCount(3, PaymentMethod::all());
        $this->assertCount(2, Bundle::all());
    }

    // ─── Role Access: Dashboards ──────────────────────────────────────

    public function test_admin_can_access_admin_dashboard(): void
    {
        $this->actingAs($this->admin)->get('/admin')->assertStatus(200);
    }

    public function test_admin_is_blocked_from_other_dashboards(): void
    {
        $this->actingAs($this->admin)->get('/dashboard')->assertStatus(403);
        $this->actingAs($this->admin)->get('/instructor')->assertStatus(403);
        $this->actingAs($this->admin)->get('/org')->assertStatus(403);
    }

    public function test_instructor_can_access_instructor_dashboard(): void
    {
        $this->actingAs($this->instructor)->get('/instructor')->assertStatus(200);
    }

    public function test_instructor_is_blocked_from_other_dashboards(): void
    {
        $this->actingAs($this->instructor)->get('/dashboard')->assertStatus(403);
        $this->actingAs($this->instructor)->get('/admin')->assertStatus(403);
        $this->actingAs($this->instructor)->get('/org')->assertStatus(403);
    }

    public function test_org_can_access_org_dashboard(): void
    {
        $this->actingAs($this->org)->get('/org')->assertStatus(200);
    }

    public function test_org_is_blocked_from_other_dashboards(): void
    {
        $this->actingAs($this->org)->get('/dashboard')->assertStatus(403);
        $this->actingAs($this->org)->get('/admin')->assertStatus(403);
        $this->actingAs($this->org)->get('/instructor')->assertStatus(403);
    }

    public function test_student_can_access_student_dashboard(): void
    {
        $this->actingAs($this->student)->get('/dashboard')->assertStatus(200);
    }

    public function test_student_is_blocked_from_other_dashboards(): void
    {
        $this->actingAs($this->student)->get('/instructor')->assertStatus(403);
        $this->actingAs($this->student)->get('/admin')->assertStatus(403);
        $this->actingAs($this->student)->get('/org')->assertStatus(403);
    }

    public function test_zoom_dashboard_does_not_cache_model_collections(): void
    {
        $this->actingAs($this->student)->get('/dashboard/zoom')->assertOk();

        $this->assertFalse(Cache::has('zoom.user.' . $this->student->id . '.upcoming'));
    }

    public function test_guest_cannot_access_any_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/instructor')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/org')->assertRedirect('/login');
    }

    // ─── Instructor Operations ─────────────────────────────────────────

    public function test_instructor_can_create_course(): void
    {
        $response = $this->actingAs($this->instructor)->post('/instructor/courses', [
            'title' => 'New Test Course',
            'description' => 'A brand new course created by the instructor',
            'category' => 'Web Development',
            'payment_type' => 'free',
            'price' => 0,
            'status' => 'Active',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('courses', ['title' => 'New Test Course', 'user_id' => $this->instructor->id]);
    }

    public function test_instructor_can_add_lesson(): void
    {
        $course = Course::where('user_id', $this->instructor->id)->first();
        $this->assertNotNull($course);

        $response = $this->actingAs($this->instructor)->post("/instructor/courses/{$course->id}/lessons", [
            'title' => 'New Lesson from Instructor',
            'content' => 'Lesson content here',
            'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
            'duration' => '10:00',
            'status' => 'published',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('lessons', ['course_id' => $course->id, 'title' => 'New Lesson from Instructor']);
    }

    public function test_instructor_can_create_quiz(): void
    {
        $course = Course::where('user_id', $this->instructor->id)->first();
        $this->assertNotNull($course);

        $response = $this->actingAs($this->instructor)->post("/instructor/courses/{$course->id}/quizzes", [
            'title' => 'Instructor Created Quiz',
            'passing_score' => 60,
            'time_limit' => 15,
            'status' => 'published',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('quizzes', ['course_id' => $course->id, 'title' => 'Instructor Created Quiz']);
    }

    public function test_instructor_can_manage_assignments(): void
    {
        $course = Course::where('user_id', $this->instructor->id)->first();

        $response = $this->actingAs($this->instructor)->post("/instructor/courses/{$course->id}/assignments", [
            'title' => 'Instructor Assignment',
            'description' => 'Complete this assignment',
            'instructions' => 'Follow the steps',
            'total_marks' => 100,
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'published',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('assignments', ['course_id' => $course->id, 'title' => 'Instructor Assignment']);
    }

    public function test_instructor_can_view_students(): void
    {
        $this->actingAs($this->instructor)->get('/instructor/students')->assertStatus(200);
    }

    public function test_instructor_can_view_earnings(): void
    {
        $this->actingAs($this->instructor)->get('/instructor/earnings')->assertStatus(200);
    }

    // ─── Student Operations ────────────────────────────────────────────

    public function test_student_can_view_enrolled_courses(): void
    {
        $this->actingAs($this->student)->get('/dashboard/my-enrolled-course')->assertStatus(200);
    }

    public function test_student_can_take_quiz(): void
    {
        $course = Course::where('title', 'Introduction to Web Development')->first();
        $enrollment = Enrollment::where('user_id', $this->student->id)->where('course_id', $course->id)->first();
        $this->assertNotNull($enrollment);

        $quiz = Quiz::where('course_id', $course->id)->first();
        $this->assertNotNull($quiz);

        $this->actingAs($this->student)->get("/dashboard/quizzes/{$quiz->id}/take")->assertStatus(200);
    }

    public function test_student_can_submit_quiz(): void
    {
        $course = Course::where('title', 'Introduction to Web Development')->first();
        $quiz = Quiz::where('course_id', $course->id)->first();
        $question = QuizQuestion::where('quiz_id', $quiz->id)->first();

        $response = $this->actingAs($this->student)->post("/dashboard/quizzes/{$quiz->id}/submit", [
            'answers' => [$question->id => $question->correct_answer],
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('quiz_results', [
            'user_id' => $this->student->id,
            'quiz_id' => $quiz->id,
        ]);
    }

    public function test_student_can_submit_assignment(): void
    {
        $course = Course::where('title', 'Introduction to Web Development')->first();
        $assignment = Assignment::where('course_id', $course->id)->first();
        $this->assertNotNull($assignment);

        $response = $this->actingAs($this->student)->post("/dashboard/assignments/{$assignment->id}/submit", [
            'submission_text' => 'This is my assignment submission.',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('assignment_submissions', [
            'user_id' => $this->student->id,
            'assignment_id' => $assignment->id,
        ]);
    }

    public function test_student_can_write_review(): void
    {
        $course = Course::where('title', 'Python for Data Science')->first();
        Enrollment::updateOrCreate(
            ['user_id' => $this->student->id, 'course_id' => $course->id],
            ['amount_paid' => 0, 'status' => 'completed', 'completed_at' => now()]
        );

        $response = $this->actingAs($this->student)->post("/dashboard/course-review/{$course->id}", [
            'rating' => 5,
            'review' => 'Great course!',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->student->id,
            'course_id' => $course->id,
        ]);
    }

    // ─── Organization Operations ───────────────────────────────────────

    public function test_org_can_create_course(): void
    {
        $response = $this->actingAs($this->org)->post('/org/courses', [
            'title' => 'Org Created Course',
            'description' => 'Created by organization',
            'category' => 'Business',
            'payment_type' => 'paid',
            'price' => 99.99,
            'status' => 'Active',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('courses', ['title' => 'Org Created Course', 'user_id' => $this->org->id]);
    }

    public function test_org_can_create_instructor(): void
    {
        $response = $this->actingAs($this->org)->post('/org/instructors', [
            'first_name' => 'New',
            'last_name' => 'Instructor',
            'name' => 'New Instructor',
            'email' => 'new-instructor@edulab.test',
            'phone' => '+1234567899',
            'password' => 'password',
            'password_confirmation' => 'password',
            'designation' => 'Junior Developer',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'new-instructor@edulab.test',
            'role' => 'instructor',
            'organization_id' => $this->org->id,
        ]);
    }

    public function test_org_can_view_financials(): void
    {
        $this->actingAs($this->org)->get('/org/financial')->assertStatus(200);
    }

    public function test_org_can_view_students(): void
    {
        $this->actingAs($this->org)->get('/org/students')->assertStatus(200);
    }

    // ─── Admin Operations ──────────────────────────────────────────────

    public function test_admin_can_manage_blog_categories(): void
    {
        $this->actingAs($this->admin)->get('/admin/blog/category')->assertStatus(200);

        $this->actingAs($this->admin)->post('/admin/blog/category', [
            'name' => 'New Category',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('blog_categories', ['name' => 'New Category']);

        $category = BlogCategory::where('name', 'New Category')->first();
        $this->actingAs($this->admin)->post("/admin/blog/category/{$category->id}", [
            'name' => 'Updated Category',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('blog_categories', ['name' => 'Updated Category']);
    }

    public function test_admin_can_manage_categories(): void
    {
        $this->actingAs($this->admin)->post('/admin/category', [
            'name' => 'Cloud Computing',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('categories', ['name' => 'Cloud Computing']);
    }

    public function test_admin_can_manage_levels(): void
    {
        $this->actingAs($this->admin)->get('/admin/course/level')->assertStatus(200);

        $this->actingAs($this->admin)->post('/admin/course/level', [
            'name' => 'Master',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('levels', ['name' => 'Master']);
    }

    public function test_admin_can_manage_tags(): void
    {
        $this->actingAs($this->admin)->get('/admin/course/tag')->assertStatus(200);

        $this->actingAs($this->admin)->post('/admin/course/tag', [
            'name' => 'Rust',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('tags', ['name' => 'Rust']);
    }

    public function test_admin_can_manage_blogs(): void
    {
        $this->actingAs($this->admin)->get('/admin/blog')->assertStatus(200);
    }

    public function test_admin_can_manage_users(): void
    {
        $this->actingAs($this->admin)->get('/admin/instructors')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/students')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/organizations')->assertStatus(200);
    }

    public function test_admin_can_manage_certificates(): void
    {
        $this->actingAs($this->admin)->get('/admin/certificate')->assertStatus(200);
    }

    public function test_admin_can_manage_coupons(): void
    {
        $this->actingAs($this->admin)->get('/admin/marketing/coupon')->assertStatus(200);
    }

    public function test_admin_can_manage_payment_methods(): void
    {
        $this->actingAs($this->admin)->get('/admin/payment-method')->assertStatus(200);
    }

    public function test_admin_can_manage_testimonials(): void
    {
        $this->actingAs($this->admin)->get('/admin/testimonial')->assertStatus(200);
    }

    public function test_admin_can_manage_faqs(): void
    {
        $this->actingAs($this->admin)->get('/admin/faq')->assertStatus(200);
    }

    public function test_admin_can_manage_pages(): void
    {
        $this->actingAs($this->admin)->get('/admin/page')->assertStatus(200);
    }

    // ─── Public Pages ──────────────────────────────────────────────────

    public function test_public_pages_render_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
        $this->get('/courses')->assertStatus(200);
        $this->get('/instructors')->assertStatus(200);
        $this->get('/about-us')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
        $this->get('/cart')->assertStatus(200);
        $this->get('/search?q=test')->assertStatus(200);
        $this->get('/blogs')->assertStatus(200);
        $this->get('/privacy-policy')->assertStatus(200);
    }

    public function test_course_detail_page_works(): void
    {
        $course = Course::first();
        $this->get("/courses/{$course->slug}")->assertStatus(200);
    }

    public function test_blog_posts_are_accessible(): void
    {
        $blog = Blog::first();
        $this->get("/blogs/{$blog->slug}")->assertStatus(200);
    }
}
