<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateDailyReport extends Command
{
    protected $signature = 'lms:daily-report';
    protected $description = 'Generate daily usage report and log it';

    public function handle(): void
    {
        $today = now()->toDateString();

        $newUsers = User::whereDate('created_at', $today)->count();
        $newEnrollments = Enrollment::whereDate('created_at', $today)->count();
        $newCourses = Course::whereDate('created_at', $today)->count();
        $revenue = Enrollment::whereDate('created_at', $today)->sum('amount_paid');

        $report = [
            'date' => $today,
            'new_users' => $newUsers,
            'new_enrollments' => $newEnrollments,
            'new_courses' => $newCourses,
            'revenue' => $revenue,
        ];

        Log::info('Daily Report', $report);

        $this->info('Daily report generated: ' . json_encode($report));
    }
}
