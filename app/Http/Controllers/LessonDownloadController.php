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
