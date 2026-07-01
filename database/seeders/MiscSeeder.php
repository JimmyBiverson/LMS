<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\HeroSection;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MiscSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Testimonials
        Testimonial::updateOrCreate(['name' => 'Sarah Johnson'], [
            'position' => 'Web Developer at Google',
            'content' => 'EduLab transformed my career. The courses are well-structured and the instructors are incredibly knowledgeable. I went from a complete beginner to a professional web developer in just 6 months.',
            'rating' => 5,
            'status' => 'active',
        ]);
        Testimonial::updateOrCreate(['name' => 'Michael Chen'], [
            'position' => 'Data Analyst at Amazon',
            'content' => 'The Data Science course was exactly what I needed to transition into analytics. The hands-on projects and real-world examples made learning practical and enjoyable.',
            'rating' => 5,
            'status' => 'active',
        ]);

        // Hero Section
        HeroSection::updateOrCreate(['page' => 'home'], [
            'title' => 'Learn Without Limits',
            'subtitle' => 'Unlock Your Potential',
            'description' => 'Access thousands of expert-led courses and take your skills to the next level. Learn at your own pace with lifetime access to course materials.',
            'status' => 'active',
        ]);

        // Sliders
        Slider::updateOrCreate(['title' => 'Welcome to EduLab'], [
            'subtitle' => 'Start Your Learning Journey',
            'description' => 'Join millions of learners worldwide and gain the skills you need to succeed.',
            'btn_text' => 'Get Started',
            'btn_link' => '/register',
            'order' => 1,
            'status' => 'active',
        ]);

        // FAQs
        Faq::updateOrCreate(['question' => 'How do I enroll in a course?'], ['answer' => 'Simply create an account, browse our course catalog, and click "Enroll" on any course. Free courses are immediately accessible, and paid courses require payment before access.', 'category' => 'General', 'order' => 1, 'status' => 'active']);
        Faq::updateOrCreate(['question' => 'Can I get a certificate after completing a course?'], ['answer' => 'Yes! When you complete all lessons in a course, a certificate of completion is automatically generated. You can download it from your dashboard.', 'category' => 'Certificates', 'order' => 1, 'status' => 'active']);
        Faq::updateOrCreate(['question' => 'What payment methods are accepted?'], ['answer' => 'We accept credit/debit cards (Visa, MasterCard, Amex), PayPal, and bank transfers for offline payments. All transactions are secure and encrypted.', 'category' => 'Payments', 'order' => 1, 'status' => 'active']);

        // Pages
        Page::updateOrCreate(['slug' => 'about-us'], [
            'title' => 'About Us',
            'content' => "EduLab is a leading online learning platform dedicated to providing high-quality education to learners worldwide. Founded in 2024, our mission is to make education accessible, affordable, and effective for everyone.\n\nOur platform features expert-led courses across multiple disciplines including web development, data science, design, and business. We believe in learning by doing, which is why our courses emphasize hands-on projects and real-world applications.\n\nWith a community of thousands of learners and hundreds of courses, EduLab is committed to helping you achieve your learning goals and advance your career.",
            'status' => 'published',
        ]);
        Page::updateOrCreate(['slug' => 'privacy-policy'], [
            'title' => 'Privacy Policy',
            'content' => "Your privacy is important to us. This Privacy Policy explains how EduLab collects, uses, and protects your personal information.\n\nWe collect information you provide when creating an account, enrolling in courses, and interacting with our platform. This includes your name, email address, and payment information.\n\nWe use this information to provide and improve our services, process payments, send course updates, and communicate with you about your learning progress.\n\nWe implement industry-standard security measures to protect your data. We do not share your personal information with third parties except as necessary to provide our services.",
            'status' => 'published',
        ]);

        // Coupon
        Coupon::updateOrCreate(['code' => 'WELCOME20'], [
            'discount' => 20,
            'discount_type' => 'percentage',
            'max_uses' => 100,
            'used_count' => 5,
            'min_amount' => 0,
            'expires_at' => now()->addYear(),
            'status' => 'active',
        ]);

        // Payment Methods
        PaymentMethod::updateOrCreate(['name' => 'PayPal'], ['type' => 'Online', 'status' => 'active', 'provider' => null]);
        PaymentMethod::updateOrCreate(['name' => 'Airtel Money'], ['type' => 'Offline', 'status' => 'active', 'provider' => 'airtel']);
        PaymentMethod::updateOrCreate(['name' => 'MTN Mobile Money'], ['type' => 'Offline', 'status' => 'active', 'provider' => 'mtn']);

        // Bundles (requires courses to exist)
        $courses = \App\Models\Course::all();
        if ($courses->count() >= 2) {
            $bundle1 = Bundle::updateOrCreate(['slug' => 'web-development-bundle'], [
                'title' => 'Web Development Bundle',
                'description' => 'Master both frontend and backend web development with this comprehensive bundle covering HTML, CSS, JavaScript, and Laravel.',
                'price' => 129.99,
                'sale_price' => 79.99,
                'status' => 'active',
                'user_id' => $admin?->id,
            ]);
            $bundle1->courses()->syncWithoutDetaching($courses->take(2)->pluck('id'));

            if ($courses->count() >= 4) {
                $bundle2 = Bundle::updateOrCreate(['slug' => 'design-data-science-bundle'], [
                    'title' => 'Design & Data Science Bundle',
                    'description' => 'Combine creative design skills with data science expertise to become a versatile tech professional.',
                    'price' => 99.99,
                    'sale_price' => 69.99,
                    'status' => 'active',
                    'user_id' => $admin?->id,
                ]);
                $bundle2->courses()->syncWithoutDetaching($courses->skip(2)->pluck('id'));
            }
        }
    }
}
