<?php

namespace App\Console\Commands;

use App\Models\ZoomMeeting;
use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Console\Command;

class SyncMeetingRecordings extends Command
{
    protected $signature = 'zoom:sync-recordings {--days=14 : only meetings ended within this many days}';

    protected $description = 'Fetch cloud recordings for ended meetings';

    public function handle(ZoomMeetingService $service): int
    {
        $days = (int) $this->option('days');

        $meetings = ZoomMeeting::query()
            ->where('status', ZoomMeeting::STATUS_ENDED)
            ->where('start_time', '>=', now()->subDays($days))
            ->whereNotNull('zoom_meeting_id')
            ->where(function ($q) {
                $q->where('recording_status', '!=', ZoomMeeting::RECORDING_AVAILABLE)
                    ->orWhereNull('recording_status');
            })
            ->orderBy('start_time')
            ->limit(50)
            ->get();

        foreach ($meetings as $meeting) {
            $this->info("Syncing recordings for meeting #{$meeting->id} ({$meeting->topic})");
            $service->syncRecordings($meeting);
        }

        $this->info('Processed '.$meetings->count().' meetings');

        return self::SUCCESS;
    }
}
