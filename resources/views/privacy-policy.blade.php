@extends('layouts.app')

@section('title', 'Privacy & Policy')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Privacy and Policy</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Privacy and Policy</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl p-8 shadow-sm prose prose-sm max-w-none text-heading/70">
            <h2 class="text-2xl font-bold text-heading">Welcome to {{ $school->school_name ?? 'Edulab' }}</h2>
            <p>These Terms and Conditions ("Terms") govern your access to and use of the website https://edulab.codexshaper.com/, including all content, services, and features offered on or through the Website (the "Services"). By accessing or using the Website, you agree to comply with and be bound by these Terms. If you do not agree to these Terms, please do not use the Website.</p>

            <h3 class="text-lg font-bold text-heading mt-6">1. User Eligibility</h3>
            <p>You must be at least 18 years old to use our Website. By using the Website, you represent and warrant that you meet the eligibility requirements.</p>

            <h3 class="text-lg font-bold text-heading mt-6">2. Account Registration</h3>
            <p>To access certain Services, you may be required to create an account by providing accurate and up-to-date information. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Notify us immediately if you believe your account has been compromised.</p>

            <h3 class="text-lg font-bold text-heading mt-6">3. Use of the Website</h3>
            <p>You agree to use the Website solely for lawful purposes and in accordance with these Terms. You must not:</p>
            <ul class="list-disc pl-6 space-y-1">
                <li>Violate any applicable local, state, national, or international law.</li>
                <li>Upload, post, or transmit any content that is unlawful, harmful, or infringing.</li>
                <li>Engage in any activity that could disable, overload, or damage the functionality of the Website.</li>
            </ul>

            <h3 class="text-lg font-bold text-heading mt-6">4. Intellectual Property</h3>
            <p>All content, including but not limited to text, images, videos, courses, software, and other materials available on the Website, are owned by or licensed to {{ config('app.name', 'EduLab') }} and are protected by intellectual property laws. You may not reproduce, distribute, or create derivative works from any content without prior written consent from the owner.</p>

            <h3 class="text-lg font-bold text-heading mt-6">5. Content Submission</h3>
            <p>If you submit any content (such as course materials, comments, or feedback) through the Website, you grant {{ $school->school_name ?? 'Edulab' }} a worldwide, royalty-free, and irrevocable license to use, reproduce, modify, and distribute that content in connection with the Website and its Services.</p>

            <h3 class="text-lg font-bold text-heading mt-6">6. Course Access and Use</h3>
            <p>Access to courses, content, and materials on the Website is subject to the specific terms outlined in the course description or agreement. We reserve the right to update, modify, or remove any course content at any time. Course access may be revoked if you violate these Terms.</p>

            <h3 class="text-lg font-bold text-heading mt-6">7. Payment and Refund Policy</h3>
            <p>If you purchase a course or subscription, you agree to pay all applicable fees. Payments are processed through third-party payment processors, and you are responsible for providing accurate billing information. Please refer to our Refund Policy for details regarding cancellations or refunds.</p>

            <h3 class="text-lg font-bold text-heading mt-6">8. Privacy and Data Collection</h3>
            <p>Your use of the Website is also governed by our Privacy Policy, which outlines how we collect, use, and protect your personal information. By using the Website, you consent to the collection and use of your data as described in the Privacy Policy.</p>

            <h3 class="text-lg font-bold text-heading mt-6">9. Disclaimers</h3>
            <p>The Website and its Services are provided "as is" and "as available." We do not make any warranties or representations regarding the accuracy, completeness, or availability of the Website or its content. We are not responsible for any loss or damage arising from your use of the Website.</p>

            <h3 class="text-lg font-bold text-heading mt-6">10. Limitation of Liability</h3>
            <p>To the fullest extent permitted by law, {{ config('app.name', 'EduLab') }} and its affiliates will not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, arising out of or related to these Terms, the use of the Website, or the Services.</p>

            <h3 class="text-lg font-bold text-heading mt-6">11. Termination</h3>
            <p>We reserve the right to suspend or terminate your account and access to the Website at any time, for any reason, including for violating these Terms. Upon termination, you must cease all use of the Website and its Services.</p>

            <h3 class="text-lg font-bold text-heading mt-6">12. Amendments</h3>
            <p>We may update or modify these Terms at any time, and the updated Terms will be posted on this page with a new effective date. Your continued use of the Website after any changes constitutes your acceptance of the revised Terms.</p>

            <h3 class="text-lg font-bold text-heading mt-6">13. Governing Law</h3>
            <p>These Terms shall be governed by and construed in accordance with the laws of Uganda, without regard to its conflict of laws principles. Any disputes arising from or related to these Terms shall be resolved in the courts located in Uganda.</p>

            <h3 class="text-lg font-bold text-heading mt-6">14. Contact Information</h3>
            <p>If you have any questions about these Terms, please contact us at:</p>
            <p>Email: {{ config('app.email', 'info@edulab.com') }}<br>Address: P8MH+9A New York, USA</p>
        </div>
    </div>
</section>
@endsection