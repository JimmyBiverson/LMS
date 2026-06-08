<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('lms:daily-report')->daily();
Schedule::command('lms:cleanup-notifications --days=90')->weekly();
Schedule::command('lms:cleanup-certificates --days=365')->monthly();
