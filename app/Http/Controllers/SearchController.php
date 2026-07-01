<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = $request->validate(['q' => 'required|string|max:100'])['q'];

        $courses = Course::with('level', 'lessons')
            ->withCount(['enrollments', 'quizzes', 'assignments'])
            ->where('status', 'Active')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->get();

        $blogs = Blog::with('category', 'author')
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest()
            ->get();

        return view('search', compact('query', 'courses', 'blogs'));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->validate(['q' => 'required|string|max:100'])['q'];

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $q = "%{$query}%";

        $courses = Course::where('status', 'Active')
            ->where('title', 'like', $q)
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'type' => 'Course',
                'title' => $c->title,
                'subtitle' => $c->category ?? 'Course',
                'url' => '/courses/' . ($c->slug ?? $c->id),
            ]);

        $blogs = Blog::where('status', 'published')
            ->where('title', 'like', $q)
            ->limit(3)
            ->get()
            ->map(fn ($b) => [
                'type' => 'Blog',
                'title' => $b->title,
                'subtitle' => 'Blog',
                'url' => '/blogs/' . ($b->slug ?? $b->id),
            ]);

        $instructors = User::where('role', User::ROLE_INSTRUCTOR)
            ->where('name', 'like', $q)
            ->limit(3)
            ->get()
            ->map(fn ($u) => [
                'type' => 'Instructor',
                'title' => $u->name,
                'subtitle' => $u->email,
                'url' => '/instructors',
            ]);

        $results = collect()
            ->merge($courses)
            ->merge($blogs)
            ->merge($instructors)
            ->take(10)
            ->values()
            ->toArray();

        return response()->json(['results' => $results]);
    }
}
