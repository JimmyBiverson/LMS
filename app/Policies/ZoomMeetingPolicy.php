<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use App\Models\ZoomMeeting;

class ZoomMeetingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isInstructor();
    }

    /**
     * View: admin/staff always; instructors for courses they teach or where they
     * host; students only for their enrolled courses (or institution-wide).
     */
    public function view(User $user, ZoomMeeting $meeting): bool
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        if ($this->manages($user, $meeting)) {
            return true;
        }

        if ($meeting->scope_type === ZoomMeeting::SCOPE_INSTITUTION) {
            return $user->isStudent();
        }

        if ($user->isStudent() && $meeting->course_id) {
            return $meeting->course?->enrollments()
                ->where('user_id', $user->id)
                ->whereIn('status', ['in_progress', 'completed'])
                ->exists() ?? false;
        }

        return false;
    }

    public function join(User $user, ZoomMeeting $meeting): bool
    {
        if (! $this->view($user, $meeting)) {
            return false;
        }

        return in_array($meeting->status, [
            ZoomMeeting::STATUS_SCHEDULED,
            ZoomMeeting::STATUS_STARTING_SOON,
            ZoomMeeting::STATUS_LIVE,
        ], true);
    }

    public function start(User $user, ZoomMeeting $meeting): bool
    {
        return $this->manages($user, $meeting);
    }

    public function update(User $user, ZoomMeeting $meeting): bool
    {
        return $this->manages($user, $meeting) && ! $meeting->isEnded();
    }

    public function cancel(User $user, ZoomMeeting $meeting): bool
    {
        return $this->manages($user, $meeting) && ! $meeting->isEnded();
    }

    public function manageAttendance(User $user, ZoomMeeting $meeting): bool
    {
        return $this->manages($user, $meeting);
    }

    public function publishRecording(User $user, ZoomMeeting $meeting): bool
    {
        return $this->manages($user, $meeting) && $meeting->hasRecording();
    }

    public function announce(User $user, ZoomMeeting $meeting): bool
    {
        return $this->manages($user, $meeting);
    }

    public function manage(User $user, ZoomMeeting $meeting): bool
    {
        return $this->manages($user, $meeting);
    }

    protected function manages(User $user, ZoomMeeting $meeting): bool
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        if (! $user->isInstructor()) {
            return false;
        }

        if ($meeting->instructor_id === $user->id) {
            return true;
        }

        if ($meeting->course_id) {
            $course = $meeting->course_id instanceof Course
                ? $meeting->course_id
                : Course::find($meeting->course_id);

            if ($course && ($course->user_id === $user->id || $course->instructor_id === $user->id)) {
                return true;
            }
        }

        return false;
    }
}
