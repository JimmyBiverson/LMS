# Instructor Fixes Implementation Plan

## Step 1: Remove ring from /instructors page
**File:** `resources/views/instructors/index.blade.php`
**Change:** Line 25 — remove `ring-2 ring-primary-100`
From: `<div class="w-24 h-24 rounded-full overflow-hidden mx-auto mb-4 ring-2 ring-primary-100">`
To:   `<div class="w-24 h-24 rounded-full overflow-hidden mx-auto mb-4">`

## Step 2: Fix homepage instructor images
**File:** `resources/views/components/instructor-card.blade.php`
**Change 1:** Replace `h-48` with `aspect-square` on the image container div
**Change 2:** Remove the gradient background `bg-gradient-to-b from-primary-100 to-primary-50`

**Old:**
```blade
<div class="h-48 bg-gradient-to-b from-primary-100 to-primary-50 flex items-center justify-center overflow-hidden">
    @if($image)
        <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" loading="lazy" class="w-full h-full object-cover">
    @else
        <div class="w-24 h-24 rounded-full bg-white/80 flex items-center justify-center">
            <i class="ri-user-smile-line text-5xl text-primary/40"></i>
        </div>
    @endif
</div>
```

**New:**
```blade
<div class="aspect-square bg-primary-50 flex items-center justify-center overflow-hidden">
    @if($image)
        <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" loading="lazy" class="w-full h-full object-cover">
    @else
        <div class="w-24 h-24 rounded-full bg-white/80 flex items-center justify-center">
            <i class="ri-user-smile-line text-5xl text-primary/40"></i>
        </div>
    @endif
</div>
```

## Step 3: Create LessonDownloadController
**New file:** `app/Http/Controllers/LessonDownloadController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;

class LessonDownloadController extends Controller
{
    public function downloadVideo(Lesson $lesson)
    {
        $course = $lesson->course;

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to download videos.');
        }

        $isEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled && !auth()->user()->isAdmin() && auth()->user()->id !== $course->user_id) {
            return redirect()->to('/courses/' . $course->slug . '/checkout')
                ->with('error', 'You must be enrolled in this course to download videos.');
        }

        if (!$lesson->video_file || !Storage::disk('public')->exists($lesson->video_file)) {
            abort(404, 'Video file not found.');
        }

        return Storage::disk('public')->download($lesson->video_file);
    }

    public function downloadDocument(Lesson $lesson)
    {
        $course = $lesson->course;

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to download documents.');
        }

        $isEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled && !auth()->user()->isAdmin() && auth()->user()->id !== $course->user_id) {
            return redirect()->to('/courses/' . $course->slug . '/checkout')
                ->with('error', 'You must be enrolled in this course to download documents.');
        }

        if (!$lesson->document_file || !Storage::disk('public')->exists($lesson->document_file)) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('public')->download($lesson->document_file);
    }
}
```

## Step 4: Add download routes
**File:** `routes/web.php`
**Add after the lesson viewing route (around line 190):**

```php
Route::get('/lessons/{lesson}/download/video', [\App\Http\Controllers\LessonDownloadController::class, 'downloadVideo'])->name('lessons.download.video')->middleware('auth');
Route::get('/lessons/{lesson}/download/document', [\App\Http\Controllers\LessonDownloadController::class, 'downloadDocument'])->name('lessons.download.document')->middleware('auth');
```

Note: Using `->middleware('auth')` so guests are automatically redirected to login.

## Step 5: Update video-player.blade.php download buttons
**File:** `resources/views/components/video-player.blade.php`

### 5a. Document download section (~lines 194-204)
**Old:**
```blade
@if($lesson->document_file)
<a href="{{ asset('storage/' . $lesson->document_file) }}" target="_blank" class="flex items-center gap-3 p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 transition-colors group cursor-pointer">
    <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
        <i class="ri-file-pdf-line text-xl"></i>
    </div>
    <div class="flex-1">
        <p class="font-semibold text-heading text-sm">Course Material</p>
        <p class="text-xs text-heading/60">PDF Document</p>
    </div>
    <i class="ri-download-line text-heading/40 group-hover:text-primary transition-colors"></i>
</a>
@endif
```

**New:**
```blade
@if($lesson->document_file)
    @auth
        @if($enrolled)
            <a href="{{ route('lessons.download.document', $lesson) }}" target="_blank" class="flex items-center gap-3 p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 transition-colors group cursor-pointer">
                <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                    <i class="ri-file-pdf-line text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-heading text-sm">Course Material</p>
                    <p class="text-xs text-heading/60">PDF Document</p>
                </div>
                <i class="ri-download-line text-heading/40 group-hover:text-primary transition-colors"></i>
            </a>
        @else
            <a href="{{ route('lessons.download.document', $lesson) }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 transition-colors group cursor-not-allowed opacity-70">
                <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                    <i class="ri-lock-line text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-heading text-sm">Course Material</p>
                    <p class="text-xs text-amber-600 font-medium">Enroll to Download</p>
                </div>
                <i class="ri-arrow-right-s-line text-heading/40"></i>
            </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 transition-colors group cursor-pointer opacity-70">
            <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                <i class="ri-lock-line text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-heading text-sm">Course Material</p>
                <p class="text-xs text-heading/60">Login to Access</p>
            </div>
            <i class="ri-arrow-right-s-line text-heading/40"></i>
        </a>
    @endauth
@endif
```

### 5b. Video download section (~lines 208-218)
**Old:**
```blade
@if($lesson->video_file)
<a href="{{ asset('storage/' . $lesson->video_file) }}" download class="flex items-center gap-3 p-4 rounded-xl border border-purple-200 bg-purple-50 hover:bg-purple-100 transition-colors group cursor-pointer">
    <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
        <i class="ri-video-download-line text-xl"></i>
    </div>
    <div class="flex-1">
        <p class="font-semibold text-heading text-sm">Download Video</p>
        @php $fileSize = $lesson->video_file && Storage::disk('public')->exists($lesson->video_file) ? Storage::disk('public')->size($lesson->video_file) : 0; @endphp
        <p class="text-xs text-heading/60">{{ $fileSize > 0 ? round($fileSize / 1048576, 1) . ' MB' : 'Available for download' }}</p>
    </div>
    <i class="ri-download-line text-heading/40 group-hover:text-primary transition-colors"></i>
</a>
@endif
```

**New:**
```blade
@if($lesson->video_file)
    @php $fileSize = $lesson->video_file && Storage::disk('public')->exists($lesson->video_file) ? Storage::disk('public')->size($lesson->video_file) : 0; @endphp
    @auth
        @if($enrolled)
            <a href="{{ route('lessons.download.video', $lesson) }}" class="flex items-center gap-3 p-4 rounded-xl border border-purple-200 bg-purple-50 hover:bg-purple-100 transition-colors group cursor-pointer">
                <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                    <i class="ri-video-download-line text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-heading text-sm">Download Video</p>
                    <p class="text-xs text-heading/60">{{ $fileSize > 0 ? round($fileSize / 1048576, 1) . ' MB' : 'Available for download' }}</p>
                </div>
                <i class="ri-download-line text-heading/40 group-hover:text-primary transition-colors"></i>
            </a>
        @else
            <div class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 opacity-70">
                <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                    <i class="ri-lock-line text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-heading text-sm">Download Video</p>
                    <p class="text-xs text-amber-600 font-medium">Enroll to Download</p>
                </div>
            </div>
        @endif
    @else
        <a href="{{ route('login') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 transition-colors group cursor-pointer opacity-70">
            <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                <i class="ri-lock-line text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-heading text-sm">Download Video</p>
                <p class="text-xs text-heading/60">Login to Access</p>
            </div>
            <i class="ri-arrow-right-s-line text-heading/40"></i>
        </a>
    @endauth
@endif
```

## Verification
After making all changes, run:
```bash
composer test
```
To verify all 311 tests still pass.
