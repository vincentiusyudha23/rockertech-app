<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\StaticOption;
use App\Jobs\BackupPrecenseJob;
use App\Jobs\ChangeAlarmStatusJob;
use App\Http\Controllers\AdminController;
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

        $staticOptions = StaticOption::pluck('option_value', 'option_name')->toArray();

        $rest_time = $staticOptions['alarm_rest_time'] ?? '12:00';
        $rest_off_time = $staticOptions['alarm_off_rest_time'] ?? '13:00';
        $out_office = $staticOptions['alarm_out_office'] ?? '17:00';

        $schedule->call(function(){
            app(AdminController::class)->set_status_alarm(1);
        })->dailyAt(Carbon::parse($rest_time)->format('H:i'));

        $schedule->call(function(){
            app(AdminController::class)->set_status_alarm(2);
        })->dailyAt(Carbon::parse($rest_off_time)->format('H:i'));

        $schedule->call(function(){
            app(AdminController::class)->set_status_alarm(3);
        })->dailyAt(Carbon::parse($out_office)->format('H:i'));

        // $schedule->job(new BackupPrecenseJob())->dailyAt('11:30');
        $schedule->job(new BackupPrecenseJob())->monthlyOn(now()->endOfMonth()->day, '23:59');
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
