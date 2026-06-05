<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $videoUrls = [
            'https://www.w3schools.com/html/mov_bbb.mp4',
            'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4',
        ];

        $courses = Course::all();

        $lessonsByCourse = [
            'Introduction to Web Development' => [
                ['title' => 'Welcome to the Course', 'content' => 'In this lesson, we will introduce you to the world of web development. You will learn what to expect from this course and how to make the most of it.', 'duration' => '10:00', 'is_free_preview' => true],
                ['title' => 'Setting Up Your Development Environment', 'content' => 'Learn how to install and configure all the tools you need to start building websites, including code editors, browsers, and developer tools.', 'duration' => '15:30'],
                ['title' => 'HTML Fundamentals', 'content' => 'Dive into HTML5 and learn about elements, attributes, semantic markup, forms, tables, and best practices for structuring web content.', 'duration' => '25:00'],
                ['title' => 'CSS Styling Basics', 'content' => 'Master the fundamentals of CSS including selectors, box model, flexbox, grid, responsive design, and CSS custom properties.', 'duration' => '20:00'],
                ['title' => 'JavaScript Introduction', 'content' => 'Get started with JavaScript programming covering variables, functions, DOM manipulation, events, and ES6+ features.', 'duration' => '30:00'],
                ['title' => 'Building Your First Web Page', 'content' => 'Apply everything you have learned by building a complete multi-section landing page from scratch.', 'duration' => '18:45'],
            ],
            'Advanced Laravel: Build Real-World Apps' => [
                ['title' => 'Course Overview & Prerequisites', 'content' => 'An overview of what we will build in this course and a review of the prerequisites you need to be successful.', 'duration' => '08:00', 'is_free_preview' => true],
                ['title' => 'Laravel Architecture Deep Dive', 'content' => 'Explore Laravels internal architecture including the service container, facades, providers, and the request lifecycle.', 'duration' => '22:00'],
                ['title' => 'Authentication & Authorization', 'content' => 'Implement complete authentication with Laravel Breeze/Fortify and role-based authorization using gates and policies.', 'duration' => '35:00'],
                ['title' => 'Building RESTful APIs', 'content' => 'Design and build RESTful APIs with Laravel including resource controllers, API resources, rate limiting, and API versioning.', 'duration' => '40:00'],
                ['title' => 'Testing Your Application', 'content' => 'Learn to write feature tests, unit tests, and browser tests using PHPUnit and Laravel Dusk.', 'duration' => '28:00'],
                ['title' => 'Deployment to Production', 'content' => 'Learn how to deploy a Laravel application to production using Forge, Vapor, or traditional VPS setups.', 'duration' => '25:00'],
            ],
            'UI/UX Design Masterclass' => [
                ['title' => 'What is UI/UX Design?', 'content' => 'Understand the difference between UI and UX design, the design thinking process, and the role of a designer in product development.', 'duration' => '12:00', 'is_free_preview' => true],
                ['title' => 'User Research Methods', 'content' => 'Learn various user research methods including interviews, surveys, usability testing, and how to synthesize findings.', 'duration' => '28:00'],
                ['title' => 'Wireframing & Prototyping in Figma', 'content' => 'Master Figma for creating wireframes, interactive prototypes, design systems, and collaborative design workflows.', 'duration' => '32:00'],
                ['title' => 'Visual Design Principles', 'content' => 'Learn color theory, typography, layout, spacing, and visual hierarchy to create beautiful and functional designs.', 'duration' => '22:00'],
                ['title' => 'Building Your Design Portfolio', 'content' => 'Learn how to showcase your work effectively, write case studies, and present your designs to stakeholders and employers.', 'duration' => '15:00'],
            ],
            'Python for Data Science' => [
                ['title' => 'Python Setup & First Steps', 'content' => 'Install Python, set up Jupyter notebooks, and write your first Python programs with hands-on exercises.', 'duration' => '10:00', 'is_free_preview' => true],
                ['title' => 'Python Data Structures & Control Flow', 'content' => 'Master Python lists, dictionaries, sets, tuples, loops, conditionals, and list comprehensions.', 'duration' => '20:00'],
                ['title' => 'NumPy for Numerical Computing', 'content' => 'Learn NumPy arrays, vectorized operations, broadcasting, and linear algebra operations for data analysis.', 'duration' => '25:00'],
                ['title' => 'Pandas for Data Manipulation', 'content' => 'Master Pandas DataFrames for data cleaning, transformation, grouping, merging, and time series analysis.', 'duration' => '30:00'],
                ['title' => 'Data Visualization with Matplotlib', 'content' => 'Create publication-quality charts and plots using Matplotlib and Seaborn for exploratory data analysis.', 'duration' => '20:00'],
            ],
        ];

        foreach ($courses as $course) {
            $lessons = $lessonsByCourse[$course->title] ?? [];

            foreach ($lessons as $i => $lessonData) {
                Lesson::updateOrCreate(
                    ['course_id' => $course->id, 'title' => $lessonData['title']],
                    [
                        'content' => $lessonData['content'],
                        'video_url' => $videoUrls[array_rand($videoUrls)],
                        'duration' => $lessonData['duration'],
                        'order' => $i + 1,
                        'is_free_preview' => $lessonData['is_free_preview'] ?? false,
                        'status' => 'published',
                    ]
                );
            }
        }
    }
}
