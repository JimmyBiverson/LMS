<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Organization\CourseController as OrgCourseController;
use App\Http\Controllers\SupportTicketController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    $courses = \App\Models\Course::with('lessons')->where('status', 'Active')->latest()->take(8)->get();
    return view('home', compact('courses'));
});
Route::get('/courses', function () {
    $courses = \App\Models\Course::with('lessons')->where('status', 'Active')->latest()->get();
    return view('courses.index', compact('courses'));
});
Route::get('/courses/{slug}', function ($slug) {
    $course = \App\Models\Course::with('lessons', 'instructor')->where('status', 'Active')->findOrFail($slug);
    return view('courses.show', compact('course'));
});
Route::get('/instructors', fn() => view('instructors.index'));
Route::get('/organizations', fn() => view('organizations.index'));
Route::get('/blogs', fn() => view('blogs.index'));
Route::get('/blogs/{slug}', fn() => view('blogs.show'));
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
Route::post('/become-instructor', [AuthController::class, 'becomeInstructor']);
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

    // Enrollment
    Route::post('/enroll/{courseId}', function ($courseId) {
        $course = \App\Models\Course::findOrFail($courseId);
        \App\Models\Enrollment::firstOrCreate([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
        ], ['amount_paid' => $course->price]);
        return redirect('/courses/' . $course->id)->with('success', 'Enrolled successfully!');
    });

    // User Profile
    Route::get('/profile', fn() => view('users.profile'))->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/contact', [ContactController::class, 'instructorContact']);
    Route::get('/organization', fn() => view('users.organization'));

    // Student Dashboard
    Route::middleware('role:' . User::ROLE_STUDENT)->prefix('dashboard')->name('dashboard')->group(function () {
        Route::get('/', fn() => view('dashboard.index'));
        Route::get('/my-enrolled-course', fn() => view('dashboard.my-enrolled-course'));
        Route::get('/purchase-course', fn() => view('dashboard.purchase-course'));
        Route::get('/bundle-course', fn() => view('dashboard.bundle-course'));
        Route::get('/certificate', fn() => view('dashboard.certificate'));
        Route::get('/quizzes/my-result', fn() => view('dashboard.quizzes.my-result'));
        Route::get('/assignments', fn() => view('dashboard.assignments'));
        Route::get('/course-review', fn() => view('dashboard.course-review'));
        Route::get('/offline-payment', fn() => view('dashboard.offline-payment'));
        Route::get('/supports/create', fn() => view('dashboard.supports.create'));
        Route::post('/supports', [SupportTicketController::class, 'store']);
        Route::get('/supports', fn() => view('dashboard.supports.index'));
        Route::get('/course-support', fn() => view('dashboard.course-support'));
        Route::get('/notifications', fn() => view('dashboard.notifications'));
        Route::get('/wishlists', fn() => view('dashboard.wishlists'));
    });

    // Instructor Dashboard
    Route::middleware('role:' . User::ROLE_INSTRUCTOR)->prefix('instructor')->name('instructor.dashboard.')->group(function () {
        Route::get('/', fn() => view('instructor.index'));
        Route::get('/courses', [InstructorCourseController::class, 'index']);
        Route::match(['GET', 'POST'], '/courses/create', [InstructorCourseController::class, 'create']);
        Route::post('/courses', [InstructorCourseController::class, 'store']);
        Route::get('/courses/edit/{id}', [InstructorCourseController::class, 'edit'])->name('courses.edit');
        Route::post('/courses/edit/{id}', [InstructorCourseController::class, 'update']);
        Route::get('/courses/{id}/lessons', [InstructorCourseController::class, 'lessons'])->name('courses.lessons');
        Route::post('/courses/{id}/lessons', [InstructorCourseController::class, 'storeLesson']);
        Route::post('/courses/{courseId}/lessons/{lessonId}/delete', [InstructorCourseController::class, 'destroyLesson'])->name('courses.lessons.delete');
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
        Route::get('/', fn() => view('org.index'));
        Route::get('/courses', [OrgCourseController::class, 'index']);
        Route::match(['GET', 'POST'], '/courses/create', [OrgCourseController::class, 'create']);
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
        Route::get('/', fn() => view('admin.index'));
        Route::get('/course', fn() => view('admin.course.index'));
        Route::get('/course/bundle', fn() => view('admin.course.bundle'));
        Route::get('/course/level', fn() => view('admin.course.level'));
        Route::get('/course/tag', fn() => view('admin.course.tag'));
        Route::get('/category', fn() => view('admin.category'));
        Route::get('/subject', fn() => view('admin.subject'));
        Route::get('/instructors', fn() => view('admin.instructors'));
        Route::get('/students', fn() => view('admin.students'));
        Route::get('/organizations', fn() => view('admin.organizations'));
        Route::get('/staff', fn() => view('admin.staff'));
        Route::get('/blog', fn() => view('admin.blog.index'));
        Route::get('/blog/category', fn() => view('admin.blog.category'));
        Route::get('/faq', fn() => view('admin.faq'));
        Route::get('/page', fn() => view('admin.page'));
        Route::get('/slider', fn() => view('admin.slider'));
        Route::get('/hero', fn() => view('admin.hero'));
        Route::get('/testimonial', fn() => view('admin.testimonial'));
        Route::get('/contact', fn() => view('admin.contact'));
        Route::get('/payment-method', fn() => view('admin.payment-method'));
        Route::get('/financial/sale', fn() => view('admin.financial.sale'));
        Route::get('/financial/offline', fn() => view('admin.financial.offline'));
        Route::get('/financial/payout-request', fn() => view('admin.financial.payout-request'));
        Route::get('/certificate/create', [AdminController::class, 'certificateCreate'])->name('certificate.create');
        Route::post('/certificate', [AdminController::class, 'storeCertificate']);
        Route::get('/enrollment/all', fn() => view('admin.enrollment.all'));
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
