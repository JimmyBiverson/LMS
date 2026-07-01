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

        Course::updateOrCreate(['title' => 'Introduction to Web Development'], [
            'user_id' => $instructor->id,
            'slug' => 'introduction-to-web-development',
            'category' => 'Web Development',
            'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for complete beginners.',
            'outcomes' => "Build responsive web pages using HTML5 and CSS3\nWrite JavaScript programs to add interactivity to websites\nUnderstand the client-server architecture of the web\nUse developer tools to debug and inspect web pages\nDeploy a static website to production",
            'requirements' => "A computer with internet access\nNo prior coding experience required\nWillingness to learn and practice",
            'price' => 0,
            'payment_type' => 'free',
            'duration' => '6h 30m',
            'status' => 'Active',
        ]);

        Course::updateOrCreate(['title' => 'Advanced Laravel: Build Real-World Apps'], [
            'user_id' => $instructor->id,
            'slug' => 'advanced-laravel-build-real-world-apps',
            'category' => 'Web Development',
            'description' => 'Master Laravel by building a complete real-world application from scratch. Covers authentication, APIs, testing, and deployment.',
            'outcomes' => "Build a complete Laravel application from scratch\nImplement authentication and authorization\nCreate RESTful APIs with Laravel\nWrite feature and unit tests\nDeploy a Laravel app to production",
            'requirements' => "Basic PHP knowledge\nFamiliarity with MVC pattern\nLaravel installed on your machine",
            'price' => 49.99,
            'payment_type' => 'paid',
            'duration' => '12h 45m',
            'status' => 'Active',
        ]);

        Course::updateOrCreate(['title' => 'UI/UX Design Masterclass'], [
            'user_id' => $instructor->id,
            'slug' => 'ui-ux-design-masterclass',
            'category' => 'Design',
            'description' => 'Learn professional UI/UX design principles, Figma workflows, and portfolio-building techniques.',
            'outcomes' => "Apply UX research methods to understand user needs\nCreate wireframes and interactive prototypes in Figma\nDesign accessible and inclusive user interfaces\nBuild a professional design portfolio",
            'requirements' => "A computer with Figma installed (free tier is fine)\nNo prior design experience required\nA creative mindset",
            'price' => 79.99,
            'sale_price' => 39.99,
            'payment_type' => 'paid',
            'duration' => '8h 20m',
            'status' => 'Active',
        ]);

        Course::updateOrCreate(['title' => 'Python for Data Science'], [
            'user_id' => $instructor->id,
            'slug' => 'python-for-data-science',
            'category' => 'Data Science',
            'description' => 'Get started with Python programming for data analysis, visualization, and machine learning.',
            'outcomes' => "Write Python programs using core language features\nManipulate data with Pandas DataFrames\nCreate visualizations with Matplotlib and Seaborn\nBuild and evaluate basic machine learning models",
            'requirements' => "Basic computer literacy\nNo programming experience required\nPython 3 installed on your machine",
            'price' => 0,
            'payment_type' => 'free',
            'duration' => '5h 00m',
            'status' => 'Active',
        ]);
    }
}