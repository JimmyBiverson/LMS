<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCrudController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Organization\CourseController as OrgCourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SupportTicketController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    $courses = \App\Models\Course::with('lessons')->where('status', 'Active')->latest()->take(8)->get();
    $categories = \App\Models\Category::withCount('courses')->where('status', 'active')->latest()->take(4)->get();
    $testimonials = \App\Models\Testimonial::where('status', 'active')->latest()->take(3)->get();
    return view('home', compact('courses', 'categories', 'testimonials'));
});
Route::get('/courses', function () {
    $query = \App\Models\Course::with('lessons')->where('status', 'Active');
    if (request('type') === 'free') $query->where('payment_type', 'free');
    if (request('type') === 'paid') $query->where('payment_type', 'paid');
    $courses = $query->latest()->get();
    return view('courses.index', compact('courses'));
});
Route::get('/courses/{course}', function ($course) {
    $course = \App\Models\Course::with('lessons', 'instructor')->where('status', 'Active')->findOrFail($course);
    return view('courses.show', compact('course'));
});
Route::get('/instructors', fn() => view('instructors.index'));
Route::get('/organizations', fn() => view('organizations.index'));
Route::get('/blogs', function () {
    $blogs = \App\Models\Blog::with('category', 'author')->where('status', 'published')->latest()->get();
    return view('blogs.index', compact('blogs'));
});
Route::get('/blogs/{slug}', function ($slug) {
    $blog = \App\Models\Blog::with('category', 'author')->where('slug', $slug)->where('status', 'published')->firstOrFail();
    return view('blogs.show', compact('blog'));
});
Route::get('/bundles', fn() => view('bundles.index'));
Route::get('/bundles/{slug}', fn() => view('bundles.show'));
Route::get('/about-us', fn() => view('about'));
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::post('/contact', [ContactController::class, 'send']);
Route::post('/newsletter', function (\Illuminate\Http\Request $r) {
    $r->validate(['email' => 'required|email']);
    logger('Newsletter subscription: ' . $r->email);
    return back()->with('success', 'Subscribed to newsletter successfully!');
});
Route::post('/become-instructor', [AuthController::class, 'becomeInstructor'])->middleware('guest');
Route::get('/cart', fn() => view('cart'));
Route::get('/privacy-policy', fn() => view('privacy-policy'));
Route::get('/terms-conditions', fn() => view('terms-conditions'));
Route::get('/categories', fn() => view('categories'));

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', fn() => view('forgot-password'))->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Checkout & Enrollment
    Route::get('/courses/{course}/checkout', function ($courseId) {
        $course = \App\Models\Course::where('status', 'Active')->findOrFail($courseId);
        $isEnrolled = \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)->exists();
        return view('courses.checkout', compact('course', 'isEnrolled'));
    });
    Route::post('/enroll/{courseId}', function ($courseId) {
        $course = \App\Models\Course::where('status', 'Active')->findOrFail($courseId);
        $isEnrolled = \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)->exists();
        if ($isEnrolled) {
            return redirect('/courses/' . $course->id)->with('info', 'You are already enrolled in this course.');
        }
        $amountPaid = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);
        \App\Models\Enrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'amount_paid' => $amountPaid,
            'status' => 'in_progress',
        ]);
        return redirect('/courses/' . $course->id)->with('success', 'Enrolled successfully!');
    });

    // User Profile
    Route::get('/profile', fn() => view('users.profile'))->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/contact', [ContactController::class, 'instructorContact']);
    Route::get('/organization', fn() => view('users.organization'));

    // Student Dashboard
    Route::middleware('role:' . User::ROLE_STUDENT)->prefix('dashboard')->name('dashboard')->group(function () {
        Route::get('/', function () {
            $enrollments = \App\Models\Enrollment::with('course.instructor')->where('user_id', auth()->id())->latest()->get();
            $totalEnrolled = $enrollments->count();
            $inProgress = $enrollments->where('status', 'in_progress')->count();
            $completed = $enrollments->where('status', 'completed')->count();
            $certificateCount = \App\Models\Certificate::where('user_id', auth()->id())->count();
            $latest = $enrollments->take(4);
            return view('dashboard.index', compact('enrollments', 'totalEnrolled', 'inProgress', 'completed', 'certificateCount', 'latest'));
        });
        Route::get('/my-enrolled-course', function () {
            $enrollments = \App\Models\Enrollment::with('course.instructor')->where('user_id', auth()->id())->latest()->get();
            return view('dashboard.my-enrolled-course', compact('enrollments'));
        });
        Route::get('/purchase-course', fn() => view('dashboard.purchase-course'));
        Route::get('/bundle-course', fn() => view('dashboard.bundle-course'));
        Route::get('/certificate', function () {
            $certificates = \App\Models\Certificate::with('course')
                ->where('user_id', auth()->id())->latest()->get();
            return view('dashboard.certificate', compact('certificates'));
        });
        Route::get('/quizzes/my-result', [QuizController::class, 'myResults']);
        Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take']);
        Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit']);
        Route::get('/assignments/{assignment}/submit', [AssignmentController::class, 'submitForm']);
        Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
        Route::get('/assignments', function () {
            $submissions = \App\Models\AssignmentSubmission::with('assignment.course')
                ->where('user_id', auth()->id())->latest()->get();
            return view('dashboard.assignments', compact('submissions'));
        });
        Route::get('/course-review', fn() => view('dashboard.course-review'));
        Route::get('/offline-payment', fn() => view('dashboard.offline-payment'));
        Route::get('/supports/create', fn() => view('dashboard.supports.create'));
        Route::post('/supports', [SupportTicketController::class, 'store']);
        Route::get('/supports', fn() => view('dashboard.supports.index'));
        Route::get('/course-support', fn() => view('dashboard.course-support'));
        Route::get('/notifications', fn() => view('dashboard.notifications'));
        Route::get('/wishlists', fn() => view('dashboard.wishlists'));
        Route::get('/profile', fn() => view('dashboard.profile'));
        Route::get('/settings', fn() => view('dashboard.settings'));
        Route::post('/settings', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            auth()->user()->update(['password' => bcrypt($request->password)]);
            return back()->with('status', 'Password changed successfully!');
        });
    });

    // Instructor Dashboard
    Route::middleware('role:' . User::ROLE_INSTRUCTOR)->prefix('instructor')->name('instructor.dashboard.')->group(function () {
        Route::get('/', function () {
            $courses = \App\Models\Course::withCount('enrollments')->where('user_id', auth()->id())->latest()->get();
            $totalStudents = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))->count();
            return view('instructor.index', compact('courses', 'totalStudents'));
        })->name('dashboard');
        Route::get('/courses', [InstructorCourseController::class, 'index']);
        Route::get('/courses/create', [InstructorCourseController::class, 'create']);
        Route::post('/courses', [InstructorCourseController::class, 'store']);
        Route::get('/courses/edit/{id}', [InstructorCourseController::class, 'edit'])->name('courses.edit');
        Route::post('/courses/edit/{id}', [InstructorCourseController::class, 'update']);
        Route::get('/courses/{id}/lessons', [InstructorCourseController::class, 'lessons'])->name('courses.lessons');
        Route::post('/courses/{id}/lessons', [InstructorCourseController::class, 'storeLesson']);
        Route::post('/courses/{courseId}/lessons/{lessonId}/delete', [InstructorCourseController::class, 'destroyLesson'])->name('courses.lessons.delete');
        Route::get('/courses/{course}/quizzes', [QuizController::class, 'index'])->name('courses.quizzes');
        Route::get('/courses/{course}/quizzes/create', [QuizController::class, 'create']);
        Route::post('/courses/{course}/quizzes', [QuizController::class, 'store']);
        Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::post('/quizzes/{quiz}', [QuizController::class, 'update']);
        Route::post('/quizzes/{quiz}/delete', [QuizController::class, 'destroy']);
        Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion']);
        Route::post('/quizzes/questions/{question}/delete', [QuizController::class, 'destroyQuestion']);
        Route::get('/courses/{course}/assignments', [AssignmentController::class, 'index'])->name('courses.assignments');
        Route::get('/courses/{course}/assignments/create', [AssignmentController::class, 'create']);
        Route::post('/courses/{course}/assignments', [AssignmentController::class, 'store']);
        Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
        Route::post('/assignments/{assignment}', [AssignmentController::class, 'update']);
        Route::post('/assignments/{assignment}/delete', [AssignmentController::class, 'destroy']);
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/submissions/{submission}/grade', [AssignmentController::class, 'grade']);
        Route::get('/earnings', fn() => view('instructor.earnings'));
        Route::get('/students', fn() => view('instructor.students'));
        Route::get('/reviews', fn() => view('instructor.reviews'));
        Route::get('/quiz', fn() => view('instructor.quiz'));
        Route::get('/supports', fn() => view('instructor.supports'));
        Route::get('/notifications', fn() => view('instructor.notifications'));
        Route::get('/settings', fn() => view('instructor.settings'));
        Route::post('/settings', [AuthController::class, 'updateProfile']);
        Route::get('/assignments', fn() => view('instructor.assignments'));
    });

    // Organization Dashboard
    Route::middleware('role:' . User::ROLE_ORGANIZATION)->prefix('org')->name('org.dashboard.')->group(function () {
        Route::get('/', function () {
            $courses = \App\Models\Course::withCount('enrollments')->where('user_id', auth()->id())->latest()->get();
            $totalStudents = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))->count();
            return view('org.index', compact('courses', 'totalStudents'));
        })->name('dashboard');
        Route::get('/courses', [OrgCourseController::class, 'index']);
        Route::get('/courses/create', [OrgCourseController::class, 'create']);
        Route::post('/courses', [OrgCourseController::class, 'store']);
        Route::get('/courses/edit/{id}', [OrgCourseController::class, 'edit'])->name('courses.edit');
        Route::post('/courses/edit/{id}', [OrgCourseController::class, 'update']);
        Route::get('/courses/{id}/lessons', [OrgCourseController::class, 'lessons'])->name('courses.lessons');
        Route::post('/courses/{id}/lessons', [OrgCourseController::class, 'storeLesson']);
        Route::post('/courses/{courseId}/lessons/{lessonId}/delete', [OrgCourseController::class, 'destroyLesson'])->name('courses.lessons.delete');
        Route::get('/instructors', fn() => view('org.instructors'));
        Route::get('/instructors/create', fn() => view('org.instructors-create'))->name('instructors.create');
        Route::post('/instructors', [OrgCourseController::class, 'storeInstructor']);
        Route::get('/students', fn() => view('org.students'));
        Route::get('/financial', fn() => view('org.financial'));
        Route::get('/reviews', fn() => view('org.reviews'));
        Route::get('/supports', fn() => view('org.supports'));
        Route::get('/noticeboard', fn() => view('org.noticeboard'));
        Route::get('/notifications', fn() => view('org.notifications'));
        Route::get('/wishlists', fn() => view('org.wishlists'));
        Route::get('/settings', fn() => view('org.settings'));
        Route::post('/settings', [AuthController::class, 'updateProfile']);
        Route::get('/profile', fn() => view('org.profile'));
    });

    // Admin Dashboard
    Route::middleware('role:' . User::ROLE_ADMIN)->prefix('admin')->name('admin.dashboard.')->group(function () {
        Route::get('/', function () {
            $totalStudents = \App\Models\User::where('role', 'student')->count();
            $totalCourses = \App\Models\Course::count();
            $totalInstructors = \App\Models\User::where('role', 'instructor')->count();
            $totalEnrollments = \App\Models\Enrollment::count();
            return view('admin.index', compact('totalStudents', 'totalCourses', 'totalInstructors', 'totalEnrollments'));
        })->name('dashboard');
        Route::get('/course', function () {
            $courses = \App\Models\Course::with('instructor')->withCount('enrollments')->latest()->get();
            return view('admin.course.index', compact('courses'));
        });
        Route::get('/course/bundle', fn() => view('admin.course.bundle'));
        Route::get('/course/level', fn() => view('admin.course.level'));
        Route::get('/course/tag', fn() => view('admin.course.tag'));
            Route::get('/category', [AdminCrudController::class, 'categories']);
        Route::post('/category', [AdminCrudController::class, 'storeCategory']);
        Route::post('/category/{category}', [AdminCrudController::class, 'updateCategory']);
        Route::post('/category/{category}/delete', [AdminCrudController::class, 'destroyCategory']);
        Route::get('/subject', [AdminCrudController::class, 'subjects']);
        Route::post('/subject', [AdminCrudController::class, 'storeSubject']);
        Route::post('/subject/{subject}', [AdminCrudController::class, 'updateSubject']);
        Route::post('/subject/{subject}/delete', [AdminCrudController::class, 'destroySubject']);
        Route::get('/instructors', [AdminCrudController::class, 'instructors']);
        Route::get('/students', [AdminCrudController::class, 'students']);
        Route::get('/organizations', [AdminCrudController::class, 'organizations']);
        Route::get('/staff', fn() => view('admin.staff'));
        Route::get('/blog', [AdminCrudController::class, 'blogs']);
        Route::post('/blog', [AdminCrudController::class, 'storeBlog']);
        Route::post('/blog/{blog}', [AdminCrudController::class, 'updateBlog']);
        Route::post('/blog/{blog}/delete', [AdminCrudController::class, 'destroyBlog']);
        Route::get('/blog/category', [AdminCrudController::class, 'blogCategories']);
        Route::post('/blog/category', [AdminCrudController::class, 'storeBlogCategory']);
        Route::post('/blog/category/{blogCategory}', [AdminCrudController::class, 'updateBlogCategory']);
        Route::post('/blog/category/{blogCategory}/delete', [AdminCrudController::class, 'destroyBlogCategory']);
        Route::get('/faq', [AdminCrudController::class, 'faqs']);
        Route::post('/faq', [AdminCrudController::class, 'storeFaq']);
        Route::post('/faq/{faq}', [AdminCrudController::class, 'updateFaq']);
        Route::post('/faq/{faq}/delete', [AdminCrudController::class, 'destroyFaq']);
        Route::get('/page', [AdminCrudController::class, 'pages']);
        Route::post('/page', [AdminCrudController::class, 'storePage']);
        Route::post('/page/{page}', [AdminCrudController::class, 'updatePage']);
        Route::post('/page/{page}/delete', [AdminCrudController::class, 'destroyPage']);
        Route::get('/slider', [AdminCrudController::class, 'sliders']);
        Route::post('/slider', [AdminCrudController::class, 'storeSlider']);
        Route::post('/slider/{slider}', [AdminCrudController::class, 'updateSlider']);
        Route::post('/slider/{slider}/delete', [AdminCrudController::class, 'destroySlider']);
        Route::get('/hero', [AdminCrudController::class, 'heros']);
        Route::post('/hero', [AdminCrudController::class, 'storeHero']);
        Route::post('/hero/{heroSection}', [AdminCrudController::class, 'updateHero']);
        Route::post('/hero/{heroSection}/delete', [AdminCrudController::class, 'destroyHero']);
        Route::get('/testimonial', [AdminCrudController::class, 'testimonials']);
        Route::post('/testimonial', [AdminCrudController::class, 'storeTestimonial']);
        Route::post('/testimonial/{testimonial}', [AdminCrudController::class, 'updateTestimonial']);
        Route::post('/testimonial/{testimonial}/delete', [AdminCrudController::class, 'destroyTestimonial']);
        Route::get('/contact', [AdminCrudController::class, 'contactMessages']);
        Route::post('/contact/{contactMessage}/read', [AdminCrudController::class, 'markAsRead']);
        Route::get('/payment-method', fn() => view('admin.payment-method'));
        Route::get('/financial/sale', fn() => view('admin.financial.sale'));
        Route::get('/financial/offline', fn() => view('admin.financial.offline'));
        Route::get('/financial/payout-request', fn() => view('admin.financial.payout-request'));
        Route::get('/certificate', [AdminCrudController::class, 'certificates']);
        Route::get('/certificate/create', [AdminController::class, 'certificateCreate'])->name('certificate.create');
        Route::post('/certificate', [AdminController::class, 'storeCertificate'])->name('certificate.store');
        Route::get('/enrollment/all', [AdminCrudController::class, 'allEnrollments']);
        Route::get('/enrollment/new-create', [AdminController::class, 'newEnrollment'])->name('enrollment.new-create');
        Route::post('/enrollment', [AdminController::class, 'storeEnrollment']);
        Route::get('/marketing/coupon', fn() => view('admin.marketing.coupon'));
        Route::get('/review/course-review', fn() => view('admin.review.course-review'));
        Route::get('/notification', fn() => view('admin.notification.index'));
        Route::get('/notification/history', fn() => view('admin.notification.history'));
        Route::get('/support-ticket/category', fn() => view('admin.support-ticket.category'));
        Route::get('/support-ticket/ticket', fn() => view('admin.support-ticket.ticket'));
        Route::get('/meet-provider', fn() => view('admin.meet-provider'));
        Route::get('/lms-module/subscription', fn() => view('admin.lms-module.subscription'));
        Route::get('/theme-setting', fn() => view('admin.theme-setting'));
        Route::post('/theme-setting', [AdminController::class, 'updateThemeSetting']);
        Route::get('/site-language', fn() => view('admin.site-language'));
        Route::get('/language', fn() => view('admin.language'));
        Route::get('/theme', fn() => view('admin.theme'));
        Route::get('/currency', fn() => view('admin.currency'));
        Route::get('/email-template', fn() => view('admin.email-template'));
        Route::get('/backend-setting', fn() => view('admin.backend-setting'));
        Route::post('/backend-setting', [AdminController::class, 'updateBackendSetting']);
        Route::get('/profile', fn() => view('admin.profile'));
        Route::post('/profile', [AuthController::class, 'updateProfile']);
        Route::get('/localization/country', fn() => view('admin.localization.country'));
        Route::get('/localization/state', fn() => view('admin.localization.state'));
        Route::get('/localization/city', fn() => view('admin.localization.city'));
        Route::get('/localization/time-zone', fn() => view('admin.localization.time-zone'));
        Route::get('/icon-providers/icon', fn() => view('admin.icon-providers.icon'));
        Route::get('/noticeboard', fn() => view('admin.noticeboard'));
    });
});
