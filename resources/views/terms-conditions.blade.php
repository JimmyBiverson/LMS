@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Terms and Condition</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Terms and Condition</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl p-8 shadow-sm prose prose-sm max-w-none text-heading/70">
            <h2 class="text-2xl font-bold text-heading">Welcome to Edulab LMS Website</h2>
            <p>These Terms and Conditions ("Terms") govern your access to and use of the website (the "Service") or the website https://edulab.codexshaper.com/, including all content, services and features provided through it. By accessing or using the Website, you agree to comply with and be bound by these Terms. If you do not agree to these Terms, do not use the Website.</p>

            <h3 class="text-lg font-bold text-heading mt-6">1. User Eligibility</h3>
            <p>You must be at least [insert age] years old to use our Website. By using the Website, you represent and warrant that you meet the eligibility requirements.</p>

            <h3 class="text-lg font-bold text-heading mt-6">2. Account Registration</h3>
            <p>To access certain Services, you may be required to create an account by providing accurate and up-to-date information. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Please notify us immediately if you believe that your account has been compromised.</p>

            <h3 class="text-lg font-bold text-heading mt-6">3. Website Use</h3>
            <p>You agree to use the Website only for lawful purposes and in accordance with these Terms. You must not:</p>
            <ul class="list-disc pl-6 space-y-1">
                <li>Violate any applicable local, state, national or international law.</li>
                <li>Upload, post or transmit any unlawful, harmful, or infringing material.</li>
                <li>Engage in any activity that could disable, overload or impair the functionality of the Website.</li>
            </ul>

            <h3 class="text-lg font-bold text-heading mt-6">4. Intellectual Property</h3>
            <p>All content, including but not limited to text, images, videos, courses, software and other materials available on the Website, is owned or licensed by [your LMS website name] and is protected by intellectual property laws. You may not reproduce, distribute or create derivative works from any content without the prior written consent of the owner.</p>

            <h3 class="text-lg font-bold text-heading mt-6">5. Content Submission</h3>
            <p>If you submit any content (such as text content, comments or feedback) via the Website, you grant Edulab a worldwide, royalty-free, and irrevocable license to use, reproduce, modify and distribute that content in connection with the Website and its services.</p>

            <h3 class="text-lg font-bold text-heading mt-6">6. Course Access and Use</h3>
            <p>Access to courses, content, and materials on the Website is subject to the specific terms and conditions set forth in the course description or agreement. We reserve the right to update, change, or remove any course content at any time. Access to the course may be revoked if you violate these terms and conditions.</p>

            <h3 class="text-lg font-bold text-heading mt-6">7. Payment and Refund Policy</h3>
            <p>If you purchase a course or membership, you agree to pay all applicable fees. Payments are processed through a third-party payment processor and you are responsible for providing accurate billing information. Please see our Refund Policy for details regarding cancellations or refunds.</p>

            <h3 class="text-lg font-bold text-heading mt-6">8. Privacy and Data Collection</h3>
            <p>Your use of the Website is also governed by our Privacy Policy, which outlines how we collect, use, and protect your personal information. By using the Website, you consent to the collection and use of your data as described in the Privacy Policy.</p>

            <h3 class="text-lg font-bold text-heading mt-6">9. Disclaimer</h3>
            <p>The Website and its services are provided "as is" and "as available." We make no warranties or representations regarding the accuracy, completeness, or availability of the Website or its content. We are not responsible for any loss or damage arising from your use of the Website.</p>

            <h3 class="text-lg font-bold text-heading mt-6">10. Limitation of Liability</h3>
            <p>To the fullest extent permitted by law, [Your LMS Website Name] and its affiliates shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to lost profits, arising out of or related to these Terms, use of the Website, or the Services.</p>

            <h3 class="text-lg font-bold text-heading mt-6">11. Termination</h3>
            <p>We reserve the right to suspend or terminate your account and access to the Website at any time for any reason, including for violation of these Terms. Upon termination, you must cease all use of the Website and its Services.</p>

            <h3 class="text-lg font-bold text-heading mt-6">12. Amendments</h3>
            <p>We may update or modify these Terms at any time, and the updated Terms will be posted on this page with a new effective date. Your continued use of the Website after any changes constitutes your acceptance of the revised Terms.</p>

            <h3 class="text-lg font-bold text-heading mt-6">13. Governing Law</h3>
            <p>These Terms shall be governed by and construed in accordance with the laws of [your jurisdiction], without regard to its conflict of law principles. Any dispute arising out of or relating to these Terms shall be resolved in the courts located in [your jurisdiction].</p>

            <h3 class="text-lg font-bold text-heading mt-6">14. Contact Information</h3>
            <p>If you have any questions about these Terms, please contact us at:</p>
            <p>Email: {{ config('app.email', 'info@edulab.com') }}<br>Address: P8MH+9A New York, United States</p>
        </div>
    </div>
</section>
@endsection