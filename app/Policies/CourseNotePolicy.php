<?php

namespace App\Policies;

use App\Models\CourseNote;
use App\Models\Enrollment;
use App\Models\User;

class CourseNotePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isInstructor() || $user->isStudent();
    }

    public function view(User $user, CourseNote $courseNote): bool
    {
        if ($user->isInstructor()) {
            return $courseNote->user_id === $user->id || $courseNote->course?->user_id === $user->id;
        }

        if ($user->isStudent()) {
            return $courseNote->status === 'published'
                && $courseNote->course !== null
                && Enrollment::where('user_id', $user->id)
                    ->where('course_id', $courseNote->course_id)
                    ->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isInstructor();
    }

    public function update(User $user, CourseNote $courseNote): bool
    {
        return $user->isInstructor() && ($courseNote->user_id === $user->id || $courseNote->course?->user_id === $user->id);
    }

    public function delete(User $user, CourseNote $courseNote): bool
    {
        return $user->isInstructor() && ($courseNote->user_id === $user->id || $courseNote->course?->user_id === $user->id);
    }

    public function download(User $user, CourseNote $courseNote): bool
    {
        return $this->view($user, $courseNote) && $courseNote->allow_download && !empty($courseNote->attachment_path);
    }
}
