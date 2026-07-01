<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (env('FORCE_HTTPS', false)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $tmpPath = storage_path('tmp');

        if (!is_dir($tmpPath)) {
            @mkdir($tmpPath, 0755, true);
        }

        if (empty(ini_get('upload_tmp_dir')) || !is_dir(ini_get('upload_tmp_dir'))) {
            @ini_set('upload_tmp_dir', $tmpPath);
        }

        if (empty(ini_get('sys_temp_dir')) || !is_dir(ini_get('sys_temp_dir'))) {
            @ini_set('sys_temp_dir', $tmpPath);
        }

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
        });

        Model::preventLazyLoading(!$this->app->environment('production'));

        View::composer('*', function ($view) {
            $school = SchoolSetting::getInstance();

            if ($school->timezone && $school->timezone !== config('app.timezone')) {
                config(['app.timezone' => $school->timezone]);
                date_default_timezone_set($school->timezone);
                Carbon::setLocale(str_replace('-', '_', $school->language ?? 'en'));
            }

            if ($school->language && $school->language !== app()->getLocale()) {
                app()->setLocale($school->language);
            }

            $view->with('school', $school);
        });
    }
}
