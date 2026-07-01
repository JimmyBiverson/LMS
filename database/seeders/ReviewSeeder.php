<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('role', 'student')->first();
        $courses = Course::all();

        if (!$student) return;

        $reviews = [
            'Introduction to Web Development' => [
                ['rating' => 5, 'review' => 'Excellent course for beginners! The content is well-structured and easy to follow. The projects really helped reinforce the concepts.'],
                ['rating' => 4, 'review' => 'Great introduction to web development. Would recommend to anyone starting out.'],
            ],
            'Advanced Laravel: Build Real-World Apps' => [
                ['rating' => 5, 'review' => 'This course took my Laravel skills to the next level. The real-world project approach is exactly what I needed.'],
                ['rating' => 4, 'review' => 'Comprehensive coverage of advanced topics. The API building section was particularly helpful.'],
            ],
            'UI/UX Design Masterclass' => [
                ['rating' => 5, 'review' => 'Amazing design course! The Figma tutorials were outstanding and the portfolio project was a game-changer.'],
            ],
            'Python for Data Science' => [
                ['rating' => 4, 'review' => 'Great starting point for data science with Python. The Pandas and NumPy sections are very practical.'],
                ['rating' => 5, 'review' => 'Perfect for beginners in data science. Clear explanations and hands-on exercises.'],
            ],
        ];

        foreach ($courses as $course) {
            $courseReviews = $reviews[$course->title] ?? [];
            foreach ($courseReviews as $reviewData) {
                Review::updateOrCreate(
                    ['user_id' => $student->id, 'course_id' => $course->id],
                    [
                        'rating' => $reviewData['rating'],
                        'review' => $reviewData['review'],
                        'is_approved' => true,
                    ]
                );
            }
        }
    }
}
