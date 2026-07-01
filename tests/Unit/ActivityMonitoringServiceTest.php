<?php

namespace Tests\Unit;

use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\User;
use App\Services\ActivityMonitoringService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ActivityMonitoringServiceTest extends TestCase
{
    use RefreshDatabase;

    private ActivityMonitoringService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ActivityMonitoringService();
        $this->user = User::factory()->create();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    /**
     * Test logging activity without subject
     */
    public function test_log_activity_without_subject(): void
    {
        $action = 'user.login';
        $metadata = ['login_method' => 'email'];

        $log = $this->service->logActivity($this->user, $action, null, $metadata);

        $this->assertInstanceOf(ActivityLog::class, $log);
        $this->assertEquals($this->user->id, $log->user_id);
        $this->assertEquals($action, $log->action);
        $this->assertEquals($metadata, $log->metadata);
        $this->assertNull($log->subject_type);
        $this->assertNull($log->subject_id);
        $this->assertNotNull($log->ip_address);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->user->id,
            'action' => $action,
        ]);
    }

    /**
     * Test logging activity with subject
     */
    public function test_log_activity_with_subject(): void
    {
        $course = Course::factory()->create();
        $action = 'course.viewed';
        $metadata = ['duration_seconds' => 45];

        $log = $this->service->logActivity($this->user, $action, $course, $metadata);

        $this->assertInstanceOf(ActivityLog::class, $log);
        $this->assertEquals($this->user->id, $log->user_id);
        $this->assertEquals($action, $log->action);
        $this->assertEquals(Course::class, $log->subject_type);
        $this->assertEquals($course->id, $log->subject_id);
        $this->assertEquals($metadata, $log->metadata);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->user->id,
            'action' => $action,
            'subject_type' => Course::class,
            'subject_id' => $course->id,
        ]);
    }

    /**
     * Test that logging activity updates user's last_activity_at
     */
    public function test_log_activity_updates_user_last_activity(): void
    {
        $originalLastActivity = $this->user->last_activity_at;

        $this->service->logActivity($this->user, 'test.action');

        $this->user->refresh();
        $this->assertNotEquals($originalLastActivity, $this->user->last_activity_at);
        $this->assertNotNull($this->user->last_activity_at);
    }

    /**
     * Test getting recent activities without filters
     */
    public function test_get_recent_activities_without_filters(): void
    {
        // Create multiple activities
        ActivityLog::factory()->count(10)->create(['user_id' => $this->user->id]);
        ActivityLog::factory()->count(5)->create(); // Different user

        $activities = $this->service->getRecentActivities();

        $this->assertCount(15, $activities);
        // Verify ordering by most recent
        $this->assertTrue($activities->first()->created_at->gte($activities->last()->created_at));
    }

    /**
     * Test getting recent activities with user filter
     */
    public function test_get_recent_activities_with_user_filter(): void
    {
        ActivityLog::factory()->count(5)->create(['user_id' => $this->user->id]);
        ActivityLog::factory()->count(3)->create(); // Different users

        $activities = $this->service->getRecentActivities(['user_id' => $this->user->id]);

        $this->assertCount(5, $activities);
        $activities->each(fn($log) => $this->assertEquals($this->user->id, $log->user_id));
    }

    /**
     * Test getting recent activities with action filter
     */
    public function test_get_recent_activities_with_action_filter(): void
    {
        ActivityLog::factory()->count(3)->create(['action' => 'course.viewed']);
        ActivityLog::factory()->count(2)->create(['action' => 'assignment.submitted']);

        $activities = $this->service->getRecentActivities(['action' => 'course.viewed']);

        $this->assertCount(3, $activities);
        $activities->each(fn($log) => $this->assertEquals('course.viewed', $log->action));
    }

    /**
     * Test getting recent activities with wildcard action filter
     */
    public function test_get_recent_activities_with_wildcard_action(): void
    {
        ActivityLog::factory()->create(['action' => 'course.viewed']);
        ActivityLog::factory()->create(['action' => 'course.enrolled']);
        ActivityLog::factory()->create(['action' => 'assignment.submitted']);

        $activities = $this->service->getRecentActivities(['action' => 'course.%']);

        $this->assertCount(2, $activities);
        $activities->each(fn($log) => $this->assertStringStartsWith('course.', $log->action));
    }

    /**
     * Test getting recent activities with date range filter
     */
    public function test_get_recent_activities_with_date_range(): void
    {
        $yesterday = Carbon::yesterday();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        ActivityLog::factory()->create(['created_at' => $yesterday]);
        ActivityLog::factory()->count(2)->create(['created_at' => $today]);
        ActivityLog::factory()->create(['created_at' => $tomorrow]);

        $activities = $this->service->getRecentActivities([
            'start_date' => $today,
            'end_date' => $today,
        ]);

        $this->assertCount(2, $activities);
    }

    /**
     * Test getting recent activities with subject type filter
     */
    public function test_get_recent_activities_with_subject_type_filter(): void
    {
        $course = Course::factory()->create();
        ActivityLog::factory()->count(2)->create([
            'subject_type' => Course::class,
            'subject_id' => $course->id,
        ]);
        ActivityLog::factory()->count(3)->create([
            'subject_type' => User::class,
            'subject_id' => $this->user->id,
        ]);

        $activities = $this->service->getRecentActivities(['subject_type' => Course::class]);

        $this->assertCount(2, $activities);
        $activities->each(fn($log) => $this->assertEquals(Course::class, $log->subject_type));
    }

    /**
     * Test getting recent activities with limit
     */
    public function test_get_recent_activities_respects_limit(): void
    {
        ActivityLog::factory()->count(20)->create();

        $activities = $this->service->getRecentActivities([], 10);

        $this->assertCount(10, $activities);
    }

    /**
     * Test getting recent activities enforces maximum limit
     */
    public function test_get_recent_activities_enforces_max_limit(): void
    {
        ActivityLog::factory()->count(600)->create();

        // Try to request more than max limit (500)
        $activities = $this->service->getRecentActivities([], 600);

        $this->assertCount(500, $activities);
    }

    /**
     * Test getting user activity report
     */
    public function test_get_user_activity_report(): void
    {
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        // Create activities with different actions
        ActivityLog::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'action' => 'course.viewed',
            'created_at' => Carbon::now()->subDays(2),
        ]);
        ActivityLog::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'action' => 'assignment.submitted',
            'created_at' => Carbon::now()->subDays(3),
        ]);
        ActivityLog::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'action' => 'quiz.completed',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        $report = $this->service->getUserActivityReport($this->user, $startDate, $endDate);

        $this->assertIsArray($report);
        $this->assertArrayHasKey('user', $report);
        $this->assertArrayHasKey('period', $report);
        $this->assertArrayHasKey('summary', $report);
        $this->assertArrayHasKey('activity_breakdown', $report);
        $this->assertArrayHasKey('activity_timeline', $report);
        $this->assertArrayHasKey('recent_activities', $report);

        // Verify user data
        $this->assertEquals($this->user->id, $report['user']['id']);

        // Verify summary
        $this->assertEquals(10, $report['summary']['total_activities']);
        $this->assertEquals(3, $report['summary']['unique_actions']);

        // Verify activity breakdown
        $this->assertArrayHasKey('course.viewed', $report['activity_breakdown']);
        $this->assertEquals(5, $report['activity_breakdown']['course.viewed']);
    }

    /**
     * Test getting user activity report caches results
     */
    public function test_get_user_activity_report_uses_cache(): void
    {
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        ActivityLog::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // First call should cache
        $report1 = $this->service->getUserActivityReport($this->user, $startDate, $endDate);

        // Create more activities
        ActivityLog::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Second call should return cached result
        $report2 = $this->service->getUserActivityReport($this->user, $startDate, $endDate);

        $this->assertEquals($report1['summary']['total_activities'], $report2['summary']['total_activities']);
        $this->assertEquals(5, $report2['summary']['total_activities']); // Still 5, not 8
    }

    /**
     * Test getting system activity stats
     */
    public function test_get_system_activity_stats(): void
    {
        // Create activities today
        ActivityLog::factory()->count(5)->create(['created_at' => Carbon::now()]);

        // Create activities in the past week
        ActivityLog::factory()->count(3)->create(['created_at' => Carbon::now()->subDays(3)]);

        // Create activities beyond last week
        ActivityLog::factory()->count(2)->create(['created_at' => Carbon::now()->subDays(10)]);

        $stats = $this->service->getSystemActivityStats();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('overview', $stats);
        $this->assertArrayHasKey('popular_actions', $stats);
        $this->assertArrayHasKey('most_active_users', $stats);
        $this->assertArrayHasKey('activity_by_day', $stats);
        $this->assertArrayHasKey('activity_by_hour', $stats);
        $this->assertArrayHasKey('subject_type_distribution', $stats);

        // Verify overview counts
        $this->assertEquals(10, $stats['overview']['total_activities']);
        $this->assertEquals(5, $stats['overview']['activities_today']);
        $this->assertEquals(8, $stats['overview']['activities_last_week']);
    }

    /**
     * Test getting system activity stats caches results
     */
    public function test_get_system_activity_stats_uses_cache(): void
    {
        ActivityLog::factory()->count(5)->create([
            'created_at' => Carbon::now(),
        ]);

        // First call should cache
        $stats1 = $this->service->getSystemActivityStats();

        // Create more activities
        ActivityLog::factory()->count(3)->create([
            'created_at' => Carbon::now(),
        ]);

        // Second call should return cached result
        $stats2 = $this->service->getSystemActivityStats();

        $this->assertEquals($stats1['overview']['total_activities'], $stats2['overview']['total_activities']);
        $this->assertEquals(5, $stats2['overview']['total_activities']); // Still 5, not 8
    }

    /**
     * Test logging activity clears cache
     */
    public function test_log_activity_clears_cache(): void
    {
        // Generate and cache stats
        ActivityLog::factory()->count(5)->create([
            'created_at' => Carbon::now(),
        ]);
        $stats1 = $this->service->getSystemActivityStats();
        $this->assertEquals(5, $stats1['overview']['total_activities']);

        // Log new activity (should clear cache)
        $this->service->logActivity($this->user, 'test.action');

        // Get stats again (should recalculate)
        Cache::forget('activity_monitoring:system_stats');
        $stats2 = $this->service->getSystemActivityStats();

        // After clearing cache manually, new stats should reflect the new activity
        $this->assertEquals(6, $stats2['overview']['total_activities']);
    }

    /**
     * Test getting user activity count
     */
    public function test_get_user_activity_count(): void
    {
        ActivityLog::factory()->count(8)->create(['user_id' => $this->user->id]);
        ActivityLog::factory()->count(3)->create(); // Different user

        $count = $this->service->getUserActivityCount($this->user);

        $this->assertEquals(8, $count);
    }

    /**
     * Test getting user activity count with date range
     */
    public function test_get_user_activity_count_with_date_range(): void
    {
        $startDate = Carbon::now()->subDays(5);
        $endDate = Carbon::now();

        ActivityLog::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()->subDays(2),
        ]);
        ActivityLog::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::now()->subDays(10), // Outside range
        ]);

        $count = $this->service->getUserActivityCount($this->user, $startDate, $endDate);

        $this->assertEquals(3, $count);
    }

    /**
     * Test getting subject activities
     */
    public function test_get_subject_activities(): void
    {
        $course = Course::factory()->create();
        ActivityLog::factory()->count(5)->create([
            'subject_type' => Course::class,
            'subject_id' => $course->id,
        ]);
        ActivityLog::factory()->count(3)->create(); // Different subjects

        $activities = $this->service->getSubjectActivities($course);

        $this->assertCount(5, $activities);
        $activities->each(function ($log) use ($course) {
            $this->assertEquals(Course::class, $log->subject_type);
            $this->assertEquals($course->id, $log->subject_id);
        });
    }

    /**
     * Test getting users batch summary
     */
    public function test_get_users_batch_summary(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        ActivityLog::factory()->count(5)->create([
            'user_id' => $user1->id,
            'created_at' => Carbon::now()->subDays(2),
        ]);
        ActivityLog::factory()->count(3)->create([
            'user_id' => $user2->id,
            'created_at' => Carbon::now()->subDays(3),
        ]);
        ActivityLog::factory()->count(1)->create([
            'user_id' => $user3->id,
            'created_at' => Carbon::now()->subDays(10), // Outside range
        ]);

        $summary = $this->service->getUsersBatchSummary(
            [$user1->id, $user2->id, $user3->id],
            $startDate,
            $endDate
        );

        $this->assertCount(2, $summary); // Only user1 and user2 had activities in range
        $this->assertEquals(5, $summary[$user1->id]['activity_count']);
        $this->assertEquals(3, $summary[$user2->id]['activity_count']);
        $this->assertArrayNotHasKey($user3->id, $summary->toArray());
    }

    /**
     * Test activity report includes all required sections
     */
    public function test_activity_report_structure(): void
    {
        $course = Course::factory()->create();
        ActivityLog::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'subject_type' => Course::class,
            'subject_id' => $course->id,
        ]);

        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();
        $report = $this->service->getUserActivityReport($this->user, $startDate, $endDate);

        // Verify all required sections exist
        $this->assertArrayHasKey('user', $report);
        $this->assertArrayHasKey('period', $report);
        $this->assertArrayHasKey('summary', $report);
        $this->assertArrayHasKey('activity_breakdown', $report);
        $this->assertArrayHasKey('activity_timeline', $report);
        $this->assertArrayHasKey('subject_breakdown', $report);
        $this->assertArrayHasKey('unique_ips', $report);
        $this->assertArrayHasKey('recent_activities', $report);

        // Verify summary structure
        $this->assertArrayHasKey('total_activities', $report['summary']);
        $this->assertArrayHasKey('unique_actions', $report['summary']);
        $this->assertArrayHasKey('average_per_day', $report['summary']);
        $this->assertArrayHasKey('most_active_day', $report['summary']);
        $this->assertArrayHasKey('unique_ip_addresses', $report['summary']);
    }

    /**
     * Test system stats includes all required sections
     */
    public function test_system_stats_structure(): void
    {
        ActivityLog::factory()->count(5)->create();

        $stats = $this->service->getSystemActivityStats();

        // Verify all required sections exist
        $this->assertArrayHasKey('overview', $stats);
        $this->assertArrayHasKey('popular_actions', $stats);
        $this->assertArrayHasKey('most_active_users', $stats);
        $this->assertArrayHasKey('activity_by_day', $stats);
        $this->assertArrayHasKey('activity_by_hour', $stats);
        $this->assertArrayHasKey('subject_type_distribution', $stats);
        $this->assertArrayHasKey('generated_at', $stats);

        // Verify overview structure
        $this->assertArrayHasKey('total_activities', $stats['overview']);
        $this->assertArrayHasKey('activities_today', $stats['overview']);
        $this->assertArrayHasKey('activities_last_week', $stats['overview']);
        $this->assertArrayHasKey('activities_last_month', $stats['overview']);
        $this->assertArrayHasKey('active_users_today', $stats['overview']);
        $this->assertArrayHasKey('active_users_last_week', $stats['overview']);
    }

    /**
     * Test getting recent activities with IP address filter
     */
    public function test_get_recent_activities_with_ip_filter(): void
    {
        ActivityLog::factory()->count(3)->create(['ip_address' => '192.168.1.1']);
        ActivityLog::factory()->count(2)->create(['ip_address' => '10.0.0.1']);

        $activities = $this->service->getRecentActivities(['ip_address' => '192.168.1.1']);

        $this->assertCount(3, $activities);
        $activities->each(fn($log) => $this->assertEquals('192.168.1.1', $log->ip_address));
    }

    /**
     * Test getting activities with combined filters
     */
    public function test_get_recent_activities_with_multiple_filters(): void
    {
        $yesterday = Carbon::yesterday();
        $today = Carbon::today();

        ActivityLog::factory()->create([
            'user_id' => $this->user->id,
            'action' => 'course.viewed',
            'created_at' => $today,
        ]);
        ActivityLog::factory()->create([
            'user_id' => $this->user->id,
            'action' => 'assignment.submitted',
            'created_at' => $today,
        ]);
        ActivityLog::factory()->create([
            'user_id' => $this->user->id,
            'action' => 'course.viewed',
            'created_at' => $yesterday,
        ]);

        $activities = $this->service->getRecentActivities([
            'user_id' => $this->user->id,
            'action' => 'course.viewed',
            'start_date' => $today,
        ]);

        $this->assertCount(1, $activities);
        $this->assertEquals('course.viewed', $activities->first()->action);
    }
}
