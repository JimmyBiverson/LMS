<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use App\Notifications\ReviewApproved;
use App\Notifications\ReviewSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with('course', 'user')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        $completedEnrollments = Enrollment::with('course')
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->get();
        return view('dashboard.course-review', compact('reviews', 'completedEnrollments'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $isEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->exists();

        if (!$isEnrolled) {
            return back()->withErrors(['course_id' => 'You must complete the course before reviewing it.']);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:2000'],
        ]);

        $review = Review::updateOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            $validated
        );

        ReviewSubmitted::send($review);

        return back()->with('success', 'Review submitted successfully! It will be visible after approval.');
    }

    public function instructorReviews(): View
    {
        $courseIds = Course::where('user_id', auth()->id())->pluck('id');
        $reviews = Review::with('user', 'course')
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->get();
        return view('instructor.reviews', compact('reviews'));
    }

    public function orgReviews(): View
    {
        $courseIds = Course::where('user_id', auth()->id())->pluck('id');
        $reviews = Review::with('user', 'course')
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->get();
        return view('org.reviews', compact('reviews'));
    }

    public function adminReviews(): View
    {
        $reviews = Review::with('user', 'course')->latest()->get();
        return view('admin.review.course-review', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin() || $user->isStaff();
        $isOwner = $review->course && $review->course->user_id === $user->id;

        if (!$isAdmin && !$isOwner) {
            abort(403, 'You are not authorized to approve this review.');
        }

        $review->update(['is_approved' => true]);

        ReviewApproved::send($review);

        return back()->with('success', 'Review approved!');
    }

    public function reject(Review $review): RedirectResponse
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin() || $user->isStaff();
        $isOwner = $review->course && $review->course->user_id === $user->id;

        if (!$isAdmin && !$isOwner) {
            abort(403, 'You are not authorized to reject this review.');
        }

        $review->update(['is_approved' => false]);

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin() || $user->isStaff();
        $isOwner = $review->course && $review->course->user_id === $user->id;

        if (!$isAdmin && !$isOwner) {
            abort(403, 'You are not authorized to delete this review.');
        }

        $review->delete();
        return back()->with('success', 'Review deleted!');
    }
}
