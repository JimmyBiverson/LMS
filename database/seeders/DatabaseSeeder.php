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
        User::updateOrCreate(['email' => 'admin@gmail.com'], [
            'first_name' => 'System',
            'last_name' => 'Admin',
            'name' => 'System Admin',
            'phone' => '+1234567890',
            'password' => Hash::make('Password123@'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        // Additional Admin 1
        User::updateOrCreate(['email' => 'james.biverson@edulab.test'], [
            'first_name' => 'James',
            'last_name' => 'Biverson',
            'name' => 'James Biverson',
            'phone' => '+1234567894',
            'password' => Hash::make('JamesAdmin2026!'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        // Additional Admin 2
        User::updateOrCreate(['email' => 'sarah.admin@edulab.test'], [
            'first_name' => 'Sarah',
            'last_name' => 'Admin',
            'name' => 'Sarah Admin',
            'phone' => '+1234567895',
            'password' => Hash::make('SarahAdmin2026!'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        // Additional Admin 3
        User::updateOrCreate(['email' => 'it.admin@edulab.test'], [
            'first_name' => 'IT',
            'last_name' => 'Admin',
            'name' => 'IT Admin',
            'phone' => '+1234567896',
            'password' => Hash::make('ITAdmin2026!'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        // Compatibility Admin
        User::updateOrCreate(['email' => 'admin@edulab.test'], [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'name' => 'Admin User',
            'phone' => '+1234567890',
            'password' => Hash::make('Admin123@'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
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
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'instructor@gmail.com'], [
            'first_name' => 'John',
            'last_name' => 'Instructor',
            'name' => 'John Instructor',
            'phone' => '+1234567897',
            'password' => Hash::make('12345654321'),
            'role' => User::ROLE_INSTRUCTOR,
            'designation' => 'Instructor Specialist',
            'status' => User::STATUS_ACTIVE,
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        // Organization
        User::updateOrCreate(['email' => 'org@edulab.test'], [
            'name' => 'Codexshapper',
            'phone' => '+1234567892',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ORGANIZATION,
            'address' => 'Toronto, Canada',
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'org@gmail.com'], [
            'name' => 'Apex Organization',
            'phone' => '+1234567898',
            'password' => Hash::make('12345654321'),
            'role' => User::ROLE_ORGANIZATION,
            'address' => 'Kampala, Uganda',
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
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
            'email_verified_at' => now(),
        ]);

        $this->call([
            PresentationTestUsersSeeder::class,
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
            BadgeSeeder::class,
            CurrencySeeder::class,
            SiteLanguageSeeder::class,
            EmailTemplateSeeder::class,
            TimezoneSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            IconProviderSeeder::class,
        ]);
    }
}
