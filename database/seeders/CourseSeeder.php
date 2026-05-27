<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::where('role', 'instructor')->first();

        if (!$instructor) {
            $instructor = User::factory()->create([
                'role' => 'instructor',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'name' => 'John Doe',
                'email' => 'instructor@example.com',
                'designation' => 'Web Developer',
            ]);
        }

        Course::create([
            'user_id' => $instructor->id,
            'title' => 'Introduction to Web Development',
            'category' => 'Web Development',
            'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for complete beginners.',
            'price' => 0,
            'payment_type' => 'free',
            'duration' => '6h 30m',
            'status' => 'Active',
        ]);

        Course::create([
            'user_id' => $instructor->id,
            'title' => 'Advanced Laravel: Build Real-World Apps',
            'category' => 'Web Development',
            'description' => 'Master Laravel by building a complete real-world application from scratch. Covers authentication, APIs, testing, and deployment.',
            'price' => 49.99,
            'payment_type' => 'paid',
            'duration' => '12h 45m',
            'status' => 'Active',
        ]);

        Course::create([
            'user_id' => $instructor->id,
            'title' => 'UI/UX Design Masterclass',
            'category' => 'Design',
            'description' => 'Learn professional UI/UX design principles, Figma workflows, and portfolio-building techniques.',
            'price' => 79.99,
            'sale_price' => 39.99,
            'payment_type' => 'paid',
            'duration' => '8h 20m',
            'status' => 'Active',
        ]);

        Course::create([
            'user_id' => $instructor->id,
            'title' => 'Python for Data Science',
            'category' => 'Data Science',
            'description' => 'Get started with Python programming for data analysis, visualization, and machine learning.',
            'price' => 0,
            'payment_type' => 'free',
            'duration' => '5h 00m',
            'status' => 'Active',
        ]);
    }
}