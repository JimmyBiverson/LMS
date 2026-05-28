<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
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
        return view('dashboard.course-review', compact('reviews'));
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

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            $validated
        );

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
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved!');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return back()->with('success', 'Review deleted!');
    }
}
