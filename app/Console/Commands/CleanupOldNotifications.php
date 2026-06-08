<?php

namespace App\Console\Commands;

use App\Models\NotificationLog;
use Illuminate\Console\Command;

class CleanupOldNotifications extends Command
{
    protected $signature = 'lms:cleanup-notifications {--days=90 : Delete notifications older than this many days}';
    protected $description = 'Delete notification logs older than specified days';

    public function handle(): void
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $deleted = NotificationLog::where('created_at', '<', $cutoff)->delete();

        $this->info("Deleted {$deleted} notification(s) older than {$days} days.");
    }
}
