<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PresentationTestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@lms.test'],
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@lms.test',
                'phone' => '256700000001',
                'password' => Hash::make('Password@123'),
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create Instructor Users
        $instructor1 = User::firstOrCreate(
            ['email' => 'instructor@lms.test'],
            [
                'name' => 'Dr. Sarah Katende',
                'first_name' => 'Sarah',
                'last_name' => 'Katende',
                'email' => 'instructor@lms.test',
                'phone' => '256700000002',
                'password' => Hash::make('Password@123'),
                'role' => User::ROLE_INSTRUCTOR,
                'designation' => 'Senior Software Engineer',
                'bio' => 'Passionate about web development and mentoring students in East Africa.',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        $instructor2 = User::firstOrCreate(
            ['email' => 'instructor2@lms.test'],
            [
                'name' => 'Eng. David Ouma',
                'first_name' => 'David',
                'last_name' => 'Ouma',
                'email' => 'instructor2@lms.test',
                'phone' => '256700000003',
                'password' => Hash::make('Password@123'),
                'role' => User::ROLE_INSTRUCTOR,
                'designation' => 'Mobile Development Specialist',
                'bio' => 'Specializing in mobile application development for African markets.',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create Organization User
        $organization = User::firstOrCreate(
            ['email' => 'organization@lms.test'],
            [
                'name' => 'Makerere University IT Department',
                'email' => 'organization@lms.test',
                'phone' => '256700000004',
                'password' => Hash::make('Password@123'),
                'role' => User::ROLE_ORGANIZATION,
                'address' => 'Kampala, Uganda',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create Student Users
        $students = [];
        $studentEmails = [
            'student1@lms.test',
            'student2@lms.test',
            'student3@lms.test',
            'student4@lms.test',
            'student5@lms.test',
        ];

        $firstNames = ['Alice', 'Brian', 'Carol', 'Daniel', 'Emily'];
        $lastNames = ['Nakato', 'Ssewanyana', 'Mwase', 'Nyamari', 'Kipchoge'];

        foreach ($studentEmails as $index => $email) {
            $students[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $firstNames[$index] . ' ' . $lastNames[$index],
                    'first_name' => $firstNames[$index],
                    'last_name' => $lastNames[$index],
                    'email' => $email,
                    'phone' => '256700000' . sprintf('%03d', 5 + $index),
                    'password' => Hash::make('Password@123'),
                    'role' => User::ROLE_STUDENT,
                    'status' => User::STATUS_ACTIVE,
                ]
            );
        }

        $this->command->info('✓ Created admin user: admin@lms.test');
        $this->command->info('✓ Created 2 instructor users');
        $this->command->info('✓ Created 1 organization user');
        $this->command->info('✓ Created 5 student users');
        $this->command->line('');
        $this->command->line('All test users password: Password@123');
    }
}
