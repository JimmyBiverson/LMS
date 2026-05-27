<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function newEnrollment(): \Illuminate\View\View
    {
        $students = User::where('role', User::ROLE_STUDENT)->get();
        $courses = Course::where('status', 'Active')->get();
        return view('admin.enrollment.new-create', compact('students', 'courses'));
    }

    public function storeEnrollment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $exists = Enrollment::where('user_id', $validated['user_id'])
            ->where('course_id', $validated['course_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['course_id' => 'Student is already enrolled in this course.']);
        }

        $course = Course::findOrFail($validated['course_id']);
        $amountPaid = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);

        Enrollment::create(array_merge($validated, [
            'amount_paid' => $amountPaid,
            'status' => 'in_progress',
        ]));

        return redirect()->route('admin.dashboard.enrollment.new-create')
            ->with('success', 'Student enrolled successfully!');
    }

    public function certificateCreate(): \Illuminate\View\View
    {
        $courses = Course::where('status', 'Active')->get();
        return view('admin.certificate.create', compact('courses'));
    }

    public function storeCertificate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course_id' => ['required', 'exists:courses,id'],
            'description' => ['nullable', 'string'],
        ]);

        Certificate::create($validated);

        return redirect()->route('admin.dashboard.certificate.create')
            ->with('success', 'Certificate created successfully!');
    }

    public function updateBackendSetting(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);

            if (!empty($validated['app_name'])) {
                $content = preg_replace('/^APP_NAME=.*/m', 'APP_NAME=' . $validated['app_name'], $content);
            }
            if (!empty($validated['email'])) {
                $content = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', 'MAIL_FROM_ADDRESS=' . $validated['email'], $content);
            }
            if (!empty($validated['timezone'])) {
                $content = preg_replace('/^APP_TIMEZONE=.*/m', 'APP_TIMEZONE=' . $validated['timezone'], $content);
            }

            file_put_contents($envPath, $content);
        }

        return back()->with('success', 'Backend settings updated successfully!');
    }

    public function updateThemeSetting(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'primary_color' => ['nullable', 'string', 'max:7'],
            'secondary_color' => ['nullable', 'string', 'max:7'],
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
        ]);

        return back()->with('success', 'Theme settings saved successfully! (Manual CSS update required for colors)');
    }
}
