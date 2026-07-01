<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\SiteLanguage;
use App\Models\Timezone;
use App\Models\User;
use App\Notifications\InstructorApproved;
use App\Notifications\InstructorRejected;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $currencies = Currency::where('status', 'active')->get();
        $languages = SiteLanguage::where('status', 'active')->get();
        $timezones = Timezone::where('status', 'active')->get();
        $pending = User::where('role', User::ROLE_INSTRUCTOR)
            ->where('is_approved', false)
            ->latest()->get();
        $approved = User::where('role', User::ROLE_INSTRUCTOR)
            ->where('is_approved', true)
            ->latest()->get();
        return view('admin.settings.index', compact('currencies', 'languages', 'timezones', 'pending', 'approved'));
    }

    public function school(): View
    {
        $currencies = Currency::where('status', 'active')->get();
        $languages = SiteLanguage::where('status', 'active')->get();
        $timezones = Timezone::where('status', 'active')->get();
        return view('admin.settings.school', compact('currencies', 'languages', 'timezones'));
    }

    public function updateSchool(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => 'nullable|string|max:255',
            'school_email' => 'nullable|email|max:255',
            'school_phone' => 'nullable|string|max:50',
            'school_address' => 'nullable|string|max:1000',
            'currency_symbol' => 'nullable|string|max:10',
            'currency_code' => 'nullable|string|max:10',
            'currency_position' => 'nullable|in:left,right',
            'timezone' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:10',
            'favicon' => 'nullable|file|mimes:ico,png,svg,jpg,jpeg,webp|max:2048',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'accent_color' => 'nullable|string|max:20',
            'custom_css' => 'nullable|string',
            'slider_video' => 'nullable|mimes:mp4,webm,ogg|max:102400',
        ]);

        $school = SchoolSetting::getInstance();

        foreach (['school_name', 'school_email', 'school_phone', 'school_address',
            'currency_symbol', 'currency_code', 'currency_position',
            'timezone', 'language', 'primary_color', 'secondary_color', 'accent_color', 'custom_css'] as $field) {
            if ($request->has($field)) {
                $school->$field = $validated[$field] ?? $school->$field;
            }
        }

        if ($request->hasFile('favicon')) {
            if ($school->favicon) {
                Storage::disk('public')->delete($school->favicon);
            }
            $school->favicon = $request->file('favicon')->store('settings', 'public');
        }

        if ($request->hasFile('site_logo')) {
            if ($school->site_logo) {
                Storage::disk('public')->delete($school->site_logo);
            }
            $school->site_logo = $request->file('site_logo')->store('settings', 'public');
        }

        if ($request->hasFile('slider_video')) {
            if ($school->slider_video) {
                Storage::disk('public')->delete($school->slider_video);
            }
            $school->slider_video = $request->file('slider_video')->store('settings/videos', 'public');
        }

        $school->save();

        Cache::forget('school_settings');

        return back()->with('success', 'School settings saved successfully!');
    }

    public function approveInstructors(): View
    {
        $pending = User::where('role', User::ROLE_INSTRUCTOR)
            ->where('is_approved', false)
            ->latest()->get();
        $approved = User::where('role', User::ROLE_INSTRUCTOR)
            ->where('is_approved', true)
            ->latest()->get();
        return view('admin.settings.approve-instructors', compact('pending', 'approved'));
    }

    public function approveInstructor(User $user): RedirectResponse
    {
        if ($user->role !== User::ROLE_INSTRUCTOR) {
            return back()->with('error', 'User is not an instructor.');
        }
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'status' => User::STATUS_ACTIVE,
        ]);

        InstructorApproved::send($user);

        return back()->with('success', "{$user->name} has been approved as an instructor.");
    }

    public function disapproveInstructor(User $user): RedirectResponse
    {
        if ($user->role !== User::ROLE_INSTRUCTOR) {
            return back()->with('error', 'User is not an instructor.');
        }
        $user->update([
            'is_approved' => false,
            'approved_at' => null,
            'status' => User::STATUS_INACTIVE,
        ]);

        InstructorRejected::send($user);

        return back()->with('success', "{$user->name} has been disapproved.");
    }
}
