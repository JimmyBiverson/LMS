<?php

namespace App\Console\Commands;

use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Console\Command;

class NotifyUpcomingMeetings extends Command
{
    protected $signature = 'zoom:notify-upcoming';

    protected $description = 'Send "starting soon" notifications for meetings about to begin';

    public function handle(ZoomMeetingService $service): int
    {
        $sent = $service->notifyStartingSoon();

        $this->info($sent.' students notified for upcoming meetings');

        return self::SUCCESS;
    }
}
