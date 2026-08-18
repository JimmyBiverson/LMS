<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCrudController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NoticeboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Dashboard\CourseNoteController as DashboardCourseNoteController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\CourseNoteController as InstructorCourseNoteController;
use App\Http\Controllers\MeetProviderController;
use App\Http\Controllers\Organization\CourseController as OrgCourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportTicketCategoryController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SchoolManagementController;
use App\Http\Controllers\WishlistController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', \App\Http\Controllers\HomeController::class);
Route::get('/courses', function () {
    $query = \App\Models\Course::with('lessons', 'level')->withCount(['enrollments', 'quizzes', 'assignments'])->where('status', 'Active');

    if (request('type') === 'free') $query->where('payment_type', 'free');
    if (request('type') === 'paid') $query->where('payment_type', 'paid');

    if (request('categories')) {
        $query->where('category_id', request('categories'));
    }

    if (request('level')) {
        $query->where('level_id', request('level'));
    }

    if (request('tag')) {
        $query->whereHas('tags', function ($q) {
            $q->where('tags.id', request('tag'));
        });
    }

    $sort = request('sort', 'newest');
    match ($sort) {
        'popular' => $query->orderBy('enrollments_count', 'desc'),
        'oldest' => $query->oldest(),
        'price-low' => $query->where('payment_type', 'paid')->orderBy('price')->orderBy('sale_price'),
        'price-high' => $query->where('payment_type', 'paid')->orderBy('price', 'desc')->orderBy('sale_price', 'desc'),
        default => $query->latest(),
    };

    $courses = $query->paginate(12)->withQueryString();

    $categories = \App\Models\Category::where('status', 'active')->withCount('courses')->get();
    $levels = \App\Models\Level::orderBy('order')->get();
    $tags = \App\Models\Tag::orderBy('name')->get();

    return view('courses.index', compact('courses', 'categories', 'levels', 'tags'));
});
Route::get('/users/{id}/profile', function ($id) {
    $instructor = \App\Models\User::where('id', $id)->whereIn('role', ['instructor', 'organization'])->firstOrFail();
    $courses = \App\Models\Course::with('lessons', 'level', 'categoryRelation')->withCount('enrollments')->where('user_id', $instructor->id)->where('status', 'Active')->paginate(6);
    $studentCount = \App\Models\Enrollment::whereIn('course_id', $instructor->courses()->pluck('id'))->count();
    $courseCount = $instructor->courses()->where('status', 'Active')->count();
    
    return view('users.profile', compact('instructor', 'courses', 'studentCount', 'courseCount'));
})->name('users.profile');
Route::post('/profile/contact', [\App\Http\Controllers\ContactController::class, 'instructorContact']);
Route::get('/courses/{slug}/checkout', function ($slug) {
    $course = \App\Models\Course::with('instructor')->where('status', 'Active')->where('slug', $slug)->firstOrFail();
    $isEnrolled = \App\Models\Enrollment::where('user_id', auth()->id())
        ->where('course_id', $course->id)->exists();
    return view('courses.checkout', compact('course', 'isEnrolled'));
})->middleware('auth');
Route::get('/courses/{slug}', function ($slug) {
    $course = \App\Models\Course::with('lessons', 'instructor', 'level')->withCount('enrollments')->where('status', 'Active')->where('slug', $slug)->firstOrFail();

    $isEnrolled = auth()->check() && \App\Models\Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
    $inWishlist = auth()->check() && \App\Models\Wishlist::where('user_id', auth()->id())->where('course_id', $course->id)->exists();

    $faqs = \App\Models\Faq::where('status', 'active')->orderBy('order')->take(3)->get();

    $reviews = \App\Models\Review::with('user')->where('course_id', $course->id)->where('is_approved', true)->latest()->get();
    $courseAvgRating = $reviews->avg('rating') ?? 0;
    $reviewCount = $reviews->count();
    $ratingCounts = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
    foreach ($reviews as $r) { if (isset($ratingCounts[$r->rating])) $ratingCounts[$r->rating]++; }

    $lessonCompletionMap = [];
    $courseQuizzes = collect();
    $courseExams = collect();
    $courseAssignments = collect();
    $courseCertificate = null;
    $announcements = \App\Models\Announcement::with('user')->where('course_id', $course->id)->latest()->take(5)->get();

    if (auth()->check() && $isEnrolled) {
        $completedIds = \App\Models\LessonCompletion::where('user_id', auth()->id())
            ->where('course_id', $course->id)->whereNotNull('completed_at')
            ->pluck('lesson_id')->toArray();
        foreach ($course->lessons as $lesson) {
            $lessonCompletionMap[$lesson->id] = in_array($lesson->id, $completedIds);
        }

        $courseQuizzes = \App\Models\Quiz::with('questions')->where('course_id', $course->id)->where('status', 'published')->where('is_exam', false)
            ->withCount(['results as user_result_exists' => function ($q) {
                $q->where('user_id', auth()->id());
            }])->get();

        $courseExams = \App\Models\Quiz::with('questions')->where('course_id', $course->id)->where('status', 'published')->where('is_exam', true)
            ->withCount(['results as user_result_exists' => function ($q) {
                $q->where('user_id', auth()->id());
            }])->get();

        $courseAssignments = \App\Models\Assignment::where('course_id', $course->id)->where('status', 'published')
            ->withCount(['submissions as user_submission_exists' => function ($q) {
                $q->where('user_id', auth()->id());
            }])->get();

        $courseCertificate = \App\Models\Certificate::where('course_id', $course->id)
            ->where('user_id', auth()->id())->first();
    }

    $userReview = null;
    $hasCompletedCourse = false;
    if (auth()->check() && $isEnrolled) {
        $userReview = \App\Models\Review::where('user_id', auth()->id())->where('course_id', $course->id)->first();
        $hasCompletedCourse = \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)->where('status', 'completed')->exists();
    }

    return view('courses.show', compact('course', 'isEnrolled', 'inWishlist', 'faqs', 'reviews', 'courseAvgRating', 'reviewCount', 'ratingCounts', 'lessonCompletionMap', 'courseQuizzes', 'courseExams', 'courseAssignments', 'courseCertificate', 'announcements', 'userReview', 'hasCompletedCourse'));
});
// Course Materials Hub (enrolled students only)
Route::get('/courses/{slug}/materials', function ($slug) {
    $course = \App\Models\Course::with('lessons', 'instructor', 'level')->withCount('enrollments')->where('status', 'Active')->where('slug', $slug)->firstOrFail();
    $isEnrolled = auth()->check() && \App\Models\Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
    if (!$isEnrolled) { abort(403, 'You must be enrolled to access course materials.'); }

    $lessonCompletionMap = [];
    $completedLessons = 0;
    $totalLessons = $course->lessons->count();
    if (auth()->check()) {
        $completions = \App\Models\LessonCompletion::where('user_id', auth()->id())
            ->where('course_id', $course->id)->get()->keyBy('lesson_id');
        foreach ($course->lessons as $l) {
            $lessonCompletionMap[$l->id] = $completions->has($l->id) && $completions[$l->id]->completed_at;
        }
        $completedLessons = $completions->filter(fn($c) => $c->completed_at)->count();
    }
    $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

    $quizzes = \App\Models\Quiz::withCount('questions')->where('course_id', $course->id)->where('status', 'published')->where('is_exam', false)->get();
    $exams = \App\Models\Quiz::withCount('questions')->where('course_id', $course->id)->where('status', 'published')->where('is_exam', true)->get();
    $quizResults = \App\Models\QuizResult::where('user_id', auth()->id())->whereIn('quiz_id', $quizzes->pluck('id'))->get()->keyBy('quiz_id');

    $assignments = \App\Models\Assignment::where('course_id', $course->id)->where('status', 'published')->orderBy('due_date')->get();
    $submissions = \App\Models\AssignmentSubmission::where('user_id', auth()->id())->whereIn('assignment_id', $assignments->pluck('id'))->get()->keyBy('assignment_id');

    $certificate = \App\Models\Certificate::where('course_id', $course->id)->where('user_id', auth()->id())->first();

    $announcements = \App\Models\Announcement::with('user')->where('course_id', $course->id)->latest()->take(5)->get();
    $courseNotes = \App\Models\CourseNote::where('course_id', $course->id)
        ->where('status', 'published')
        ->orderBy('display_order')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('courses.materials', compact('course', 'lessonCompletionMap', 'completedLessons', 'totalLessons', 'progressPercent', 'quizzes', 'exams', 'quizResults', 'assignments', 'submissions', 'certificate', 'announcements', 'courseNotes'));
})->middleware('auth');

// Lesson viewing route
Route::get('/courses/{slug}/lessons/{lessonId}', function ($slug, $lessonId) {
    $course = \App\Models\Course::with('lessons')->where('status', 'Active')->where('slug', $slug)->firstOrFail();
    $lesson = $course->lessons()->findOrFail($lessonId);

    $isEnrolled = auth()->check() && \App\Models\Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
    $isInstructor = auth()->check() && (auth()->user()->id === $course->user_id || auth()->user()->isAdmin());
    $canView = $isEnrolled || $isInstructor || $lesson->is_free_preview;

    $lessonCompleted = false;
    $lastPosition = 0;
    $lessonCompletionMap = [];
    $completedLessons = 0;
    $totalLessons = $course->lessons->count();
    $progressPercent = 0;

    if (auth()->check() && $isEnrolled) {
        $completions = \App\Models\LessonCompletion::where('user_id', auth()->id())
            ->where('course_id', $course->id)->get()->keyBy('lesson_id');

        foreach ($course->lessons as $l) {
            $lessonCompletionMap[$l->id] = $completions->has($l->id) && $completions[$l->id]->completed_at;
        }

        $lessonCompleted = $completions->has($lesson->id) && $completions[$lesson->id]->completed_at;
        $lastPosition = $completions[$lesson->id]->last_watched_position ?? 0;

        $completedLessons = $completions->filter(fn($c) => $c->completed_at)->count();
        $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
    }

    return view('courses.lesson', compact('course', 'lesson', 'isEnrolled', 'isInstructor', 'canView', 'lessonCompleted', 'lastPosition', 'lessonCompletionMap', 'completedLessons', 'totalLessons', 'progressPercent'));
});
Route::get('/lessons/{lesson}/download/video', [\App\Http\Controllers\LessonDownloadController::class, 'downloadVideo'])->name('lessons.download.video')->middleware('auth');
Route::get('/lessons/{lesson}/download/document', [\App\Http\Controllers\LessonDownloadController::class, 'downloadDocument'])->name('lessons.download.document')->middleware('auth');

Route::get('/instructors', function () {
    $instructors = \App\Models\User::where('role', 'instructor')->where('status', 'active')->latest()->get();
    return view('instructors.index', compact('instructors'));
});
Route::get('/organizations', function () {
    $organizations = \App\Models\User::where('role', 'organization')->where('status', 'active')->latest()->get();
    return view('organizations.index', compact('organizations'));
});
Route::get('/blogs', function () {
    $blogs = \App\Models\Blog::with('category', 'author')->where('status', 'published')->latest()->get();
    return view('blogs.index', compact('blogs'));
});
Route::get('/blogs/{slug}', function ($slug) {
    $blog = \App\Models\Blog::with('category', 'author')->where('slug', $slug)->where('status', 'published')->firstOrFail();
    return view('blogs.show', compact('blog'));
});
Route::get('/bundles', [BundleController::class, 'index']);
Route::get('/bundles/{slug}', [BundleController::class, 'show']);
Route::get('/about-us', function () {
    $page = \App\Models\Page::where('slug', 'about-us')->where('status', 'published')->first();
    return $page ? view('page', compact('page')) : view('about');
});
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::post('/contact', [ContactController::class, 'send']);
Route::post('/newsletter', function (\Illuminate\Http\Request $r) {
    $r->validate(['email' => 'required|email']);
    logger('Newsletter subscription: ' . $r->email);
    return back()->with('success', 'Subscribed to newsletter successfully!');
});
Route::post('/become-instructor', [AuthController::class, 'becomeInstructor'])->middleware('guest');
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{courseId}', [CartController::class, 'addCourse'])->middleware('auth');
Route::post('/cart/add-bundle/{bundleId}', [CartController::class, 'addBundle'])->middleware('auth');
Route::post('/cart/remove/{type}/{id}', [CartController::class, 'remove'])->middleware('auth');
Route::post('/cart/clear', [CartController::class, 'clear'])->middleware('auth');
Route::get('/checkout', [PaymentController::class, 'showCheckout'])->middleware('auth');
Route::post('/checkout/place-order', [CartController::class, 'placeOrder'])->middleware('auth');
Route::post('/checkout/paystack', [PaymentController::class, 'initiatePaystack'])->middleware('auth');
Route::get('/checkout/paystack/callback', [PaymentController::class, 'handlePaystackCallback'])->name('paystack.callback')->middleware('auth');
Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->middleware('auth');
Route::post('/coupon/remove', [CartController::class, 'removeCoupon'])->middleware('auth');
Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch']);

Route::get('/faq', function () {
    $faqs = \App\Models\Faq::where('status', 'active')->orderBy('order')->latest()->get();
    return view('faq', compact('faqs'));
});
Route::get('/about', function () {
    $page = \App\Models\Page::where('slug', 'about-us')->where('status', 'published')->first();
    return $page ? view('page', compact('page')) : view('about');
});
Route::get('/privacy-policy', function () {
    $page = \App\Models\Page::where('slug', 'privacy-policy')->where('status', 'published')->first();
    return $page ? view('page', compact('page')) : view('privacy-policy');
});
Route::get('/terms-conditions', function () {
    $page = \App\Models\Page::where('slug', 'terms-conditions')->where('status', 'published')->first();
    return $page ? view('page', compact('page')) : view('terms-conditions');
});
Route::get('/categories', fn() => view('categories'));
Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
Route::get('/search', SearchController::class);

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::post('/admin/admin-login', [AuthController::class, 'adminLogin'])->name('admin.login')->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', fn() => view('forgot-password'))->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', fn($token) => view('reset-password', ['token' => $token]))->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Webhooks
Route::post('/webhook', [\App\Http\Controllers\WebhookController::class, 'handle'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
Route::post('/webhook/paystack', [PaymentController::class, 'paystackWebhook'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/enroll/{courseId}', function ($courseId) {
        if (auth()->user()->role !== \App\Models\User::ROLE_STUDENT) {
            abort(403, 'Only students can enroll in courses.');
        }
        $course = \App\Models\Course::where('status', 'Active')->findOrFail($courseId);
        $isEnrolled = \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)->exists();
        if ($isEnrolled) {
            return redirect('/courses/' . $course->slug)->with('info', 'You are already enrolled in this course.');
        }
        $amountPaid = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);
        \App\Models\Enrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'amount_paid' => $amountPaid,
            'payment_status' => $amountPaid > 0 ? 'pending' : 'approved',
            'approval_status' => $amountPaid > 0 ? 'pending' : 'approved',
            'status' => 'in_progress',
        ]);
        \App\Notifications\CourseEnrolled::send(auth()->user(), $course);
        return redirect('/courses/' . $course->slug)->with('success', 'Enrolled successfully!');
    });

    // Lesson Completion
    Route::post('/lessons/{lesson}/toggle-completion', [\App\Http\Controllers\LessonCompletionController::class, 'toggle']);

    // Course Prerequisites
    Route::post('/courses/{course}/prerequisites', function (\App\Models\Course $course, \Illuminate\Http\Request $request) {
        if ($course->user_id !== auth()->id()) {
            abort(403);
        }
        $validated = $request->validate(['prerequisite_ids' => 'nullable|array', 'prerequisite_ids.*' => 'exists:courses,id']);
        $course->prerequisites()->sync($validated['prerequisite_ids'] ?? []);
        return back()->with('success', 'Prerequisites updated!');
    });

    // Course Discussions
    Route::get('/courses/{course:slug}/discussions', [\App\Http\Controllers\DiscussionController::class, 'index'])->name('courses.discussions');
    Route::post('/courses/{course:slug}/discussions', [\App\Http\Controllers\DiscussionController::class, 'store']);
    Route::post('/courses/{course:slug}/discussions/{discussion}/reply', [\App\Http\Controllers\DiscussionController::class, 'reply']);
    Route::post('/courses/{course:slug}/discussions/{discussion}/delete', [\App\Http\Controllers\DiscussionController::class, 'destroy']);

    // Notification Preferences
    Route::get('/notifications/preferences', function () {
        $preferences = \App\Models\NotificationPreference::where('user_id', auth()->id())->get()->keyBy('type');
        return view('dashboard.notification-preferences', compact('preferences'));
    });
    Route::post('/notifications/preferences', function (\Illuminate\Http\Request $request) {
        $types = ['course_enrolled', 'lesson_completed', 'course_completed', 'quiz_result', 'assignment_graded', 'discussion_reply'];
        foreach ($types as $type) {
            $channel = $request->input($type, 'none');
            if ($channel === 'none') {
                \App\Models\NotificationPreference::where('user_id', auth()->id())->where('type', $type)->delete();
            } else {
                \App\Models\NotificationPreference::updateOrCreate(
                    ['user_id' => auth()->id(), 'type' => $type],
                    ['channel' => $channel, 'enabled' => $channel !== 'none']
                );
            }
        }
        return back()->with('success', 'Notification preferences saved!');
    });

    // User-to-User Notification
    Route::get('/notifications/send', [\App\Http\Controllers\NotificationController::class, 'sendForm']);
    Route::post('/notifications/send', [\App\Http\Controllers\NotificationController::class, 'sendToUser']);

    // Video Progress
    Route::post('/lessons/{lesson}/progress', function (\App\Models\Lesson $lesson, \Illuminate\Http\Request $request) {
        $request->validate(['position' => 'required|integer|min:0']);
        $completion = \App\Models\LessonCompletion::firstOrNew([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
            'course_id' => $lesson->course_id,
        ]);
        $completion->last_watched_position = $request->position;
        $completion->save();
        return response()->json(['success' => true]);
    });

    // User Profile
    Route::get('/profile', fn() => view('users.profile'))->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/organization', fn() => view('users.organization'));

    // Student Dashboard
    Route::middleware('role:' . User::ROLE_STUDENT)->prefix('dashboard')->group(function () {
        Route::get('/', function () {
            $enrollments = \App\Models\Enrollment::with([
                    'course.instructor',
                    'course.lessons',
                    'course' => fn($q) => $q->withCount([
                        'quizzes' => fn($q2) => $q2->where('status', 'published'),
                        'assignments' => fn($q2) => $q2->where('status', 'published'),
                    ]),
                ])
                ->where('user_id', auth()->id())->latest()->get();
            $totalEnrolled = $enrollments->count();
            $inProgress = $enrollments->where('status', 'in_progress')->count();
            $completed = $enrollments->where('status', 'completed')->count();
            $certificateCount = \App\Models\Certificate::where('user_id', auth()->id())->count();
            $latest = $enrollments->take(4);
            // Pre-compute aggregated progress counts to avoid N+1
            $courseIds = $enrollments->pluck('course_id')->filter()->toArray();
            $progressCounts = [];
            if (!empty($courseIds)) {
                $completions = \App\Models\LessonCompletion::where('user_id', auth()->id())
                    ->whereIn('course_id', $courseIds)
                    ->whereNotNull('completed_at')
                    ->selectRaw('course_id, count(*) as completed')
                    ->groupBy('course_id')
                    ->pluck('completed', 'course_id');
                foreach ($courseIds as $cid) {
                    $progressCounts[$cid] = $completions[$cid] ?? 0;
                }
            }
            // My Tasks: upcoming deadlines & pending quizzes
            $courseIdsArr = $enrollments->pluck('course_id')->filter()->toArray();
            $pendingAssignments = collect();
            $pendingQuizzes = collect();
            $pendingExams = collect();
            $recentGraded = collect();
            if (!empty($courseIdsArr)) {
                $pendingAssignments = \App\Models\Assignment::with('course')
                    ->whereIn('course_id', $courseIdsArr)->where('status', 'published')
                    ->where(function ($q) { $q->whereNull('due_date')->orWhere('due_date', '>=', now()); })
                    ->whereDoesntHave('submissions', function ($q) { $q->where('user_id', auth()->id()); })
                    ->orderBy('due_date')->take(5)->get();
                $pendingQuizzes = \App\Models\Quiz::with('course')
                    ->whereIn('course_id', $courseIdsArr)->where('status', 'published')
                    ->where('is_exam', false)
                    ->whereDoesntHave('results', function ($q) { $q->where('user_id', auth()->id()); })
                    ->take(5)->get();
                $pendingExams = \App\Models\Quiz::with('course')
                    ->whereIn('course_id', $courseIdsArr)->where('status', 'published')
                    ->where('is_exam', true)
                    ->whereDoesntHave('results', function ($q) { $q->where('user_id', auth()->id()); })
                    ->take(5)->get();
                $recentGraded = \App\Models\AssignmentSubmission::with('assignment.course')
                    ->where('user_id', auth()->id())->whereNotNull('score')
                    ->latest('graded_at')->take(5)->get();
            }
            return view('dashboard.index', compact('enrollments', 'totalEnrolled', 'inProgress', 'completed', 'certificateCount', 'latest', 'progressCounts', 'pendingAssignments', 'pendingQuizzes', 'pendingExams', 'recentGraded'));
        })->name('dashboard');
        Route::get('/my-enrolled-course', function () {
            $enrollments = \App\Models\Enrollment::with('course.instructor', 'course.lessons')
                ->where('user_id', auth()->id())->latest()->get();

            $courseIds = $enrollments->pluck('course_id')->filter()->toArray();
            $completionCounts = [];
            if (!empty($courseIds)) {
                $completionCounts = \App\Models\LessonCompletion::where('user_id', auth()->id())
                    ->whereIn('course_id', $courseIds)->whereNotNull('completed_at')
                    ->selectRaw('course_id, count(*) as completed')
                    ->groupBy('course_id')
                    ->pluck('completed', 'course_id');
            }

            $progress = $enrollments->mapWithKeys(function ($e) use ($completionCounts) {
                $total = $e->course?->lessons->count() ?? 0;
                $completed = $completionCounts[$e->course_id] ?? 0;
                return [$e->id => ['total' => $total, 'completed' => $completed]];
            });

            return view('dashboard.my-enrolled-course', compact('enrollments', 'progress'));
        });
        Route::get('/purchase-course', function () {
            $purchases = \App\Models\Enrollment::with('course')
                ->where('user_id', auth()->id())->latest()->get();
            return view('dashboard.purchase-course', compact('purchases'));
        });
        Route::get('/term-reports', function () {
            $reports = \App\Models\StudentTermReport::with(['student', 'instructor', 'authorizedBy'])
                ->where('student_id', auth()->id())
                ->latest()->get()
                ->filter(fn ($report) => $report->canStudentSee())
                ->values();

            return view('dashboard.term-reports', compact('reports'));
        })->name('dashboard.term-reports');
        Route::get('/bundle-course', function () {
            $bundles = \App\Models\Bundle::with('courses')->latest()->get();
            return view('dashboard.bundle-course', compact('bundles'));
        });
        Route::get('/certificate', function () {
            $certificates = \App\Models\Certificate::with('course')
                ->where('user_id', auth()->id())->latest()->get();
            return view('dashboard.certificate', compact('certificates'));
        });
        Route::get('/certificate/{certificate}/preview', [CertificateController::class, 'preview'])->name('certificate.preview');
        Route::get('/certificate/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');
        Route::get('/quizzes/my-result', [QuizController::class, 'myResults'])->name('quizzes.my-result');
        Route::get('/quizzes/{quiz}/instructions', [QuizController::class, 'instructions']);
        Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take']);
        Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit']);
        Route::get('/exams', function () {
            $courseIds = \App\Models\Enrollment::where('user_id', auth()->id())
                ->whereIn('status', ['in_progress', 'completed'])
                ->pluck('course_id');
            $exams = \App\Models\Quiz::with('course')->withCount('questions')
                ->whereIn('course_id', $courseIds)
                ->where('is_exam', true)
                ->where('status', 'published')
                ->latest()->get();
            return view('dashboard.exams', compact('exams'));
        });
        Route::get('/exams/{quiz}/instructions', [QuizController::class, 'instructions']);
        Route::get('/exams/{quiz}/take', [QuizController::class, 'take']);
        Route::post('/exams/{quiz}/submit', [QuizController::class, 'submit']);
        Route::get('/assignments/{assignment}/submit', [AssignmentController::class, 'submitForm']);
        Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
        Route::get('/assignments', function () {
            $submissions = \App\Models\AssignmentSubmission::with('assignment.course')
                ->where('user_id', auth()->id())->latest()->get();
            return view('dashboard.assignments', compact('submissions'));
        });
        Route::get('/course-review', [ReviewController::class, 'index']);
        Route::post('/course-review/{course}', [ReviewController::class, 'store']);
        Route::get('/offline-payment', function () {
            $payments = \App\Models\Enrollment::with('course')->where('user_id', auth()->id())
                ->where('amount_paid', '>', 0)->latest()->get();
            return view('dashboard.offline-payment', compact('payments'));
        });
        Route::get('/supports/create', function () {
            $enrollments = \App\Models\Enrollment::with('course')
                ->where('user_id', auth()->id())
                ->where('status', 'in_progress')
                ->get();
            $courses = $enrollments->map->course->filter();
            return view('dashboard.supports.create', compact('courses'));
        });
        Route::post('/supports', [SupportTicketController::class, 'store']);
        Route::get('/supports', [SupportTicketController::class, 'index'])->name('dashboard.supports');
        Route::get('/course-support', function () {
            $tickets = \App\Models\SupportTicket::where('user_id', auth()->id())
                ->latest()->get();
            return view('dashboard.course-support', compact('tickets'));
        });
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notificationLog}/read', [NotificationController::class, 'markRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::get('/wishlists', [WishlistController::class, 'index']);
        Route::post('/wishlists/toggle/{courseId}', [WishlistController::class, 'toggle']);
        Route::get('/course-notes', [DashboardCourseNoteController::class, 'index'])->name('dashboard.course-notes.index');
        Route::get('/course-notes/{courseNote}', [DashboardCourseNoteController::class, 'show'])->name('dashboard.course-notes.show');
        Route::get('/course-notes/{courseNote}/download', [DashboardCourseNoteController::class, 'download'])->name('dashboard.course-notes.download');
        Route::get('/profile', fn() => view('dashboard.profile'));
        Route::get('/settings', fn() => view('dashboard.settings'));
        Route::post('/settings', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            auth()->user()->update(['password' => Hash::make($request->password)]);
            return back()->with('status', 'Password changed successfully!');
        });
        Route::prefix('zoom')->name('zoom.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Zoom\ZoomMeetingController::class, 'index'])->name('index');
            Route::get('/calendar', [\App\Http\Controllers\Zoom\ZoomMeetingController::class, 'calendar'])->name('calendar');
            Route::get('/calendar/ics', [\App\Http\Controllers\Zoom\ZoomMeetingController::class, 'calendarIcs'])->name('calendar.ics');
            Route::get('/meetings/{meeting}', [\App\Http\Controllers\Zoom\ZoomMeetingController::class, 'show'])->name('show');
            Route::post('/meetings/{meeting}/join', [\App\Http\Controllers\Zoom\ZoomMeetingController::class, 'join'])->name('join');
            Route::get('/meetings/{meeting}/ics', [\App\Http\Controllers\Zoom\ZoomMeetingController::class, 'ics'])->name('ics');
        });
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/supports/{supportTicket}', [SupportTicketController::class, 'show']);
        Route::post('/supports/{supportTicket}/reply', [SupportTicketController::class, 'reply']);
    });

    // Instructor Dashboard
    Route::get('/instructor/pending-approval', function () {
        if (auth()->user() && auth()->user()->is_approved) {
            return redirect()->route('instructor.dashboard.dashboard');
        }
        return view('instructor.pending-approval');
    })->name('instructor.pending-approval');

    Route::middleware(['role:' . User::ROLE_INSTRUCTOR, 'instructor_approved'])->prefix('instructor')->name('instructor.dashboard.')->group(function () {
        Route::get('/', function () {
            $courses = \App\Models\Course::withCount('enrollments')->where('user_id', auth()->id())->latest()->get();
            $totalStudents = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))->count();
            $recentNotifications = \App\Models\NotificationLog::where('user_id', auth()->id())->latest()->take(5)->get();
            return view('instructor.index', compact('courses', 'totalStudents', 'recentNotifications'));
        })->name('dashboard');
        Route::get('/courses', [InstructorCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [InstructorCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/edit/{id}', [InstructorCourseController::class, 'edit'])->name('courses.edit');
        Route::post('/courses/edit/{id}', [InstructorCourseController::class, 'update'])->name('courses.update');
        Route::get('/courses/{id}/lessons', [InstructorCourseController::class, 'lessons'])->name('courses.lessons');
        Route::post('/courses/{id}/lessons', [InstructorCourseController::class, 'storeLesson'])->name('courses.lessons.store');
        Route::post('/courses/{courseId}/lessons/{lessonId}/delete', [InstructorCourseController::class, 'destroyLesson'])->name('courses.lessons.delete');
        Route::post('/courses/delete/{id}', [InstructorCourseController::class, 'destroy'])->name('courses.delete');
        Route::post('/courses/{id}/lessons/reorder', [InstructorCourseController::class, 'updateLessonOrder'])->name('courses.lessons.reorder');
        Route::get('/courses/{course}/quizzes', [QuizController::class, 'index'])->name('courses.quizzes');
        Route::get('/courses/{course}/quizzes/create', [QuizController::class, 'create']);
        Route::post('/courses/{course}/quizzes', [QuizController::class, 'store']);
        Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::post('/quizzes/{quiz}', [QuizController::class, 'update']);
        Route::post('/quizzes/{quiz}/delete', [QuizController::class, 'destroy']);
        Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion']);
        Route::post('/quizzes/questions/{question}/delete', [QuizController::class, 'destroyQuestion']);
        Route::post('/quizzes/questions/{question}/update', [QuizController::class, 'updateQuestion']);
        Route::post('/quizzes/{quiz}/extract-questions', [QuizController::class, 'extractQuestions']);
        Route::post('/quizzes/{quiz}/bulk-store-questions', [QuizController::class, 'bulkStoreQuestions']);
        Route::post('/quizzes/{quiz}/release-results', [QuizController::class, 'releaseResults']);
        Route::post('/courses/{course}/announcements', function (\App\Models\Course $course, \Illuminate\Http\Request $request) {
            if ($course->user_id !== auth()->id()) { abort(403); }
            $validated = $request->validate(['title' => 'required|string|max:255', 'body' => 'required|string']);
            \App\Models\Announcement::create([
                'course_id' => $course->id,
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'body' => $validated['body'],
            ]);
            return back()->with('success', 'Announcement posted!');
        });
        Route::post('/announcements/{announcement}/delete', function (\App\Models\Announcement $announcement) {
            if ($announcement->user_id !== auth()->id()) { abort(403); }
            $announcement->delete();
            return back()->with('success', 'Announcement deleted.');
        });
        Route::get('/courses/{course}/assignments', [AssignmentController::class, 'index'])->name('courses.assignments');
        Route::get('/courses/{course}/assignments/create', [AssignmentController::class, 'create']);
        Route::post('/courses/{course}/assignments', [AssignmentController::class, 'store']);
        Route::get('/assignments', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $assignments = \App\Models\Assignment::with("course")->withCount("submissions")->whereIn("course_id", $courseIds)->latest()->get();
            $courses = \App\Models\Course::where("user_id", auth()->id())->latest()->get();
            return view('instructor.assignments', compact('assignments', 'courses'));
        });
        Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
        Route::post('/assignments/{assignment}', [AssignmentController::class, 'update']);
        Route::post('/assignments/{assignment}/delete', [AssignmentController::class, 'destroy']);
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/submissions/{submission}/grade', [AssignmentController::class, 'grade']);
        Route::get('/earnings', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $totalEarnings = \App\Models\Enrollment::whereIn("course_id", $courseIds)->sum("amount_paid");
            $currentMonth = \App\Models\Enrollment::whereIn("course_id", $courseIds)
                ->whereMonth("created_at", now()->month)->sum("amount_paid");
            $pendingEarnings = \App\Models\Enrollment::whereIn("course_id", $courseIds)
                ->where("status", "in_progress")->sum("amount_paid");
            return view('instructor.earnings', compact('totalEarnings', 'currentMonth', 'pendingEarnings'));
        });
        Route::get('/payouts', [\App\Http\Controllers\PayoutController::class, 'index']);
        Route::post('/payouts/request', [\App\Http\Controllers\PayoutController::class, 'request']);
        Route::get('/students', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $studentIds = \App\Models\Enrollment::whereIn("course_id", $courseIds)->pluck("user_id");
            $students = \App\Models\User::whereIn("id", $studentIds)->get();
            return view('instructor.students', compact('students'));
        });
        Route::get('/reviews', [ReviewController::class, 'instructorReviews']);
        Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve']);
        Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject']);
        Route::get('/quiz', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $quizzes = \App\Models\Quiz::with("course")->withCount('questions')->whereIn("course_id", $courseIds)->latest()->get();
            $courses = \App\Models\Course::where("user_id", auth()->id())->latest()->get();
            return view('instructor.quiz', compact('quizzes', 'courses'));
        });
        Route::get('/supports', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $tickets = \App\Models\SupportTicket::with('user')->whereIn("course_id", $courseIds)->latest()->get();
            return view('instructor.supports', compact('tickets'));
        });
        Route::get('/contact-messages', function () {
            $messages = \App\Models\ContactMessage::with('instructor')
                ->where('instructor_id', auth()->id())
                ->latest()->get();
            return view('instructor.contact-messages', compact('messages'));
        });
        Route::post('/contact-messages/{contactMessage}/read', function (\App\Models\ContactMessage $contactMessage) {
            if ($contactMessage->instructor_id !== auth()->id()) { abort(403); }
            $contactMessage->update(['is_read' => true]);
            return back()->with('success', 'Message marked as read.');
        });
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notificationLog}/read', [NotificationController::class, 'markRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::get('/pending-payments', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $pendingPayments = \App\Models\Enrollment::with(['course', 'user', 'paymentMethod'])
                ->whereIn("course_id", $courseIds)
                ->where("payment_status", "pending")
                ->latest()->get();
            return view('instructor.pending-payments', compact('pendingPayments'));
        });
        Route::post('/pending-payments/{enrollment}/approve', function (\Illuminate\Http\Request $request, \App\Models\Enrollment $enrollment) {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            if (!in_array($enrollment->course_id, $courseIds->toArray())) {
                return back()->with('error', 'Unauthorized.');
            }
            $enrollment->update([
                'payment_status' => 'approved',
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
            return back()->with('success', 'Payment approved. Student now has full access.');
        });
        Route::post('/enrollments/{enrollment}/approve', function (\Illuminate\Http\Request $request, \App\Models\Enrollment $enrollment) {
            $courseIds = \App\Models\Course::where('user_id', auth()->id())->pluck('id');
            if (!in_array($enrollment->course_id, $courseIds->toArray())) {
                return back()->with('error', 'Unauthorized.');
            }

            $enrollment->update([
                'approval_status' => 'approved',
                'payment_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return back()->with('success', 'Enrollment approved. The student can now access the paid course.');
        })->name('enrollments.approve');
        Route::post('/enrollments/{enrollment}/reject', function (\Illuminate\Http\Request $request, \App\Models\Enrollment $enrollment) {
            $courseIds = \App\Models\Course::where('user_id', auth()->id())->pluck('id');
            if (!in_array($enrollment->course_id, $courseIds->toArray())) {
                return back()->with('error', 'Unauthorized.');
            }

            $enrollment->update([
                'approval_status' => 'rejected',
                'payment_status' => 'rejected',
            ]);

            return back()->with('success', 'Enrollment rejected. The student remains blocked until the instructor reviews it again.');
        })->name('enrollments.reject');
        Route::post('/pending-payments/{enrollment}/reject', function (\Illuminate\Http\Request $request, \App\Models\Enrollment $enrollment) {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            if (!in_array($enrollment->course_id, $courseIds->toArray())) {
                return back()->with('error', 'Unauthorized.');
            }
            $enrollment->update(['payment_status' => 'rejected']);
            return back()->with('success', 'Payment rejected.');
        });
        Route::get('/course-notes', [InstructorCourseNoteController::class, 'index'])->name('course-notes.index');
        Route::get('/course-notes/create', [InstructorCourseNoteController::class, 'create'])->name('course-notes.create');
        Route::post('/course-notes', [InstructorCourseNoteController::class, 'store'])->name('course-notes.store');
        Route::get('/course-notes/{courseNote}', [InstructorCourseNoteController::class, 'show'])->name('course-notes.show');
        Route::get('/course-notes/{courseNote}/edit', [InstructorCourseNoteController::class, 'edit'])->name('course-notes.edit');
        Route::put('/course-notes/{courseNote}', [InstructorCourseNoteController::class, 'update'])->name('course-notes.update');
        Route::delete('/course-notes/{courseNote}', [InstructorCourseNoteController::class, 'destroy'])->name('course-notes.destroy');
        Route::get('/settings', fn() => view('instructor.settings'));
        Route::post('/settings', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
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
        Route::post('/courses/delete/{id}', [OrgCourseController::class, 'destroy'])->name('courses.delete');
        Route::post('/courses/{id}/lessons/reorder', [OrgCourseController::class, 'updateLessonOrder'])->name('courses.lessons.reorder');
        Route::get('/courses/{id}/lessons', [OrgCourseController::class, 'lessons'])->name('courses.lessons');
        Route::post('/courses/{id}/lessons', [OrgCourseController::class, 'storeLesson'])->name('courses.lessons.store');
        Route::post('/courses/{courseId}/lessons/{lessonId}/delete', [OrgCourseController::class, 'destroyLesson'])->name('courses.lessons.delete');
        Route::get('/courses/bundle', function () {
            $bundles = \App\Models\Bundle::withCount('courses')->latest()->get();
            return view('org.bundle', compact('bundles'));
        });
        Route::get('/instructors', function () {
            $instructors = \App\Models\User::where("organization_id", auth()->id())->get();
            return view('org.instructors', compact('instructors'));
        });
        Route::get('/instructors/create', fn() => view('org.instructors-create'))->name('instructors.create');
        Route::post('/instructors', [OrgCourseController::class, 'storeInstructor']);
        Route::get('/students', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $studentIds = \App\Models\Enrollment::whereIn("course_id", $courseIds)->pluck("user_id");
            $students = \App\Models\User::whereIn("id", $studentIds)->get();
            return view('org.students', compact('students'));
        });
        Route::get('/financial', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $totalRevenue = \App\Models\Enrollment::whereIn("course_id", $courseIds)->sum("amount_paid");
            $currentMonth = \App\Models\Enrollment::whereIn("course_id", $courseIds)
                ->whereMonth("created_at", now()->month)->sum("amount_paid");
            $pendingAmount = \App\Models\Enrollment::whereIn("course_id", $courseIds)
                ->where("status", "in_progress")->sum("amount_paid");
            $transactions = \App\Models\Enrollment::with('course', 'user')->whereIn("course_id", $courseIds)->latest()->get();
            return view('org.financial', compact('totalRevenue', 'currentMonth', 'pendingAmount', 'transactions'));
        });
        Route::get('/financial/payout', fn() => view('org.payout'));
        Route::get('/reviews', [ReviewController::class, 'orgReviews']);
        Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve']);
        Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject']);
        Route::get('/supports/create', fn() => view('org.supports-create'));
        Route::get('/supports', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $tickets = \App\Models\SupportTicket::with('user')->whereIn("course_id", $courseIds)->latest()->get();
            return view('org.supports', compact('tickets'));
        });
        Route::get('/noticeboard', [NoticeboardController::class, 'orgIndex']);
        Route::post('/noticeboard', [NoticeboardController::class, 'store']);
        Route::post('/noticeboard/{noticeboard}', [NoticeboardController::class, 'update']);
        Route::post('/noticeboard/{noticeboard}/delete', [NoticeboardController::class, 'destroy']);
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notificationLog}/read', [NotificationController::class, 'markRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::get('/wishlists', function () {
            $courseIds = \App\Models\Course::where("user_id", auth()->id())->pluck("id");
            $wishlists = \App\Models\Wishlist::with("course")->withCount("course.wishlists")->whereIn("course_id", $courseIds)->get();
            return view('org.wishlists', compact('wishlists'));
        });
        Route::get('/settings', fn() => view('org.settings'));
        Route::post('/settings', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::get('/profile', function () {
            $user = auth()->user();
            $courseCount = \App\Models\Course::where("user_id", $user->id)->count();
            $instructorCount = \App\Models\User::where("organization_id", $user->id)->count();
            $courseIds = \App\Models\Course::where("user_id", $user->id)->pluck("id");
            $studentCount = \App\Models\Enrollment::whereIn("course_id", $courseIds)->count();
            return view('org.profile', compact('user', 'courseCount', 'instructorCount', 'studentCount'));
        });
    });

    // Admin Dashboard
    Route::middleware('role:' . User::ROLE_ADMIN . ',' . User::ROLE_STAFF)->prefix('admin')->name('admin.dashboard.')->group(function () {
        Route::get('/', function () {
            $totalStudents = \App\Models\User::where('role', 'student')->count();
            $totalCourses = \App\Models\Course::count();
            $totalInstructors = \App\Models\User::where('role', 'instructor')->count();
            $totalPendingInstructors = \App\Models\User::where('role', 'instructor')->where('is_approved', false)->count();
            $totalEnrollments = \App\Models\Enrollment::count();
            $totalOrganizations = \App\Models\User::where('role', 'organization')->count();
            $activeCourses = \App\Models\Course::where('status', 'Active')->count();
            $totalCertificates = \App\Models\Certificate::count();
            $pendingReviews = \App\Models\Review::where('is_approved', false)->count();
            $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
            $monthSelect = $driver === 'mysql'
                ? "DATE_FORMAT(created_at, '%Y-%m') as month"
                : "strftime('%Y-%m', created_at) as month";

            $recent = \App\Models\Enrollment::with('user', 'course')->latest()->take(5)->get();
            $recentCert = \App\Models\Certificate::with('course')->latest()->take(5)->get();
            $popular = \App\Models\Course::withCount('enrollments')->orderByDesc('enrollments_count')->take(5)->get();
            $monthlyData = \App\Models\Enrollment::selectRaw("$monthSelect, count(*) as count, sum(amount_paid) as revenue")
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')->orderBy('month')->get();
            $totalUsers = \App\Models\User::count();
            $activeCategories = \App\Models\Category::where('status', 'active')->count();
            $publishedBlogs = \App\Models\Blog::where('status', 'published')->count();
            $totalRevenue = \App\Models\Enrollment::sum('amount_paid');

            $courseDistribution = \App\Models\Category::withCount('courses')
                ->where('status', 'active')->get()->map(fn($c) => ['label' => $c->name, 'value' => $c->courses_count])->toArray();
            $roleDistribution = [
                ['label' => 'Students', 'value' => $totalStudents],
                ['label' => 'Instructors', 'value' => $totalInstructors],
                ['label' => 'Organizations', 'value' => $totalOrganizations],
                ['label' => 'Admins', 'value' => \App\Models\User::where('role', 'admin')->count()],
            ];

            $daySelect = $driver === 'mysql'
                ? "DATE_FORMAT(created_at, '%w') as day"
                : "strftime('%w', created_at) as day";

            $weeklyEnrollments = \App\Models\Enrollment::selectRaw("$daySelect, count(*) as count")
                ->where('created_at', '>=', now()->subWeek())
                ->groupBy('day')->orderBy('day')->get()->pluck('count', 'day')->toArray();
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $weeklyLabels = $days;
            $weeklyData = array_map(fn($d) => $weeklyEnrollments[array_search($d, $days)] ?? 0, $days);

            $storagePath = public_path('storage');
            $symlinkWarning = (!file_exists($storagePath) || !is_link($storagePath));

            return view('admin.index', compact(
                'totalStudents', 'totalCourses', 'totalInstructors', 'totalPendingInstructors',
                'totalEnrollments', 'totalOrganizations', 'activeCourses', 'totalCertificates',
                'pendingReviews', 'recent', 'recentCert', 'popular', 'monthlyData',
                'totalUsers', 'activeCategories', 'publishedBlogs', 'totalRevenue',
                'courseDistribution', 'roleDistribution', 'weeklyLabels', 'weeklyData',
                'symlinkWarning'
            ));
        })->name('dashboard');
        Route::get('/course', function () {
            $courses = \App\Models\Course::with('instructor')->withCount('enrollments')->latest()->get();
            return view('admin.course.index', compact('courses'));
        });
        Route::get('/course/bundle', [BundleController::class, 'adminIndex']);
        Route::post('/course/bundle', [BundleController::class, 'store']);
        Route::post('/course/bundle/{bundle}', [BundleController::class, 'update']);
        Route::post('/course/bundle/{bundle}/delete', [BundleController::class, 'destroy']);
        Route::get('/course/level', [LevelController::class, 'index']);
        Route::post('/course/level', [LevelController::class, 'store']);
        Route::post('/course/level/{level}', [LevelController::class, 'update']);
        Route::post('/course/level/{level}/delete', [LevelController::class, 'destroy']);
        Route::get('/course/tag', [TagController::class, 'index']);
        Route::post('/course/tag', [TagController::class, 'store']);
        Route::post('/course/tag/{tag}', [TagController::class, 'update']);
        Route::post('/course/tag/{tag}/delete', [TagController::class, 'destroy']);
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
        Route::post('/users/{user}/toggle-status', [AdminCrudController::class, 'toggleUserStatus']);
        Route::post('/users/{user}/delete', [AdminCrudController::class, 'destroyUser']);
        Route::get('/staff', [StaffController::class, 'index']);
        Route::post('/staff', [StaffController::class, 'store']);
        Route::post('/staff/{staff}', [StaffController::class, 'update']);
        Route::post('/staff/{staff}/delete', [StaffController::class, 'destroy']);
        Route::get('/blog/category', [AdminCrudController::class, 'blogCategories']);
        Route::post('/blog/category', [AdminCrudController::class, 'storeBlogCategory']);
        Route::post('/blog/category/{blogCategory}', [AdminCrudController::class, 'updateBlogCategory']);
        Route::post('/blog/category/{blogCategory}/delete', [AdminCrudController::class, 'destroyBlogCategory']);
        Route::get('/blog', [AdminCrudController::class, 'blogs']);
        Route::post('/blog', [AdminCrudController::class, 'storeBlog']);
        Route::post('/blog/{blog}', [AdminCrudController::class, 'updateBlog']);
        Route::post('/blog/{blog}/delete', [AdminCrudController::class, 'destroyBlog']);
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
        Route::post('/contact/{contactMessage}/delete', [AdminCrudController::class, 'destroyContactMessage']);
        Route::get('/payment-method', [AdminCrudController::class, 'paymentMethods']);
        Route::post('/payment-method', [AdminCrudController::class, 'storePaymentMethod']);
        Route::post('/payment-method/{paymentMethod}', [AdminCrudController::class, 'updatePaymentMethod']);
        Route::post('/payment-method/{paymentMethod}/delete', [AdminCrudController::class, 'destroyPaymentMethod']);
        Route::get('/financial/payouts', [\App\Http\Controllers\PayoutController::class, 'adminIndex']);
        Route::post('/financial/payouts/{payout}/approve', [\App\Http\Controllers\PayoutController::class, 'approve']);
        Route::post('/financial/payouts/{payout}/reject', [\App\Http\Controllers\PayoutController::class, 'reject']);
        Route::get('/financial/payout-request', [\App\Http\Controllers\PayoutController::class, 'adminIndex']);
        Route::get('/financial/sale', fn() => view('admin.financial.sale'));
        Route::get('/financial/offline', function () {
            $payments = \App\Models\Enrollment::with('user', 'course', 'paymentMethod')
                ->whereNotNull('payment_provider')
                ->whereIn('payment_provider', ['airtel', 'mtn'])
                ->latest()
                ->get();
            return view('admin.financial.offline', compact('payments'));
        });
        Route::get('/certificate', [AdminCrudController::class, 'certificates']);
        Route::get('/certificate/create', [AdminController::class, 'certificateCreate'])->name('certificate.create');
        Route::post('/certificate', [AdminController::class, 'storeCertificate'])->name('certificate.store');
        Route::get('/enrollment/all', [AdminCrudController::class, 'allEnrollments']);
        Route::get('/enrollment/new-create', [AdminController::class, 'newEnrollment'])->name('enrollment.new-create');
        Route::post('/enrollment', [AdminController::class, 'storeEnrollment']);
        Route::get('/marketing/coupon', [AdminCrudController::class, 'coupons']);
        Route::post('/marketing/coupon', [AdminCrudController::class, 'storeCoupon']);
        Route::post('/marketing/coupon/{coupon}', [AdminCrudController::class, 'updateCoupon']);
        Route::post('/marketing/coupon/{coupon}/delete', [AdminCrudController::class, 'destroyCoupon']);
        Route::redirect('/review', '/admin/review/course-review');
        Route::get('/review/course-review', [ReviewController::class, 'adminReviews']);
        Route::post('/review/{review}/approve', [ReviewController::class, 'approve']);
        Route::post('/review/{review}/delete', [ReviewController::class, 'destroy']);
        Route::get('/notification', [AdminCrudController::class, 'notificationTemplates']);
        Route::post('/notification', [AdminCrudController::class, 'storeNotificationTemplate']);
        Route::post('/notification/{notificationTemplate}', [AdminCrudController::class, 'updateNotificationTemplate']);
        Route::post('/notification/{notificationTemplate}/delete', [AdminCrudController::class, 'destroyNotificationTemplate']);
        Route::post('/notification/send-test', [NotificationController::class, 'sendTest']);
        Route::get('/notification/history', function () {
            $logs = \App\Models\NotificationLog::with('user', 'template')->latest()->paginate(20);
            return view('admin.notification.history', compact('logs'));
        });
        Route::get('/support-ticket/category', [SupportTicketCategoryController::class, 'index']);
        Route::post('/support-ticket/category', [SupportTicketCategoryController::class, 'store']);
        Route::post('/support-ticket/category/{supportTicketCategory}', [SupportTicketCategoryController::class, 'update']);
        Route::post('/support-ticket/category/{supportTicketCategory}/delete', [SupportTicketCategoryController::class, 'destroy']);
        Route::get('/support-ticket/ticket', [AdminCrudController::class, 'supportTickets']);
        Route::get('/support-ticket/ticket/{supportTicket}', [SupportTicketController::class, 'show']);
        Route::post('/support-ticket/ticket/{supportTicket}', [AdminCrudController::class, 'updateSupportTicket']);
        Route::post('/support-ticket/ticket/{supportTicket}/delete', [AdminCrudController::class, 'destroySupportTicket']);
        Route::get('/meet-provider', [MeetProviderController::class, 'index']);
        Route::post('/meet-provider', [MeetProviderController::class, 'store']);
        Route::post('/meet-provider/{meetProvider}', [MeetProviderController::class, 'update']);
        Route::post('/meet-provider/{meetProvider}/delete', [MeetProviderController::class, 'destroy']);
        Route::get('/lms-module/subscription', [SubscriptionController::class, 'index']);
        Route::post('/lms-module/subscription', [SubscriptionController::class, 'store']);
        Route::post('/lms-module/subscription/{subscriptionPlan}', [SubscriptionController::class, 'update']);
        Route::post('/lms-module/subscription/{subscriptionPlan}/delete', [SubscriptionController::class, 'destroy']);
        Route::get('/search', App\Http\Controllers\Admin\AdminSearchController::class);
        Route::get('/storage-health', [\App\Http\Controllers\Admin\StorageHealthController::class, 'index']);
        Route::post('/storage-health/fix', [\App\Http\Controllers\Admin\StorageHealthController::class, 'fix']);
        Route::get('/settings', [SettingsController::class, 'index']);
        Route::get('/theme-setting', fn() => redirect('/admin/settings?tab=theme'));
        Route::post('/theme-setting', [AdminController::class, 'updateThemeSetting']);
        Route::get('/settings/school', fn() => redirect('/admin/settings?tab=school'));
        Route::post('/settings/school', [SettingsController::class, 'updateSchool']);
        Route::get('/settings/approve-instructors', fn() => redirect('/admin/settings?tab=instructors'));
        Route::post('/settings/instructors/{user}/approve', [SettingsController::class, 'approveInstructor']);
        Route::post('/settings/instructors/{user}/disapprove', [SettingsController::class, 'disapproveInstructor']);
        Route::post('/settings/instructors/{user}/toggle-super', [SettingsController::class, 'toggleSuperInstructor'])->name('settings.instructors.toggle-super');
        Route::get('/site-language', [AdminCrudController::class, 'siteLanguages']);
        Route::post('/site-language', [AdminCrudController::class, 'storeSiteLanguage']);
        Route::post('/site-language/{siteLanguage}/update', [AdminCrudController::class, 'updateSiteLanguage']);
        Route::post('/site-language/{siteLanguage}/delete', [AdminCrudController::class, 'destroySiteLanguage']);
        Route::get('/language', fn() => view('admin.language'));
        Route::get('/theme', fn() => view('admin.theme'));
        Route::get('/currency', [AdminCrudController::class, 'currencies']);
        Route::post('/currency', [AdminCrudController::class, 'storeCurrency']);
        Route::post('/currency/{currency}/update', [AdminCrudController::class, 'updateCurrency']);
        Route::post('/currency/{currency}/delete', [AdminCrudController::class, 'destroyCurrency']);
        Route::get('/email-template', [AdminCrudController::class, 'emailTemplates']);
        Route::post('/email-template', [AdminCrudController::class, 'storeEmailTemplate']);
        Route::post('/email-template/{emailTemplate}/update', [AdminCrudController::class, 'updateEmailTemplate']);
        Route::post('/email-template/{emailTemplate}/delete', [AdminCrudController::class, 'destroyEmailTemplate']);
        Route::prefix('school')->group(function () {
            Route::get('/classes', [SchoolManagementController::class, 'classes']);
            Route::post('/classes', [SchoolManagementController::class, 'storeClass']);
            Route::post('/classes/{class}', [SchoolManagementController::class, 'updateClass']);
            Route::post('/classes/{class}/delete', [SchoolManagementController::class, 'destroyClass']);
            Route::get('/attendances', [SchoolManagementController::class, 'attendances']);
            Route::post('/attendances', [SchoolManagementController::class, 'storeAttendance']);
            Route::post('/attendances/batch', [SchoolManagementController::class, 'markAttendanceBatch']);
            Route::get('/exams', [SchoolManagementController::class, 'exams']);
            Route::post('/exams', [SchoolManagementController::class, 'storeExam']);
            Route::post('/exams/{exam}/update', [SchoolManagementController::class, 'updateExam']);
            Route::post('/exams/{exam}/delete', [SchoolManagementController::class, 'destroyExam']);
            Route::get('/results', [SchoolManagementController::class, 'results']);
            Route::post('/results', [SchoolManagementController::class, 'storeResult']);
            Route::post('/results/{result}/update', [SchoolManagementController::class, 'updateResult']);
            Route::post('/results/{result}/delete', [SchoolManagementController::class, 'destroyResult']);
            Route::get('/timetables', [SchoolManagementController::class, 'timetables']);
            Route::post('/timetables', [SchoolManagementController::class, 'storeTimetable']);
            Route::post('/timetables/{timetable}/delete', [SchoolManagementController::class, 'destroyTimetable']);
            Route::get('/parents', [SchoolManagementController::class, 'parents']);
            Route::post('/parents', [SchoolManagementController::class, 'storeParent']);
            Route::post('/parents/{user}/update', [SchoolManagementController::class, 'updateParent']);
            Route::post('/parents/{user}/delete', [SchoolManagementController::class, 'destroyParent']);
        });
        Route::get('/backend-setting', fn() => redirect('/admin/settings?tab=backend'));
        Route::post('/backend-setting', [AdminController::class, 'updateBackendSetting']);
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notificationLog}/read', [NotificationController::class, 'markRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::get('/profile', fn() => view('admin.profile'));
        Route::post('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::get('/localization/country', [AdminCrudController::class, 'countries']);
        Route::post('/localization/country', [AdminCrudController::class, 'storeCountry']);
        Route::post('/localization/country/{country}/update', [AdminCrudController::class, 'updateCountry']);
        Route::post('/localization/country/{country}/delete', [AdminCrudController::class, 'destroyCountry']);
        Route::get('/localization/state', [AdminCrudController::class, 'states']);
        Route::post('/localization/state', [AdminCrudController::class, 'storeState']);
        Route::post('/localization/state/{state}/update', [AdminCrudController::class, 'updateState']);
        Route::post('/localization/state/{state}/delete', [AdminCrudController::class, 'destroyState']);
        Route::get('/localization/city', [AdminCrudController::class, 'cities']);
        Route::post('/localization/city', [AdminCrudController::class, 'storeCity']);
        Route::post('/localization/city/{city}/update', [AdminCrudController::class, 'updateCity']);
        Route::post('/localization/city/{city}/delete', [AdminCrudController::class, 'destroyCity']);
        Route::get('/localization/time-zone', [AdminCrudController::class, 'timezones']);
        Route::post('/localization/time-zone', [AdminCrudController::class, 'storeTimezone']);
        Route::post('/localization/time-zone/{timezone}/update', [AdminCrudController::class, 'updateTimezone']);
        Route::post('/localization/time-zone/{timezone}/delete', [AdminCrudController::class, 'destroyTimezone']);
        Route::get('/icon-providers/icon', [AdminCrudController::class, 'iconProviders']);
        Route::post('/icon-providers/icon', [AdminCrudController::class, 'storeIconProvider']);
        Route::post('/icon-providers/icon/{iconProvider}/update', [AdminCrudController::class, 'updateIconProvider']);
        Route::post('/icon-providers/icon/{iconProvider}/delete', [AdminCrudController::class, 'destroyIconProvider']);
        Route::get('/wishlists', function () {
            $wishlists = \App\Models\Wishlist::with('user', 'course')->latest()->get();
            return view('admin.wishlists', compact('wishlists'));
        });
        Route::get('/noticeboard', [NoticeboardController::class, 'adminIndex']);
        Route::post('/noticeboard', [NoticeboardController::class, 'store']);
        Route::post('/noticeboard/{noticeboard}', [NoticeboardController::class, 'update']);
        Route::post('/noticeboard/{noticeboard}/delete', [NoticeboardController::class, 'destroy']);
    });

    // Instructor Zoom (standalone so route names are zoom.instructor.*)
    Route::middleware(['role:' . User::ROLE_INSTRUCTOR, 'instructor_approved'])->prefix('instructor/zoom')->name('zoom.instructor.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'store'])->name('store');
        Route::get('/calendar', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'calendar'])->name('calendar');
        Route::get('/calendar/ics', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'calendarIcs'])->name('calendar.ics');
        Route::get('/meetings/{meeting}', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'show'])->name('show');
        Route::get('/meetings/{meeting}/edit', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'edit'])->name('edit');
        Route::put('/meetings/{meeting}', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'update'])->name('update');
        Route::post('/meetings/{meeting}/start', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'start'])->name('start');
        Route::post('/meetings/{meeting}/cancel', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'cancel'])->name('cancel');
        Route::post('/meetings/{meeting}/recording', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'toggleRecording'])->name('recording');
        Route::post('/meetings/{meeting}/notify', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'notify'])->name('notify');
        Route::get('/meetings/{meeting}/attendance', [\App\Http\Controllers\Zoom\ZoomAttendanceController::class, 'show'])->name('attendance');
        Route::get('/meetings/{meeting}/attendance/export', [\App\Http\Controllers\Zoom\ZoomAttendanceController::class, 'export'])->name('attendance.export');
    });

    // Admin Zoom (standalone so route names are zoom.admin.*)
    Route::middleware('role:' . User::ROLE_ADMIN . ',' . User::ROLE_STAFF)->prefix('admin/zoom')->name('zoom.admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Zoom\ZoomAdminController::class, 'index'])->name('index');
        Route::get('/settings', [\App\Http\Controllers\Zoom\ZoomAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Zoom\ZoomSettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/test', [\App\Http\Controllers\Zoom\ZoomSettingsController::class, 'test'])->name('settings.test');
        Route::get('/create', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'store'])->name('store');
        Route::get('/calendar', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'calendar'])->name('calendar');
        Route::get('/calendar/ics', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'calendarIcs'])->name('calendar.ics');
        Route::get('/meetings/{meeting}', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'show'])->name('show');
        Route::get('/meetings/{meeting}/edit', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'edit'])->name('edit');
        Route::put('/meetings/{meeting}', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'update'])->name('update');
        Route::post('/meetings/{meeting}/start', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'start'])->name('start');
        Route::post('/meetings/{meeting}/cancel', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'cancel'])->name('cancel');
        Route::post('/meetings/{meeting}/recording', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'toggleRecording'])->name('recording');
        Route::post('/meetings/{meeting}/notify', [\App\Http\Controllers\Zoom\ZoomScheduleController::class, 'notify'])->name('notify');
        Route::get('/meetings/{meeting}/attendance', [\App\Http\Controllers\Zoom\ZoomAttendanceController::class, 'show'])->name('attendance');
        Route::get('/meetings/{meeting}/attendance/export', [\App\Http\Controllers\Zoom\ZoomAttendanceController::class, 'export'])->name('attendance.export');
    });
});



