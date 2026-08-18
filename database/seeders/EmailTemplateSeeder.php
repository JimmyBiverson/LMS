<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::insert([
            ['name' => 'Welcome', 'subject' => 'Welcome to EduLab', 'body' => 'Welcome to EduLab! We are excited to have you onboard.', 'status' => 'active'],
            ['name' => 'Reset Password', 'subject' => 'Password Reset Request', 'body' => 'Click the link below to reset your password.', 'status' => 'active'],
            ['name' => 'Enrollment', 'subject' => 'Enrollment Confirmation', 'body' => 'You have been successfully enrolled in the course.', 'status' => 'active'],
        ]);
    }
}
