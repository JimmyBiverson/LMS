<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin
        User::updateOrCreate(['email' => 'admin@edulab.test'], [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'name' => 'Admin User',
            'phone' => '+1234567890',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        // Instructor
        User::updateOrCreate(['email' => 'instructor@edulab.test'], [
            'first_name' => 'Robert',
            'last_name' => 'Smith',
            'name' => 'Robert Smith',
            'phone' => '+1234567891',
            'password' => Hash::make('password'),
            'role' => User::ROLE_INSTRUCTOR,
            'designation' => 'Senior Web Developer',
            'status' => User::STATUS_ACTIVE,
        ]);

        // Organization
        User::updateOrCreate(['email' => 'org@edulab.test'], [
            'name' => 'Codexshapper',
            'phone' => '+1234567892',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ORGANIZATION,
            'address' => 'Toronto, Canada',
            'status' => User::STATUS_ACTIVE,
        ]);

        // Student
        User::updateOrCreate(['email' => 'student@edulab.test'], [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'phone' => '+1234567893',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STUDENT,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->call([
            LevelSeeder::class,
            TagSeeder::class,
            CategorySeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,
            QuizSeeder::class,
            AssignmentSeeder::class,
            EnrollmentSeeder::class,
            ReviewSeeder::class,
            BlogSeeder::class,
            MiscSeeder::class,
        ]);
    }
}
