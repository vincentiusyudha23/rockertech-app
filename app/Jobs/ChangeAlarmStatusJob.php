<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChangeAlarmStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function handle(): void
    {
        // Misalkan fungsi ini mengupdate status alarm
         \Log::info('Changing alarm status to: ' . $this->status);
        update_static_option('alarm', $this->status);
    }
}
