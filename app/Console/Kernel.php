<?php

namespace App\Console;

use Carbon\Carbon;
use App\Jobs\ChangeAlarmStatusJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            dispatch(new ChangeAlarmStatusJob(1));
            dispatch(new ChangeAlarmStatusJob(0))->delay(now()->addSeconds(10));
        })->dailyAt(Carbon::parse(get_static_option('alarm_rest_time'))->format('H:i'));

        $schedule->call(function () {
            dispatch(new ChangeAlarmStatusJob(2));
            dispatch(new ChangeAlarmStatusJob(0))->delay(now()->addSeconds(10));
        })->dailyAt(Carbon::parse(get_static_option('alarm_off_rest_time'))->format('H:i'));

        $schedule->call(function () {
            dispatch(new ChangeAlarmStatusJob(3));
            dispatch(new ChangeAlarmStatusJob(0))->delay(now()->addSeconds(10));
        })->dailyAt(Carbon::parse(get_static_option('alarm_out_office'))->format('H:i'));

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
