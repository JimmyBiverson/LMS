<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseDiscussion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Course $course): View
    {
        $discussions = CourseDiscussion::with('user', 'replies.user')
            ->where('course_id', $course->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(20);
        return view('courses.discussions', compact('course', 'discussions'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $request->validate(['body' => 'required|string|max:2000']);

        CourseDiscussion::create([
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Discussion posted!');
    }

    public function reply(Request $request, Course $course, CourseDiscussion $discussion): RedirectResponse
    {
        if ($discussion->parent_id !== null) {
            abort(400, 'Cannot reply to a reply.');
        }

        $request->validate(['body' => 'required|string|max:2000']);

        CourseDiscussion::create([
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
            'parent_id' => $discussion->id,
        ]);

        return back()->with('success', 'Reply posted!');
    }

    public function destroy(Course $course, CourseDiscussion $discussion): RedirectResponse
    {
        if ($discussion->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $discussion->delete();
        return back()->with('success', 'Discussion deleted.');
    }
}
