<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $q = '%' . $query . '%';

        $courses = Course::where('title', 'like', $q)
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'type' => 'Course',
                'title' => $c->title,
                'subtitle' => $c->category ?? 'Course',
                'url' => '/admin/course',
            ]);

        $users = User::where(function ($w) use ($q) {
            $w->where('name', 'like', $q)->orWhere('email', 'like', $q);
        })
            ->whereIn('role', ['student', 'instructor'])
            ->limit(5)
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'type' => ucfirst($u->role),
                'title' => $u->name,
                'subtitle' => $u->email,
                'url' => $u->role === 'instructor' ? '/admin/instructors' : '/admin/students',
            ]);

        $lessons = Lesson::where('title', 'like', $q)
            ->limit(5)
            ->get()
            ->map(fn($l) => [
                'id' => $l->id,
                'type' => 'Lesson',
                'title' => $l->title,
                'subtitle' => $l->course?->title ?? 'Lesson',
                'url' => '/admin/course',
            ]);

        $results = collect()
            ->merge($courses)
            ->merge($users)
            ->merge($lessons)
            ->take(15)
            ->values()
            ->toArray();

        return response()->json(['results' => $results]);
    }
}
