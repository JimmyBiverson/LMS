<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use App\Models\Level;
use App\Models\Enrollment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTestAccounts extends Command
{
    protected $signature = 'create:test-accounts';
    protected $description = 'Create comprehensive test accounts for all user roles with sample courses';

    public function handle()
    {
        $this->info('🚀 Creating comprehensive test accounts...');
        $this->line('');

        // Create categories
        $this->info('📚 Creating course categories...');
        $categories = [];
        foreach (['Digital Marketing', 'Web Development', 'Data Science', 'Business', 'Graphic Design'] as $catName) {
            $categories[$catName] = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName, 'status' => 'active']
            );
        }
        $this->line("✅ Created " . count($categories) . " categories");
        $this->line('');

        // Create levels
        $this->info('📊 Creating course levels...');
        $levels = [];
        foreach (['Beginner', 'Intermediate', 'Advanced', 'Expert'] as $levelName) {
            $levels[$levelName] = Level::firstOrCreate(
                ['name' => $levelName],
                ['name' => $levelName]
            );
        }
        $this->line("✅ Created " . count($levels) . " levels");
        $this->line('');

        // Admin Account
        $this->info('👨‍💼 Creating Admin Account...');
        $admin = User::firstOrCreate(
            ['email' => 'admin@lms.test'],
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('Password@123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $this->line("Email: admin@lms.test");
        $this->line("Password: Password@123");
        $this->line("Dashboard: /admin/dashboard/dashboard");
        $this->line('');

        // Student Accounts
        $this->info('👨‍🎓 Creating Student Accounts...');
        $students = [];
        $studentData = [
            ['name' => 'Alice Johnson', 'email' => 'alice@lms.test', 'first_name' => 'Alice', 'last_name' => 'Johnson'],
            ['name' => 'Bob Smith', 'email' => 'bob@lms.test', 'first_name' => 'Bob', 'last_name' => 'Smith'],
            ['name' => 'Carol Davis', 'email' => 'carol@lms.test', 'first_name' => 'Carol', 'last_name' => 'Davis'],
        ];

        foreach ($studentData as $data) {
            $student = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('Password@123'),
                    'role' => 'student',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ])
            );
            $students[] = $student;
            $this->line("✅ {$student->name} ({$student->email})");
        }
        $this->line('');
        $this->line("Dashboard: /dashboard");
        $this->line("Password: Password@123 (for all students)");
        $this->line('');

        // Instructor Accounts
        $this->info('👨‍🏫 Creating Instructor Accounts...');
        $instructors = [];
        $instructorData = [
            ['name' => 'Prof. James Wilson', 'email' => 'james@lms.test', 'first_name' => 'James', 'last_name' => 'Wilson', 'bio' => 'Expert in Digital Marketing with 10+ years experience'],
            ['name' => 'Prof. Sarah Lee', 'email' => 'sarah@lms.test', 'first_name' => 'Sarah', 'last_name' => 'Lee', 'bio' => 'Web Development Specialist and Open Source Contributor'],
            ['name' => 'Prof. Michael Brown', 'email' => 'michael@lms.test', 'first_name' => 'Michael', 'last_name' => 'Brown', 'bio' => 'Data Science and AI Research Professional'],
        ];

        foreach ($instructorData as $data) {
            $instructor = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => Hash::make('Password@123'),
                    'role' => 'instructor',
                    'status' => 'active',
                    'bio' => $data['bio'] ?? null,
                    'email_verified_at' => now(),
                ]
            );
            $instructors[] = $instructor;
            $this->line("✅ {$instructor->name} ({$instructor->email})");
        }
        $this->line('');
        $this->line("Dashboard: /instructor");
        $this->line("Password: Password@123 (for all instructors)");
        $this->line('');

        // Organization Accounts
        $this->info('🏫 Creating Organization Accounts...');
        $organizations = [];
        $orgData = [
            ['name' => 'Makerere University Learning Center', 'email' => 'learning@makerere.lms.test'],
            ['name' => 'Uganda Tech Training Institute', 'email' => 'training@utti.lms.test'],
        ];

        foreach ($orgData as $data) {
            $org = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'first_name' => explode(' ', $data['name'])[0],
                    'last_name' => 'Organization',
                    'password' => Hash::make('Password@123'),
                    'role' => 'organization',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $organizations[] = $org;
            $this->line("✅ {$org->name} ({$org->email})");
        }
        $this->line('');
        $this->line("Dashboard: /org");
        $this->line("Password: Password@123 (for all organizations)");
        $this->line('');

        // Create Sample Courses
        $this->info('📖 Creating Sample Courses...');
        $courseTemplates = [
            [
                'instructor' => $instructors[0],
                'title' => 'Digital Marketing Fundamentals',
                'category' => $categories['Digital Marketing'],
                'level' => $levels['Beginner'],
                'description' => 'Learn the basics of digital marketing including SEO, SEM, social media marketing, and email marketing.',
                'payment_type' => 'free',
                'lessons' => [
                    ['title' => 'Introduction to Digital Marketing', 'content' => 'Learn what digital marketing is and why it matters'],
                    ['title' => 'SEO Basics', 'content' => 'Understand search engine optimization fundamentals'],
                    ['title' => 'Social Media Marketing', 'content' => 'Strategies for social media platforms'],
                ]
            ],
            [
                'instructor' => $instructors[1],
                'title' => 'Web Development with Laravel',
                'category' => $categories['Web Development'],
                'level' => $levels['Intermediate'],
                'description' => 'Build modern web applications using Laravel framework. Learn routing, databases, authentication, and deployment.',
                'payment_type' => 'free',
                'lessons' => [
                    ['title' => 'Laravel Setup & Installation', 'content' => 'Get started with Laravel development'],
                    ['title' => 'Routing and Controllers', 'content' => 'Master Laravel routing and controllers'],
                    ['title' => 'Database & Eloquent ORM', 'content' => 'Work with databases using Eloquent'],
                    ['title' => 'Authentication & Authorization', 'content' => 'Implement secure authentication'],
                ]
            ],
            [
                'instructor' => $instructors[2],
                'title' => 'Data Science with Python',
                'category' => $categories['Data Science'],
                'level' => $levels['Beginner'],
                'description' => 'Introduction to data science using Python. Learn NumPy, Pandas, and data visualization.',
                'payment_type' => 'free',
                'lessons' => [
                    ['title' => 'Python Basics for Data Science', 'content' => 'Essential Python skills for data work'],
                    ['title' => 'Working with NumPy', 'content' => 'Numerical computing with NumPy'],
                    ['title' => 'Data Manipulation with Pandas', 'content' => 'Clean and transform data efficiently'],
                ]
            ],
            [
                'instructor' => $instructors[0],
                'title' => 'Business Strategy Masterclass',
                'category' => $categories['Business'],
                'level' => $levels['Advanced'],
                'description' => 'Advanced course on business strategy, competitive analysis, and market positioning.',
                'payment_type' => 'paid',
                'price' => 99.99,
                'sale_price' => 49.99,
                'lessons' => [
                    ['title' => 'Strategic Planning Framework', 'content' => 'Develop strategic plans for your business'],
                    ['title' => 'Competitive Analysis', 'content' => 'Analyze your competition effectively'],
                ]
            ],
            [
                'instructor' => $instructors[1],
                'title' => 'Graphic Design Essentials',
                'category' => $categories['Graphic Design'],
                'level' => $levels['Beginner'],
                'description' => 'Learn fundamental graphic design principles, color theory, and design tools.',
                'payment_type' => 'free',
                'lessons' => [
                    ['title' => 'Design Principles', 'content' => 'Core principles of good design'],
                    ['title' => 'Color Theory', 'content' => 'Understanding colors and color schemes'],
                    ['title' => 'Typography', 'content' => 'Mastering fonts and text design'],
                ]
            ],
        ];

        $courses = [];
        foreach ($courseTemplates as $template) {
            $course = Course::firstOrCreate(
                ['slug' => Str::slug($template['title']) . '-' . Str::random(5)],
                [
                    'user_id' => $template['instructor']->id,
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'category_id' => $template['category']->id,
                    'level_id' => $template['level']->id,
                    'payment_type' => $template['payment_type'],
                    'price' => $template['price'] ?? null,
                    'sale_price' => $template['sale_price'] ?? null,
                    'status' => 'Active',
                    'duration' => '4 weeks',
                    'outcomes' => implode("\n", [
                        'Understand core concepts',
                        'Apply knowledge in real projects',
                        'Get certified upon completion',
                    ]),
                ]
            );
            $courses[] = $course;

            // Create lessons
            foreach ($template['lessons'] as $lessonData) {
                Lesson::firstOrCreate(
                    ['course_id' => $course->id, 'title' => $lessonData['title']],
                    [
                        'content' => $lessonData['content'],
                        'status' => 'published',
                        'is_free_preview' => true,
                    ]
                );
            }

            $this->line("✅ {$course->title} ({$template['payment_type']})");
        }
        $this->line('');

        // Enroll students in courses
        $this->info('📝 Enrolling Students in Courses...');
        foreach ($students as $student) {
            // Enroll in first 2 courses
            foreach (array_slice($courses, 0, 2) as $course) {
                Enrollment::firstOrCreate(
                    ['user_id' => $student->id, 'course_id' => $course->id],
                    [
                        'status' => 'in_progress',
                        'amount_paid' => $course->payment_type === 'paid' ? ($course->sale_price ?? $course->price) : 0,
                    ]
                );
            }
            $this->line("✅ {$student->name} enrolled in sample courses");
        }
        $this->line('');

        // Display Summary
        $this->info('✅ SYSTEM SETUP COMPLETE!');
        $this->line('');
        $this->table(
            ['User Type', 'Email', 'Password', 'Dashboard'],
            [
                ['Admin', 'admin@lms.test', 'Password@123', '/admin/dashboard/dashboard'],
                ['Student (3)', 'alice@lms.test, bob@lms.test, carol@lms.test', 'Password@123', '/dashboard'],
                ['Instructor (3)', 'james@lms.test, sarah@lms.test, michael@lms.test', 'Password@123', '/instructor'],
                ['Organization (2)', 'learning@makerere.lms.test, training@utti.lms.test', 'Password@123', '/org'],
            ]
        );
        $this->line('');
        $this->info('📊 Data Summary:');
        $this->line("✅ {$courseTemplates|count} courses created (5 free, varying paid)");
        $this->line("✅ " . array_sum(array_map(fn($t) => count($t['lessons']), $courseTemplates)) . " lessons total");
        $this->line("✅ " . (count($students) * 2) . " course enrollments");
        $this->line('');
        $this->info('🚀 Ready to present! Visit ' . config('app.url') . ' to get started.');
        $this->line('');
        $this->comment('💡 Tip: Try logging in with different accounts to see role-specific dashboards');
        $this->comment('💡 Free courses can be enrolled immediately, paid courses demonstrate payment flow');
    }
}
