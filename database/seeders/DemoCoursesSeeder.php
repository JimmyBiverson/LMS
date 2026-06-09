<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructor = User::where('email', 'instructor@lms.test')->first();
        $students = User::where('role', 'student')->get();

        // Course 1: Web Development Fundamentals
        $course1 = Course::firstOrCreate(
            ['slug' => 'web-development-fundamentals'],
            [
                'user_id' => $instructor->id,
                'title' => 'Web Development Fundamentals',
                'description' => 'Learn the basics of web development including HTML, CSS, and JavaScript. Perfect for beginners!',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Web+Development',
                'price' => 49.99,
                'sale_price' => 29.99,
                'category_id' => 1,
                'status' => 'Active',
            ]
        );

        // Add lessons to Course 1
        $lessonsData1 = [
            ['title' => 'Getting Started with HTML', 'content' => 'Introduction to HTML tags and structure', 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'order' => 1, 'is_free_preview' => true],
            ['title' => 'CSS Styling Basics', 'content' => 'Learn CSS selectors, properties, and layout', 'video_url' => 'https://www.youtube.com/embed/E7wchG7gs7M', 'order' => 2, 'is_free_preview' => false],
            ['title' => 'JavaScript Fundamentals', 'content' => 'Variables, functions, and DOM manipulation', 'video_url' => 'https://www.youtube.com/embed/FSs_JYwnAdI', 'order' => 3, 'is_free_preview' => false],
            ['title' => 'Responsive Web Design', 'content' => 'Mobile-first design principles', 'video_url' => 'https://www.youtube.com/embed/4Ey_r2j2Y0w', 'order' => 4, 'is_free_preview' => false],
        ];

        foreach ($lessonsData1 as $lessonData) {
            Lesson::create(array_merge($lessonData, ['course_id' => $course1->id]));
        }

        // Course 2: Digital Marketing Essentials
        $course2 = Course::firstOrCreate(
            ['slug' => 'digital-marketing-essentials'],
            [
                'user_id' => $instructor->id,
                'title' => 'Digital Marketing Essentials',
                'description' => 'Master modern digital marketing strategies and tactics. Learn SEO, social media, email marketing, and more.',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Digital+Marketing',
                'price' => 59.99,
                'sale_price' => 39.99,
                'category_id' => 2,
                'status' => 'Active',
            ]
        );

        $lessonsData2 = [
            ['title' => 'Digital Marketing Overview', 'content' => 'Understanding the digital landscape', 'video_url' => 'https://www.youtube.com/embed/jNQXAC9IVRw', 'order' => 1, 'is_free_preview' => true],
            ['title' => 'SEO Fundamentals', 'content' => 'On-page and off-page optimization', 'video_url' => 'https://www.youtube.com/embed/9bZkp7q19f0', 'order' => 2, 'is_free_preview' => false],
            ['title' => 'Social Media Strategy', 'content' => 'Content creation and engagement tactics', 'video_url' => 'https://www.youtube.com/embed/kffacxfA7g4', 'order' => 3, 'is_free_preview' => false],
            ['title' => 'Email Marketing Campaigns', 'content' => 'Building and maintaining an email list', 'video_url' => 'https://www.youtube.com/embed/ZXsQAXx_ao0', 'order' => 4, 'is_free_preview' => false],
        ];

        foreach ($lessonsData2 as $lessonData) {
            Lesson::create(array_merge($lessonData, ['course_id' => $course2->id]));
        }

        // Course 3: Advanced Python Programming
        $course3 = Course::firstOrCreate(
            ['slug' => 'advanced-python-programming'],
            [
                'user_id' => $instructor->id,
                'title' => 'Advanced Python Programming',
                'description' => 'Deep dive into advanced Python concepts including OOP, decorators, and async programming.',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Python+Programming',
                'price' => 79.99,
                'sale_price' => 49.99,
                'category_id' => 1,
                'status' => 'Active',
            ]
        );

        $lessonsData3 = [
            ['title' => 'Object-Oriented Programming', 'content' => 'Classes, inheritance, and polymorphism', 'video_url' => 'https://www.youtube.com/embed/qPKrQp3p5qc', 'order' => 1, 'is_free_preview' => false],
            ['title' => 'Decorators and Generators', 'content' => 'Advanced Python patterns', 'video_url' => 'https://www.youtube.com/embed/FsAPt_9Bf3U', 'order' => 2, 'is_free_preview' => false],
            ['title' => 'Async Programming', 'content' => 'Asynchronous programming with asyncio', 'video_url' => 'https://www.youtube.com/embed/Xbl7ZBhnDiw', 'order' => 3, 'is_free_preview' => false],
        ];

        foreach ($lessonsData3 as $lessonData) {
            Lesson::create(array_merge($lessonData, ['course_id' => $course3->id]));
        }

        // Course 4: Data Science with Machine Learning
        $course4 = Course::firstOrCreate(
            ['slug' => 'data-science-machine-learning'],
            [
                'user_id' => $instructor->id,
                'title' => 'Data Science with Machine Learning',
                'description' => 'Learn data analysis, visualization, and machine learning algorithms using Python and popular libraries.',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Data+Science',
                'price' => 99.99,
                'sale_price' => 69.99,
                'category_id' => 3,
                'status' => 'Active',
            ]
        );

        $lessonsData4 = [
            ['title' => 'NumPy and Pandas Basics', 'content' => 'Data manipulation with NumPy and Pandas', 'video_url' => 'https://www.youtube.com/embed/r-uOLxkrihE', 'order' => 1, 'is_free_preview' => true],
            ['title' => 'Data Visualization', 'content' => 'Create insightful visualizations', 'video_url' => 'https://www.youtube.com/embed/nzKy9GY12qU', 'order' => 2, 'is_free_preview' => false],
            ['title' => 'Machine Learning Basics', 'content' => 'Supervised and unsupervised learning', 'video_url' => 'https://www.youtube.com/embed/aircAruvnKk', 'order' => 3, 'is_free_preview' => false],
            ['title' => 'Deep Learning Introduction', 'content' => 'Neural networks and TensorFlow', 'video_url' => 'https://www.youtube.com/embed/aircAruvnKk', 'order' => 4, 'is_free_preview' => false],
        ];

        foreach ($lessonsData4 as $lessonData) {
            Lesson::create(array_merge($lessonData, ['course_id' => $course4->id]));
        }

        // Create enrollments for students
        $courses = [$course1, $course2, $course3, $course4];
        
        foreach ($students as $student) {
            // Each student enrolls in 2-3 courses
            $enrolledCourses = collect($courses)->random(rand(2, 3))->pluck('id');
            
            foreach ($enrolledCourses as $courseId) {
                Enrollment::firstOrCreate(
                    ['user_id' => $student->id, 'course_id' => $courseId],
                    [
                        'status' => 'Active',
                        'amount_paid' => Course::find($courseId)->sale_price ?? Course::find($courseId)->price,
                    ]
                );
            }
        }

        $this->command->info('✓ Created 4 demo courses with lessons');
        $this->command->info('✓ Enrolled ' . $students->count() . ' students in courses');
        $this->command->info('✓ Total enrollments: ' . Enrollment::count());
    }
}
