<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Zoom Classroom settings
    |--------------------------------------------------------------------------
    */

    // Minutes before start_time when a meeting flips to "starting soon".
    'starting_soon_minutes' => env('ZOOM_STARTING_SOON_MINUTES', 15),

    // A student joining more than this many minutes after start is marked "late".
    'late_minutes' => env('ZOOM_LATE_MINUTES', 10),

    // A student leaving this many minutes before the end is marked "left early".
    'early_leave_minutes' => env('ZOOM_EARLY_LEAVE_MINUTES', 15),

    // Enable automatic attendance tracking on the join action.
    'track_join_attendance' => env('ZOOM_TRACK_ATTENDANCE', true),

    // Default timezone offered when no school timezone is configured.
    'default_timezone' => env('APP_TIMEZONE', 'UTC'),

    // How long (seconds) per-user meeting lists are cached.
    'cache_ttl' => (int) env('ZOOM_CACHE_TTL', 60),

];
