<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isOrganization()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $isOrg = $user->isOrganization();
        $courseIds = $isOrg ? Course::where('user_id', $user->id)->pluck('id') : null;

        $totalStudents = User::where('role', 'student')->count();
        $totalCourses = $isOrg ? Course::where('user_id', $user->id)->count() : Course::count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalEnrollments = $isOrg
            ? Enrollment::whereIn('course_id', $courseIds)->count()
            : Enrollment::count();
        $totalRevenue = $isOrg
            ? Enrollment::whereIn('course_id', $courseIds)->sum('amount_paid')
            : Enrollment::sum('amount_paid');

        $monthlyEnrollments = Enrollment::select(
            DB::raw('strftime(\'%Y-%m\', created_at) as month'),
            DB::raw('count(*) as total')
        )
            ->when($isOrg, fn($q) => $q->whereIn('course_id', $courseIds))
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        $popularCourses = Course::withCount('enrollments')
            ->when($isOrg, fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('enrollments_count')
            ->take(10)
            ->get(['id', 'title', 'enrollments_count']);

        return response()->json([
            'totals' => compact('totalStudents', 'totalCourses', 'totalInstructors', 'totalEnrollments', 'totalRevenue'),
            'monthlyEnrollments' => $monthlyEnrollments,
            'popularCourses' => $popularCourses,
        ]);
    }
}
