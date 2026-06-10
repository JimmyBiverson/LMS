<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use App\Models\Level;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTestAccountsSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n🚀 Creating comprehensive test accounts...\n\n";

        // Create categories
        echo "📚 Creating course categories...\n";
        $categories = [];
        foreach (['Digital Marketing', 'Web Development', 'Data Science', 'Business', 'Graphic Design'] as $catName) {
            $categories[$catName] = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName, 'status' => 'active']
            );
        }
        echo "✅ Created " . count($categories) . " categories\n\n";

        // Create levels
        echo "📊 Creating course levels...\n";
        $levels = [];
        foreach (['Beginner', 'Intermediate', 'Advanced', 'Expert'] as $levelName) {
            $levels[$levelName] = Level::firstOrCreate(
                ['name' => $levelName],
                ['name' => $levelName]
            );
        }
        echo "✅ Created " . count($levels) . " levels\n\n";

        // Admin Account
        echo "👨‍💼 Creating Admin Account...\n";
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
        echo "Email: admin@lms.test\n";
        echo "Password: Password@123\n";
        echo "Dashboard: /admin/dashboard/dashboard\n\n";

        // Student Accounts
        echo "👨‍🎓 Creating Student Accounts...\n";
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
            echo "✅ {$student->name} ({$student->email})\n";
        }
        echo "Dashboard: /dashboard\n";
        echo "Password: Password@123 (for all students)\n\n";

        // Instructor Accounts
        echo "👨‍🏫 Creating Instructor Accounts...\n";
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
            echo "✅ {$instructor->name} ({$instructor->email})\n";
        }
        echo "Dashboard: /instructor\n";
        echo "Password: Password@123 (for all instructors)\n\n";

        // Organization Accounts
        echo "🏫 Creating Organization Accounts...\n";
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
            echo "✅ {$org->name} ({$org->email})\n";
        }
        echo "Dashboard: /org\n";
        echo "Password: Password@123 (for all organizations)\n\n";

        // Create Sample Courses
        echo "📖 Creating Sample Courses...\n";
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
                    'price' => $template['price'] ?? 0,
                    'sale_price' => $template['sale_price'] ?? 0,
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

            echo "✅ {$course->title} ({$template['payment_type']})\n";
        }
        echo "\n";

        // Enroll students in courses
        echo "📝 Enrolling Students in Courses...\n";
        foreach ($students as $student) {
            foreach (array_slice($courses, 0, 2) as $course) {
                Enrollment::firstOrCreate(
                    ['user_id' => $student->id, 'course_id' => $course->id],
                    [
                        'status' => 'in_progress',
                        'amount_paid' => $course->payment_type === 'paid' ? ($course->sale_price ?? $course->price) : 0,
                    ]
                );
            }
            echo "✅ {$student->name} enrolled in sample courses\n";
        }
        echo "\n";

        echo "✅ SYSTEM SETUP COMPLETE!\n";
        echo "\nTest Credentials:\n";
        echo "Admin: admin@lms.test / Password@123 → /admin/dashboard/dashboard\n";
        echo "Students: alice@lms.test, bob@lms.test, carol@lms.test / Password@123 → /dashboard\n";
        echo "Instructors: james@lms.test, sarah@lms.test, michael@lms.test / Password@123 → /instructor\n";
        echo "Organizations: learning@makerere.lms.test, training@utti.lms.test / Password@123 → /org\n";
        echo "\n📊 Data Summary:\n";
        echo "✅ " . count($courseTemplates) . " courses created (mix of free and paid)\n";
        echo "✅ " . array_sum(array_map(fn($t) => count($t['lessons']), $courseTemplates)) . " lessons total\n";
        echo "✅ " . (count($students) * 2) . " course enrollments\n";
        echo "\n🚀 Ready to present! Visit http://127.0.0.1:8000 to get started.\n\n";
    }
}
