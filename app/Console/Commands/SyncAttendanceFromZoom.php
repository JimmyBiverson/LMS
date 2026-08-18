<?php

namespace App\Console\Commands;

use App\Models\ZoomMeeting;
use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Console\Command;

class SyncAttendanceFromZoom extends Command
{
    protected $signature = 'zoom:sync-attendance {--days=7 : only meetings ended within this many days}';

    protected $description = 'Pull Zoom participant reports and classify attendance for ended meetings';

    public function handle(ZoomMeetingService $service): int
    {
        $days = (int) $this->option('days');

        $meetings = ZoomMeeting::query()
            ->where('status', ZoomMeeting::STATUS_ENDED)
            ->where('start_time', '>=', now()->subDays($days))
            ->where('has_attendance', false)
            ->orderBy('start_time')
            ->limit(50)
            ->get();

        foreach ($meetings as $meeting) {
            $this->info("Syncing attendance for meeting #{$meeting->id} ({$meeting->topic})");
            $service->syncAttendance($meeting);
        }

        $this->info('Processed '.$meetings->count().' meetings');

        return self::SUCCESS;
    }
}
