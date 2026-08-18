<?php

namespace App\Jobs;

use App\Mail\DynamicMail;
use App\Models\User;
use App\Models\ZoomMeeting;
use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendZoomNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public ZoomMeeting $meeting,
        public User $student,
        public string $type
    ) {
    }

    public function handle(ZoomMeetingService $service): void
    {
        try {
            $subject = $service->notificationSubject($this->meeting, $this->type);
            $body = $service->notificationBody($this->meeting, $this->type);

            Mail::to($this->student->email)->queue(
                new DynamicMail($subject, nl2br(e($body)))
            );
        } catch (\Throwable $e) {
            logger()->error('Zoom email notification failed', [
                'student' => $this->student->id,
                'meeting' => $this->meeting->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
