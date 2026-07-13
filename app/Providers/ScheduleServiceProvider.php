<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Scheduling lives in routes/console.php (Laravel 11 style).
        // Registering it here too caused the command to run twice a minute
        // and send duplicate feeding notifications.

    }
}