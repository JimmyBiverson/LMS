<?php

namespace App\Console\Commands;

use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Console\Command;

class SyncMeetingStatuses extends Command
{
    protected $signature = 'zoom:sync-status';

    protected $description = 'Advance Zoom meetings through the lifecycle based on the clock';

    public function handle(ZoomMeetingService $service): int
    {
        $changed = $service->syncStatuses();

        $this->info(count($changed).' meetings advanced ('.implode(',', $changed).')');

        return self::SUCCESS;
    }
}
