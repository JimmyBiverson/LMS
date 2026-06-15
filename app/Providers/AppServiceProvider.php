<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
    }
}
