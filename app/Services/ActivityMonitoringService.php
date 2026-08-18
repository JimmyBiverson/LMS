<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Activity Monitoring Service
 * 
 * Tracks user activities for admin monitoring including activity logging with metadata,
 * filtering and pagination, activity report generation, and system statistics.
 */
class ActivityMonitoringService
{
    /**
     * Cache configuration
     */
    private const CACHE_TTL = 900; // 15 minutes
    private const CACHE_KEY_PREFIX = 'activity_monitoring:';

    /**
     * Default pagination limits
     */
    private const DEFAULT_LIMIT = 50;
    private const MAX_LIMIT = 500;

    /**
     * Log a user activity with metadata.
     * 
     * Creates an activity log entry capturing the action performed, optional subject,
     * metadata, IP address, and user agent for comprehensive activity tracking.
     * 
     * @param User $user User performing the activity
     * @param string $action Action identifier (e.g., "course.viewed", "assignment.submitted")
     * @param Model|null $subject Optional subject model (polymorphic)
     * @param array $metadata Additional context data
     * @return ActivityLog Created activity log
     * @throws Exception If logging fails
     */
    public function logActivity(
        User $user,
        string $action,
        ?Model $subject = null,
        array $metadata = []
    ): ActivityLog {
        try {
            $logData = [
                'user_id' => $user->id,
                'action' => $action,
                'metadata' => $metadata,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];

            // Add polymorphic subject if provided
            if ($subject) {
                $logData['subject_type'] = get_class($subject);
                $logData['subject_id'] = $subject->id;
            }

            $activityLog = ActivityLog::create($logData);

            // Update user's last activity timestamp
            $user->update(['last_activity_at' => now()]);

            // Clear cached statistics since new activity was logged
            $this->clearActivityCache();

            Log::debug('Activity logged', [
                'user_id' => $user->id,
                'action' => $action,
                'activity_log_id' => $activityLog->id
            ]);

            return $activityLog;
        } catch (Exception $e) {
            Log::error('Failed to log activity', [
                'user_id' => $user->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Get recent activities with filtering and pagination.
     * 
     * Retrieves activity logs with support for filtering by user, action, date range,
     * and subject type. Results are ordered by most recent first.
     * 
     * @param array $filters Filter criteria
     *   - user_id: Filter by specific user
     *   - action: Filter by action type (supports wildcards with %)
     *   - subject_type: Filter by subject model type
     *   - subject_id: Filter by specific subject ID
     *   - start_date: Filter activities from this date
     *   - end_date: Filter activities until this date
     *   - ip_address: Filter by IP address
     * @param int $limit Maximum number of results (default: 50, max: 500)
     * @return Collection Collection of ActivityLog models with relationships
     */
    public function getRecentActivities(array $filters = [], int $limit = self::DEFAULT_LIMIT): Collection
    {
        try {
            // Enforce maximum limit
            $limit = min($limit, self::MAX_LIMIT);

            $query = ActivityLog::query()
                ->with(['user:id,name,email,role', 'subject']);

            // Apply filters
            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }

            if (isset($filters['action'])) {
                // Support wildcard searches with LIKE
                if (str_contains($filters['action'], '%')) {
                    $query->where('action', 'like', $filters['action']);
                } else {
                    $query->where('action', $filters['action']);
                }
            }

            if (isset($filters['subject_type'])) {
                $query->where('subject_type', $filters['subject_type']);
            }

            if (isset($filters['subject_id'])) {
                $query->where('subject_id', $filters['subject_id']);
            }

            if (isset($filters['start_date'])) {
                $query->where('created_at', '>=', Carbon::parse($filters['start_date']));
            }

            if (isset($filters['end_date'])) {
                $query->where('created_at', '<=', Carbon::parse($filters['end_date'])->endOfDay());
            }

            if (isset($filters['ip_address'])) {
                $query->where('ip_address', $filters['ip_address']);
            }

            // Order by most recent first
            $activities = $query->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            Log::debug('Retrieved recent activities', [
                'filters' => $filters,
                'count' => $activities->count(),
                'limit' => $limit
            ]);

            return $activities;
        } catch (Exception $e) {
            Log::error('Failed to retrieve recent activities', [
                'filters' => $filters,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to retrieve activities: ' . $e->getMessage());
        }
    }

    /**
     * Generate activity report for a specific user.
     * 
     * Creates a comprehensive report of user activities within a date range,
     * including activity counts by type, timeline data, and summary statistics.
     * 
     * @param User $user User to generate report for
     * @param Carbon $startDate Report start date
     * @param Carbon $endDate Report end date
     * @return array Report data with summary, activities, and breakdown
     */
    public function getUserActivityReport(User $user, Carbon $startDate, Carbon $endDate): array
    {
        try {
            $cacheKey = $this->getUserReportCacheKey($user->id, $startDate, $endDate);

            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $startDate, $endDate) {
                // Get all activities in date range
                $activities = ActivityLog::where('user_id', $user->id)
                    ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Group activities by action type
                $activityBreakdown = $activities->groupBy('action')
                    ->map(fn($group) => $group->count())
                    ->sortDesc();

                // Group activities by date for timeline
                $activityTimeline = $activities->groupBy(function ($activity) {
                    return $activity->created_at->format('Y-m-d');
                })->map(fn($group) => $group->count());

                // Get most active day
                $mostActiveDay = $activityTimeline->sortDesc()->keys()->first();

                // Calculate average activities per day
                $daysDiff = max(1, $startDate->diffInDays($endDate) + 1);
                $averagePerDay = round($activities->count() / $daysDiff, 2);

                // Get unique IP addresses
                $uniqueIps = $activities->pluck('ip_address')->unique()->filter()->values();

                // Get subject types breakdown
                $subjectBreakdown = $activities->whereNotNull('subject_type')
                    ->groupBy('subject_type')
                    ->map(fn($group) => [
                        'count' => $group->count(),
                        'type' => class_basename($group->first()->subject_type)
                    ]);

                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'period' => [
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                        'days' => $daysDiff,
                    ],
                    'summary' => [
                        'total_activities' => $activities->count(),
                        'unique_actions' => $activityBreakdown->count(),
                        'average_per_day' => $averagePerDay,
                        'most_active_day' => $mostActiveDay,
                        'unique_ip_addresses' => $uniqueIps->count(),
                    ],
                    'activity_breakdown' => $activityBreakdown->toArray(),
                    'activity_timeline' => $activityTimeline->toArray(),
                    'subject_breakdown' => $subjectBreakdown->toArray(),
                    'unique_ips' => $uniqueIps->take(10)->toArray(), // Limit to 10 for privacy
                    'recent_activities' => $activities->take(20)->map(function ($activity) {
                        return [
                            'action' => $activity->action,
                            'subject_type' => $activity->subject_type ? class_basename($activity->subject_type) : null,
                            'subject_id' => $activity->subject_id,
                            'created_at' => $activity->created_at->toDateTimeString(),
                            'metadata' => $activity->metadata,
                        ];
                    })->toArray(),
                ];
            });
        } catch (Exception $e) {
            Log::error('Failed to generate user activity report', [
                'user_id' => $user->id,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to generate activity report: ' . $e->getMessage());
        }
    }

    /**
     * Get system-wide activity statistics.
     * 
     * Provides aggregate statistics for admin monitoring including total activities,
     * active users, popular actions, and recent activity trends.
     * 
     * @return array System statistics data
     */
    public function getSystemActivityStats(): array
    {
        try {
            $cacheKey = self::CACHE_KEY_PREFIX . 'system_stats';

            return Cache::remember($cacheKey, self::CACHE_TTL, function () {
                $now = Carbon::now();
                $today = $now->copy()->startOfDay();
                $lastWeek = $now->copy()->subWeek();
                $lastMonth = $now->copy()->subMonth();

                // Total activities
                $totalActivities = ActivityLog::count();

                // Activities today
                $activitiestoday = ActivityLog::where('created_at', '>=', $today)->count();

                // Activities last 7 days
                $activitiesLastWeek = ActivityLog::where('created_at', '>=', $lastWeek)->count();

                // Activities last 30 days
                $activitiesLastMonth = ActivityLog::where('created_at', '>=', $lastMonth)->count();

                // Active users (last 24 hours)
                $activeUsersToday = User::where('last_activity_at', '>=', $today)->count();

                // Active users (last 7 days)
                $activeUsersLastWeek = User::where('last_activity_at', '>=', $lastWeek)->count();

                // Most popular actions (last 7 days)
                $popularActions = ActivityLog::select('action', DB::raw('count(*) as count'))
                    ->where('created_at', '>=', $lastWeek)
                    ->groupBy('action')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(fn($item) => [
                        'action' => $item->action,
                        'count' => $item->count
                    ]);

                // Most active users (last 7 days)
                $mostActiveUsers = ActivityLog::select('user_id', DB::raw('count(*) as activity_count'))
                    ->where('created_at', '>=', $lastWeek)
                    ->groupBy('user_id')
                    ->orderBy('activity_count', 'desc')
                    ->limit(10)
                    ->with('user:id,name,email,role')
                    ->get()
                    ->map(fn($item) => [
                        'user_id' => $item->user_id,
                        'user_name' => $item->user->name ?? 'Unknown',
                        'user_email' => $item->user->email ?? 'Unknown',
                        'user_role' => $item->user->role ?? 'Unknown',
                        'activity_count' => $item->activity_count
                    ]);

                // Activity by day (last 7 days)
                // Use database-agnostic date extraction
                $activityByDay = ActivityLog::where('created_at', '>=', $lastWeek)
                    ->select(DB::raw($this->getDateSql() . ' as date'), DB::raw('count(*) as count'))
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get()
                    ->pluck('count', 'date');

                // Activity by hour (last 24 hours)
                // Use database-agnostic hour extraction
                $activityByHour = ActivityLog::where('created_at', '>=', $today)
                    ->select(DB::raw($this->getHourSql() . ' as hour'), DB::raw('count(*) as count'))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get()
                    ->pluck('count', 'hour');

                // Subject type distribution (last 7 days)
                $subjectTypeDistribution = ActivityLog::select('subject_type', DB::raw('count(*) as count'))
                    ->whereNotNull('subject_type')
                    ->where('created_at', '>=', $lastWeek)
                    ->groupBy('subject_type')
                    ->orderBy('count', 'desc')
                    ->get()
                    ->map(fn($item) => [
                        'type' => class_basename($item->subject_type),
                        'full_type' => $item->subject_type,
                        'count' => $item->count
                    ]);

                return [
                    'overview' => [
                        'total_activities' => $totalActivities,
                        'activities_today' => $activitiestoday,
                        'activities_last_week' => $activitiesLastWeek,
                        'activities_last_month' => $activitiesLastMonth,
                        'active_users_today' => $activeUsersToday,
                        'active_users_last_week' => $activeUsersLastWeek,
                    ],
                    'popular_actions' => $popularActions->toArray(),
                    'most_active_users' => $mostActiveUsers->toArray(),
                    'activity_by_day' => $activityByDay->toArray(),
                    'activity_by_hour' => $activityByHour->toArray(),
                    'subject_type_distribution' => $subjectTypeDistribution->toArray(),
                    'generated_at' => $now->toDateTimeString(),
                ];
            });
        } catch (Exception $e) {
            Log::error('Failed to generate system activity stats', [
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to generate system statistics: ' . $e->getMessage());
        }
    }

    /**
     * Get database-agnostic SQL for extracting DATE from a timestamp column.
     */
    private function getDateSql(): string
    {
        $driver = DB::connection()->getDriverName();
        return match ($driver) {
            'sqlite' => "DATE(created_at)",
            'pgsql' => "DATE(created_at)",
            default => "DATE(created_at)",
        };
    }

    /**
     * Get database-agnostic SQL for extracting HOUR from a timestamp column.
     */
    private function getHourSql(): string
    {
        $driver = DB::connection()->getDriverName();
        return match ($driver) {
            'sqlite' => "CAST(strftime('%H', created_at) AS INTEGER)",
            'pgsql' => "EXTRACT(HOUR FROM created_at)",
            default => "HOUR(created_at)",
        };
    }

    /**
     * Clear activity-related cache.
     * 
     * Invalidates cached statistics and reports. Called when new activities are logged.
     * 
     * @return void
     */
    private function clearActivityCache(): void
    {
        try {
            // Clear system stats cache
            Cache::forget(self::CACHE_KEY_PREFIX . 'system_stats');

            // Clear user report caches (pattern-based)
            // Note: This is a simplified approach. In production, consider using cache tags
            // or a more sophisticated cache invalidation strategy.
            Log::debug('Activity cache cleared');
        } catch (Exception $e) {
            Log::warning('Failed to clear activity cache', [
                'error' => $e->getMessage()
            ]);
            // Don't throw - cache clearing failure shouldn't break the application
        }
    }

    /**
     * Generate cache key for user activity report.
     * 
     * @param int $userId User ID
     * @param Carbon $startDate Start date
     * @param Carbon $endDate End date
     * @return string Cache key
     */
    private function getUserReportCacheKey(int $userId, Carbon $startDate, Carbon $endDate): string
    {
        return self::CACHE_KEY_PREFIX . "user_report:{$userId}:{$startDate->format('Y-m-d')}:{$endDate->format('Y-m-d')}";
    }

    /**
     * Get activity count for a user within a date range.
     * 
     * Quick method to get just the count without full report generation.
     * 
     * @param User $user User to count activities for
     * @param Carbon|null $startDate Start date (null for all time)
     * @param Carbon|null $endDate End date (null for now)
     * @return int Activity count
     */
    public function getUserActivityCount(User $user, ?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        $query = ActivityLog::where('user_id', $user->id);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate->endOfDay());
        }

        return $query->count();
    }

    /**
     * Get recent activities for a specific subject.
     * 
     * Retrieves activities related to a specific model (e.g., all activities for a course).
     * 
     * @param Model $subject Subject model to get activities for
     * @param int $limit Maximum number of results
     * @return Collection Collection of ActivityLog models
     */
    public function getSubjectActivities(Model $subject, int $limit = 50): Collection
    {
        return $this->getRecentActivities([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
        ], $limit);
    }

    /**
     * Get activity summary for multiple users.
     * 
     * Useful for comparing user engagement across a cohort or organization.
     * 
     * @param array $userIds Array of user IDs
     * @param Carbon $startDate Start date
     * @param Carbon $endDate End date
     * @return Collection Summary data keyed by user ID
     */
    public function getUsersBatchSummary(array $userIds, Carbon $startDate, Carbon $endDate): Collection
    {
        try {
            $summaries = ActivityLog::select('user_id', DB::raw('count(*) as activity_count'))
                ->whereIn('user_id', $userIds)
                ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                ->groupBy('user_id')
                ->with('user:id,name,email,role')
                ->get()
                ->keyBy('user_id')
                ->map(fn($item) => [
                    'user_id' => $item->user_id,
                    'user_name' => $item->user->name ?? 'Unknown',
                    'activity_count' => $item->activity_count,
                ]);

            return $summaries;
        } catch (Exception $e) {
            Log::error('Failed to generate batch user summaries', [
                'user_ids_count' => count($userIds),
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to generate batch summaries: ' . $e->getMessage());
        }
    }
}
