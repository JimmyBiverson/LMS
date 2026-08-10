<?php

namespace App\Providers;

use App\Models\ZoomMeeting;
use App\Policies\ZoomMeetingPolicy;
use App\Services\Zoom\ZoomApiService;
use App\Services\Zoom\ZoomCalendarService;
use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ZoomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/zoom.php', 'zoom');

        $this->app->singleton(ZoomApiService::class, fn () => new ZoomApiService());
        $this->app->singleton(ZoomMeetingService::class, fn () => new ZoomMeetingService($this->app->make(ZoomApiService::class)));
        $this->app->singleton(ZoomCalendarService::class, fn () => new ZoomCalendarService());
    }

    public function boot(): void
    {
        Gate::policy(ZoomMeeting::class, ZoomMeetingPolicy::class);
    }
}
