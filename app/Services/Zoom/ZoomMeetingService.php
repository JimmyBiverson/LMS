<?php

namespace App\Services\Zoom;

use App\Exceptions\ZoomNotConfiguredException;
use App\Jobs\SendZoomNotification;
use App\Jobs\SyncZoomAttendance;
use App\Jobs\SyncZoomRecordings;
use App\Models\MeetProvider;
use App\Models\NotificationLog;
use App\Models\User;
use App\Models\ZoomAttendance;
use App\Models\ZoomMeeting;
use App\Models\ZoomNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Business logic for the Zoom classroom lifecycle: scheduling, rescheduling,
 * cancelling, notifications, attendance and recordings. All Zoom API calls are
 * isolated here so controllers and scheduled commands stay thin.
 */
class ZoomMeetingService
{
    public function __construct(protected ZoomApiService $api)
    {
    }

    public function api(): ZoomApiService
    {
        return $this->api;
    }

    public function buildCreatePayload(array $data): array
    {
        $start = Carbon::parse($data['start_time'], $data['timezone'] ?? 'UTC');
        $autoRecording = (bool) ($data['auto_recording'] ?? $this->api->autoRecordingEnabled());

        return [
            'topic' => $data['topic'],
            'type' => ! empty($data['is_recurring']) ? 8 : 2,
            'start_time' => $start->toIso8601String(),
            'duration' => (int) ($data['duration_minutes'] ?? 60),
            'timezone' => $data['timezone'] ?? 'UTC',
            'agenda' => $data['agenda'] ?? null,
            'password' => $data['password'] ?? null,
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
                'mute_upon_entry' => true,
                'waiting_room' => (bool) ($data['waiting_room'] ?? $this->api->config()['waiting_room'] ?? true),
                'approval_type' => 0,
                'auto_recording' => $autoRecording ? 'cloud' : 'none',
            ],
        ];
    }

    /**
     * Create a meeting in Zoom and persist its metadata locally.
     */
    public function schedule(array $data, User $actor): ZoomMeeting
    {
        if (! $this->api->isConfigured()) {
            throw new ZoomNotConfiguredException(
                'Zoom is not configured yet. Ask an admin to add the Zoom credentials in Admin > Zoom Classroom > Settings before scheduling classes.'
            );
        }

        $payload = $this->buildCreatePayload($data);
        $zoom = $this->api->createMeeting($payload);

        $meeting = ZoomMeeting::create([
            'meet_provider_id' => $this->providerId(),
            'course_id' => $data['course_id'] ?? null,
            'lesson_id' => $data['lesson_id'] ?? null,
            'instructor_id' => $data['instructor_id'] ?? $actor->id,
            'created_by' => $actor->id,
            'scope_type' => $data['scope_type'] ?? ZoomMeeting::SCOPE_COURSE,
            'topic' => $data['topic'],
            'agenda' => $data['agenda'] ?? null,
            'start_time' => Carbon::parse($data['start_time'], $data['timezone'] ?? 'UTC'),
            'timezone' => $data['timezone'] ?? 'UTC',
            'duration_minutes' => (int) ($data['duration_minutes'] ?? 60),
            'meeting_type' => $payload['type'],
            'zoom_meeting_id' => (string) ($zoom['id'] ?? ''),
            'zoom_uuid' => $zoom['uuid'] ?? null,
            'join_url' => $zoom['join_url'] ?? null,
            'start_url' => $zoom['start_url'] ?? null,
            'password' => $zoom['password'] ?? $data['password'] ?? null,
            'host_email' => $data['host_email'] ?? $this->api->hostEmail(),
            'status' => ZoomMeeting::STATUS_SCHEDULED,
            'is_recurring' => ! empty($data['is_recurring']),
            'recording_status' => ZoomMeeting::RECORDING_NONE,
        ]);

        $this->invalidateMeetingCaches($meeting);

        $this->notifyStudents($meeting, ZoomNotification::TYPE_SCHEDULED);

        return $meeting;
    }

    public function reschedule(ZoomMeeting $meeting, array $data): ZoomMeeting
    {
        $payload = $this->buildCreatePayload($data);
        $this->api->updateMeeting($meeting->zoom_meeting_id, $payload);

        $meeting->update([
            'course_id' => $data['course_id'] ?? $meeting->course_id,
            'lesson_id' => $data['lesson_id'] ?? $meeting->lesson_id,
            'instructor_id' => $data['instructor_id'] ?? $meeting->instructor_id,
            'scope_type' => $data['scope_type'] ?? $meeting->scope_type,
            'topic' => $data['topic'],
            'agenda' => $data['agenda'] ?? null,
            'start_time' => Carbon::parse($data['start_time'], $data['timezone'] ?? 'UTC'),
            'timezone' => $data['timezone'] ?? 'UTC',
            'duration_minutes' => (int) ($data['duration_minutes'] ?? 60),
            'status' => ZoomMeeting::STATUS_SCHEDULED,
        ]);

        $this->invalidateMeetingCaches($meeting);

        $this->notifyStudents($meeting, ZoomNotification::TYPE_RESCHEDULED);

        return $meeting;
    }

    public function cancel(ZoomMeeting $meeting): void
    {
        if ($meeting->zoom_meeting_id) {
            $this->api->deleteMeeting($meeting->zoom_meeting_id);
        }

        $meeting->update([
            'status' => ZoomMeeting::STATUS_CANCELLED,
            'zoom_meeting_id' => null,
            'join_url' => null,
            'start_url' => null,
        ]);

        $this->invalidateMeetingCaches($meeting);

        $this->notifyStudents($meeting, ZoomNotification::TYPE_CANCELLED);
    }

    public function start(ZoomMeeting $meeting): void
    {
        if ($meeting->status === ZoomMeeting::STATUS_CANCELLED || $meeting->status === ZoomMeeting::STATUS_ENDED) {
            return;
        }

        $meeting->update(['status' => ZoomMeeting::STATUS_LIVE]);

        $this->invalidateMeetingCaches($meeting);
    }

    public function markEnded(ZoomMeeting $meeting): void
    {
        $meeting->update([
            'status' => ZoomMeeting::STATUS_ENDED,
            'last_synced_at' => now(),
        ]);

        SyncZoomAttendance::dispatch($meeting->id);
        SyncZoomRecordings::dispatch($meeting->id);

        $this->invalidateMeetingCaches($meeting);
    }

    /**
     * Persist computed lifecycle statuses for all scheduled/starting/live meetings.
     * Runs every minute via the scheduler.
     */
    public function syncStatuses(): array
    {
        $changed = [];

        ZoomMeeting::query()
            ->whereIn('status', [
                ZoomMeeting::STATUS_SCHEDULED,
                ZoomMeeting::STATUS_STARTING_SOON,
                ZoomMeeting::STATUS_LIVE,
            ])
            ->each(function (ZoomMeeting $meeting) use (&$changed) {
                $computed = $meeting->computeStatus();

                if ($meeting->status === ZoomMeeting::STATUS_LIVE && $computed === ZoomMeeting::STATUS_LIVE) {
                    return;
                }

                if ($computed === ZoomMeeting::STATUS_LIVE && $meeting->status !== ZoomMeeting::STATUS_LIVE) {
                    $this->start($meeting);
                    $changed[] = $meeting->id;

                    return;
                }

                if ($computed === ZoomMeeting::STATUS_ENDED) {
                    $this->markEnded($meeting);
                    $changed[] = $meeting->id;

                    return;
                }

                if ($meeting->status !== $computed) {
                    $meeting->update(['status' => $computed]);
                    $this->invalidateMeetingCaches($meeting);
                    $changed[] = $meeting->id;
                }
            });

        return $changed;
    }

    /**
     * Send "starting soon" notifications once per meeting.
     */
    public function notifyStartingSoon(): int
    {
        $threshold = (int) config('zoom.starting_soon_minutes', 15);

        $sent = 0;

        ZoomMeeting::query()
            ->where('status', ZoomMeeting::STATUS_STARTING_SOON)
            ->whereBetween('start_time', [now()->subMinutes(1), now()->addMinutes($threshold)])
            ->whereDoesntHave('notifications', fn ($q) => $q->where('type', ZoomNotification::TYPE_STARTING_SOON))
            ->each(function (ZoomMeeting $meeting) use (&$sent) {
                $sent += $this->notifyStudents($meeting, ZoomNotification::TYPE_STARTING_SOON);
            });

        return $sent;
    }

    /**
     * Push an in-app notification to every enrolled student plus a queued email.
     * Returns the number of students notified.
     */
    public function notifyStudents(ZoomMeeting $meeting, string $type, ?string $customMessage = null): int
    {
        $ids = $meeting->enrolledStudentIds();

        if (empty($ids)) {
            return 0;
        }

        $students = User::whereIn('id', $ids)->where('status', User::STATUS_ACTIVE)->get();

        foreach ($students as $student) {
            $subject = $this->notificationSubject($meeting, $type);
            $body = $customMessage ?: $this->notificationBody($meeting, $type);
            $link = $this->meetingUrl($meeting, $student);

            ZoomNotification::create([
                'meeting_id' => $meeting->id,
                'user_id' => $student->id,
                'type' => $type,
                'channel' => ZoomNotification::CHANNEL_IN_APP,
                'subject' => $subject,
                'body' => $body,
                'link' => $link,
                'is_sent' => true,
                'sent_at' => now(),
            ]);

            NotificationLog::create([
                'user_id' => $student->id,
                'type' => 'zoom',
                'subject' => $subject,
                'body' => $body,
                'link' => $link,
                'channel' => 'in_app',
                'is_read' => false,
                'sent_at' => now(),
            ]);

            SendZoomNotification::dispatch($meeting, $student, $type)->onQueue('zoom');
        }

        return $students->count();
    }

    public function notificationSubject(ZoomMeeting $meeting, string $type): string
    {
        return match ($type) {
            ZoomNotification::TYPE_SCHEDULED => 'New class scheduled: '.$meeting->topic,
            ZoomNotification::TYPE_RESCHEDULED => 'Class rescheduled: '.$meeting->topic,
            ZoomNotification::TYPE_CANCELLED => 'Class cancelled: '.$meeting->topic,
            ZoomNotification::TYPE_STARTING_SOON => 'Starting soon: '.$meeting->topic,
            ZoomNotification::TYPE_RECORDING => 'Recording available: '.$meeting->topic,
            ZoomNotification::TYPE_REMINDER => 'Reminder: '.$meeting->topic,
            default => $meeting->topic,
        };
    }

    public function notificationBody(ZoomMeeting $meeting, string $type): string
    {
        $when = $meeting->start_time->setTimezone($meeting->timezone)->format('D, M j, Y g:i A');

        return match ($type) {
            ZoomNotification::TYPE_SCHEDULED => "A Zoom class for {$meeting->topic} has been scheduled for {$when} ({$meeting->timezone}). Join from your dashboard.",
            ZoomNotification::TYPE_RESCHEDULED => "The Zoom class \"{$meeting->topic}\" has been moved to {$when} ({$meeting->timezone}).",
            ZoomNotification::TYPE_CANCELLED => "The Zoom class \"{$meeting->topic}\" (scheduled for {$when}) has been cancelled.",
            ZoomNotification::TYPE_STARTING_SOON => "Your class \"{$meeting->topic}\" starts at {$when}. Click Join Live Lesson to enter.",
            ZoomNotification::TYPE_RECORDING => "The recording for \"{$meeting->topic}\" is now available.",
            ZoomNotification::TYPE_REMINDER => "A reminder for your upcoming class \"{$meeting->topic}\" at {$when}.",
            default => $meeting->topic,
        };
    }

    public function meetingUrl(ZoomMeeting $meeting, User $user): string
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return route('zoom.admin.show', $meeting);
        }

        if ($user->isInstructor()) {
            return route('zoom.instructor.show', $meeting);
        }

        return route('zoom.show', $meeting);
    }

    /**
     * Sync attendance from Zoom's participant reports and/or the LMS join
     * clicks, then classify each enrolled student.
     */
    public function syncAttendance(ZoomMeeting $meeting): void
    {
        $students = User::whereIn('id', $meeting->enrolledStudentIds())->get();
        $participants = collect();

        try {
            $id = $meeting->zoom_uuid ?: $meeting->zoom_meeting_id;
            if ($id) {
                $participants = collect($this->api->listPastMeetingParticipants($id))
                    ->keyBy(fn ($p) => strtolower((string) ($p['user_email'] ?? '')));
            }
        } catch (\Throwable $e) {
            logger()->warning('Zoom participants fetch failed for meeting '.$meeting->id.': '.$e->getMessage());
        }

        $start = $meeting->start_time;
        $end = $meeting->endTime();

        foreach ($students as $student) {
            $existing = ZoomAttendance::query()
                ->where('meeting_id', $meeting->id)
                ->where('student_id', $student->id)
                ->first();

            $participant = $participants->get(strtolower((string) $student->email));

            if ($existing && $existing->join_time) {
                if ($participant && ! $existing->leave_time) {
                    $existing->update([
                        'leave_time' => isset($participant['leave_time']) ? Carbon::parse($participant['leave_time']) : null,
                        'duration_minutes' => (int) ($participant['duration'] ?? $existing->duration_minutes),
                        'source' => ZoomAttendance::SOURCE_ZOOM,
                    ]);
                }

                $existing->update([
                    'status' => $this->classifyAttendance($meeting, $existing->join_time, $existing->leave_time),
                ]);

                continue;
            }

            if (! $participant) {
                ZoomAttendance::updateOrCreate(
                    ['meeting_id' => $meeting->id, 'student_id' => $student->id],
                    [
                        'join_time' => null,
                        'leave_time' => null,
                        'duration_minutes' => 0,
                        'status' => ZoomAttendance::STATUS_ABSENT,
                        'source' => ZoomAttendance::SOURCE_ZOOM,
                    ]
                );

                continue;
            }

            $join = isset($participant['join_time']) ? Carbon::parse($participant['join_time']) : null;
            $leave = isset($participant['leave_time']) ? Carbon::parse($participant['leave_time']) : null;

            ZoomAttendance::updateOrCreate(
                ['meeting_id' => $meeting->id, 'student_id' => $student->id],
                [
                    'join_time' => $join,
                    'leave_time' => $leave,
                    'duration_minutes' => (int) ($participant['duration'] ?? 0),
                    'status' => $this->classifyAttendance($meeting, $join, $leave, $start, $end),
                    'source' => ZoomAttendance::SOURCE_ZOOM,
                ]
            );
        }

        $meeting->update([
            'has_attendance' => true,
            'last_synced_at' => now(),
        ]);

        $this->invalidateMeetingCaches($meeting);
    }

    protected function classifyAttendance(ZoomMeeting $meeting, ?Carbon $join, ?Carbon $leave, ?Carbon $start = null, ?Carbon $end = null): string
    {
        $start = $start ?? $meeting->start_time;
        $end = $end ?? $meeting->endTime();

        if (! $join) {
            return ZoomAttendance::STATUS_ABSENT;
        }

        $lateAt = $start->copy()->addMinutes((int) config('zoom.late_minutes', 10));
        if ($join->gt($lateAt)) {
            return ZoomAttendance::STATUS_LATE;
        }

        if ($leave && $leave->lt($end->copy()->subMinutes((int) config('zoom.early_leave_minutes', 15)))) {
            return ZoomAttendance::STATUS_LEFT_EARLY;
        }

        return ZoomAttendance::STATUS_PRESENT;
    }

    /**
     * Pull cloud recordings for an ended meeting and mark them available.
     */
    public function syncRecordings(ZoomMeeting $meeting): void
    {
        if (! $meeting->zoom_meeting_id) {
            return;
        }

        try {
            $data = $this->api->listMeetingRecordings($meeting->zoom_meeting_id);
        } catch (\Throwable $e) {
            logger()->warning('Zoom recordings fetch failed for meeting '.$meeting->id.': '.$e->getMessage());

            $meeting->update(['recording_status' => ZoomMeeting::RECORDING_NONE]);

            return;
        }

        $files = collect($data['recording_files'] ?? []);

        if ($files->isEmpty()) {
            $meeting->update(['recording_status' => ZoomMeeting::RECORDING_NONE]);

            return;
        }

        $video = $files->first();
        $recordingUrl = $data['share_url'] ?? $video['download_url'] ?? null;

        $meeting->update([
            'recording_status' => ZoomMeeting::RECORDING_AVAILABLE,
            'recording_url' => $recordingUrl,
            'recording_password' => $data['password'] ?? null,
            'recording_files' => $files->all(),
            'last_synced_at' => now(),
        ]);

        $this->invalidateMeetingCaches($meeting);

        if (! ZoomNotification::where('meeting_id', $meeting->id)->where('type', ZoomNotification::TYPE_RECORDING)->exists()) {
            $this->notifyStudents($meeting, ZoomNotification::TYPE_RECORDING);
        }
    }

    /**
     * Record a student's join click (LMS-side attendance).
     */
    public function recordJoin(ZoomMeeting $meeting, User $student): ZoomAttendance
    {
        $existing = ZoomAttendance::query()
            ->where('meeting_id', $meeting->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing && $existing->join_time) {
            return $existing;
        }

        $join = now();

        return ZoomAttendance::updateOrCreate(
            ['meeting_id' => $meeting->id, 'student_id' => $student->id],
            [
                'join_time' => $join,
                'leave_time' => null,
                'duration_minutes' => 0,
                'status' => $this->classifyAttendance($meeting, $join),
                'source' => ZoomAttendance::SOURCE_MANUAL,
            ]
        );
    }

    protected function providerId(): ?int
    {
        return MeetProvider::query()->where('slug', 'zoom')->value('id');
    }

    public function invalidateMeetingCaches(ZoomMeeting $meeting): void
    {
        if ($meeting->course_id) {
            Cache::forget('zoom.course.'.$meeting->course_id);
        }

        Cache::forget('zoom.institution');
        Cache::forget('zoom.calendar.institution');
    }
}
