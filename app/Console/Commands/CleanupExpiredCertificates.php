<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Illuminate\Console\Command;

class CleanupExpiredCertificates extends Command
{
    protected $signature = 'lms:cleanup-certificates {--days=365 : Delete certificates older than this many days}';
    protected $description = 'Clean up old or revoked certificates';

    public function handle(): void
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $deleted = Certificate::where('created_at', '<', $cutoff)->delete();

        $this->info("Deleted {$deleted} certificate(s) older than {$days} days.");
    }
}
