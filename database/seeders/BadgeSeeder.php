<?php

namespace Database\Seeders;

use App\Models\AchievementBadge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Steps',
                'slug' => 'first-steps',
                'description' => 'Completed your first lesson',
                'icon' => 'ri-footprint-line',
                'points' => 10,
                'criteria_type' => 'first_lesson',
                'criteria' => ['lesson_count' => 1],
            ],
            [
                'name' => 'Eager Learner',
                'slug' => 'eager-learner',
                'description' => 'Completed 10 lessons',
                'icon' => 'ri-book-open-line',
                'points' => 50,
                'criteria_type' => 'ten_lessons',
                'criteria' => ['lesson_count' => 10],
            ],
            [
                'name' => 'Course Conqueror',
                'slug' => 'course-conqueror',
                'description' => 'Completed your first course',
                'icon' => 'ri-graduation-cap-line',
                'points' => 100,
                'criteria_type' => 'course_complete',
                'criteria' => ['course_count' => 1],
            ],
            [
                'name' => 'Perfect Score',
                'slug' => 'perfect-score',
                'description' => 'Scored 100% on a quiz',
                'icon' => 'ri-award-line',
                'points' => 75,
                'criteria_type' => 'perfect_quiz',
                'criteria' => ['perfect_score' => true],
            ],
            [
                'name' => 'Welcome Aboard',
                'slug' => 'welcome-aboard',
                'description' => 'Signed up for an account',
                'icon' => 'ri-user-smile-line',
                'points' => 5,
                'criteria_type' => 'first_login',
                'criteria' => ['login_count' => 1],
            ],
            [
                'name' => 'Star Reviewer',
                'slug' => 'star-reviewer',
                'description' => 'Reviewed 5 courses',
                'icon' => 'ri-star-line',
                'points' => 30,
                'criteria_type' => 'reviewer',
                'criteria' => ['review_count' => 5],
            ],
            [
                'name' => 'Dedicated Scholar',
                'slug' => 'dedicated-scholar',
                'description' => 'Studied for 30 days in a row',
                'icon' => 'ri-fire-line',
                'points' => 200,
                'criteria_type' => 'streak',
                'criteria' => ['streak_days' => 30],
            ],
            [
                'name' => 'Knowledge Seeker',
                'slug' => 'knowledge-seeker',
                'description' => 'Enrolled in 5 courses',
                'icon' => 'ri-database-2-line',
                'points' => 40,
                'criteria_type' => 'enrollment_milestone',
                'criteria' => ['course_count' => 5],
            ],
        ];

        foreach ($badges as $badge) {
            AchievementBadge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}
