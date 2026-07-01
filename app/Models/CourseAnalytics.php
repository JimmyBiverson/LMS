<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Course Analytics Model
 * 
 * Manages aggregated analytics data for courses. This model stores daily
 * statistics for course performance including views, enrollments, completions,
 * ratings, and revenue.
 * 
 * @property int $id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $views_count
 * @property int $enrollments_count
 * @property int $completions_count
 * @property float|null $average_rating
 * @property float $total_revenue
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * 
 * @property-read \App\Models\Course $course
 */
class CourseAnalytics extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'date',
        'views_count',
        'enrollments_count',
        'completions_count',
        'average_rating',
        'total_revenue',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'views_count' => 'integer',
            'enrollments_count' => 'integer',
            'completions_count' => 'integer',
            'average_rating' => 'decimal:2',
            'total_revenue' => 'decimal:2',
        ];
    }

    /**
     * Get the course that this analytics record belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get aggregated statistics for a course within a date range.
     *
     * @param int $courseId
     * @param \Illuminate\Support\Carbon $startDate
     * @param \Illuminate\Support\Carbon $endDate
     * @return array
     */
    public static function getAggregatedStats(int $courseId, Carbon $startDate, Carbon $endDate): array
    {
        $stats = static::where('course_id', $courseId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                SUM(views_count) as total_views,
                SUM(enrollments_count) as total_enrollments,
                SUM(completions_count) as total_completions,
                AVG(average_rating) as avg_rating,
                SUM(total_revenue) as total_revenue
            ')
            ->first();

        return [
            'total_views' => (int) ($stats->total_views ?? 0),
            'total_enrollments' => (int) ($stats->total_enrollments ?? 0),
            'total_completions' => (int) ($stats->total_completions ?? 0),
            'average_rating' => $stats->avg_rating ? round($stats->avg_rating, 2) : null,
            'total_revenue' => (float) ($stats->total_revenue ?? 0),
            'completion_rate' => $stats->total_enrollments > 0 
                ? round(($stats->total_completions / $stats->total_enrollments) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get daily statistics for a course within a date range.
     *
     * @param int $courseId
     * @param \Illuminate\Support\Carbon $startDate
     * @param \Illuminate\Support\Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getDailyStats(int $courseId, Carbon $startDate, Carbon $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('course_id', $courseId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
    }

    /**
     * Get top performing courses by a specific metric.
     *
     * @param string $metric Metric to rank by: views_count, enrollments_count, completions_count, total_revenue
     * @param int $limit Number of courses to return
     * @param \Illuminate\Support\Carbon|null $startDate Optional start date filter
     * @param \Illuminate\Support\Carbon|null $endDate Optional end date filter
     * @return \Illuminate\Support\Collection
     */
    public static function getTopCourses(string $metric = 'enrollments_count', int $limit = 10, ?Carbon $startDate = null, ?Carbon $endDate = null): \Illuminate\Support\Collection
    {
        $query = static::select('course_id')
            ->selectRaw("SUM({$metric}) as total");

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->groupBy('course_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with('course')
            ->get()
            ->map(function ($analytics) use ($metric) {
                return [
                    'course' => $analytics->course,
                    'total' => $analytics->total,
                    'metric' => $metric,
                ];
            });
    }

    /**
     * Calculate and store analytics for a specific course and date.
     *
     * @param int $courseId
     * @param \Illuminate\Support\Carbon $date
     * @return static
     */
    public static function calculateForDate(int $courseId, Carbon $date): static
    {
        // Count views from activity logs
        $viewsCount = ActivityLog::where('subject_type', Course::class)
            ->where('subject_id', $courseId)
            ->where('action', 'course.viewed')
            ->whereDate('created_at', $date)
            ->count();

        // Count enrollments for the date
        $enrollmentsCount = Enrollment::where('course_id', $courseId)
            ->whereDate('created_at', $date)
            ->count();

        // Count completions for the date
        $completionsCount = Enrollment::where('course_id', $courseId)
            ->where('status', 'completed')
            ->whereDate('completed_at', $date)
            ->count();

        // Calculate average rating
        $course = Course::find($courseId);
        $averageRating = $course ? $course->averageRating() : null;

        // Calculate total revenue for the date (from enrollments with payments)
        $totalRevenue = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.course_id', $courseId)
            ->whereDate('enrollments.created_at', $date)
            ->where('courses.payment_type', '!=', 'free')
            ->sum(DB::raw('COALESCE(courses.sale_price, courses.price, 0)'));

        return static::updateOrCreate(
            [
                'course_id' => $courseId,
                'date' => $date,
            ],
            [
                'views_count' => $viewsCount,
                'enrollments_count' => $enrollmentsCount,
                'completions_count' => $completionsCount,
                'average_rating' => $averageRating,
                'total_revenue' => $totalRevenue ?? 0,
            ]
        );
    }

    /**
     * Get monthly trend for a specific metric.
     *
     * @param int $courseId
     * @param string $metric
     * @param int $months Number of months to look back
     * @return \Illuminate\Support\Collection
     */
    public static function getMonthlyTrend(int $courseId, string $metric = 'enrollments_count', int $months = 6): \Illuminate\Support\Collection
    {
        $startDate = Carbon::now()->subMonths($months)->startOfMonth();
        
        return static::where('course_id', $courseId)
            ->where('date', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM({$metric}) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Increment views count for today.
     *
     * @param int $courseId
     * @return void
     */
    public static function incrementViews(int $courseId): void
    {
        $today = Carbon::today();
        
        static::firstOrCreate(
            [
                'course_id' => $courseId,
                'date' => $today,
            ],
            [
                'views_count' => 0,
                'enrollments_count' => 0,
                'completions_count' => 0,
                'total_revenue' => 0,
            ]
        )->increment('views_count');
    }

    /**
     * Increment enrollments count for today.
     *
     * @param int $courseId
     * @return void
     */
    public static function incrementEnrollments(int $courseId): void
    {
        $today = Carbon::today();
        
        static::firstOrCreate(
            [
                'course_id' => $courseId,
                'date' => $today,
            ],
            [
                'views_count' => 0,
                'enrollments_count' => 0,
                'completions_count' => 0,
                'total_revenue' => 0,
            ]
        )->increment('enrollments_count');
    }

    /**
     * Increment completions count for today.
     *
     * @param int $courseId
     * @return void
     */
    public static function incrementCompletions(int $courseId): void
    {
        $today = Carbon::today();
        
        static::firstOrCreate(
            [
                'course_id' => $courseId,
                'date' => $today,
            ],
            [
                'views_count' => 0,
                'enrollments_count' => 0,
                'completions_count' => 0,
                'total_revenue' => 0,
            ]
        )->increment('completions_count');
    }
}
