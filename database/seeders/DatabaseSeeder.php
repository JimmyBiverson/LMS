<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'name' => 'Admin User',
            'email' => 'admin@edulab.test',
            'phone' => '+1234567890',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        // Instructor
        User::factory()->create([
            'first_name' => 'Robert',
            'last_name' => 'Smith',
            'name' => 'Robert Smith',
            'email' => 'instructor@edulab.test',
            'phone' => '+1234567891',
            'password' => bcrypt('password'),
            'role' => User::ROLE_INSTRUCTOR,
            'designation' => 'Senior Web Developer',
            'status' => User::STATUS_ACTIVE,
        ]);

        // Organization
        User::factory()->create([
            'name' => 'Codexshapper',
            'email' => 'org@edulab.test',
            'phone' => '+1234567892',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ORGANIZATION,
            'address' => 'Toronto, Canada',
            'status' => User::STATUS_ACTIVE,
        ]);

        // Student
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'student@edulab.test',
            'phone' => '+1234567893',
            'password' => bcrypt('password'),
            'role' => User::ROLE_STUDENT,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->call(CourseSeeder::class);
    }
}
