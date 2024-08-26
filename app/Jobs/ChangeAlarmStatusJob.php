<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\StaticOption;
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
        try {
            \Log::info('Changing alarm status to '.$this->status);

            StaticOption::updateOrCreate(
                ['option_name' => 'alarm'],
                ['option_name' => 'alarm', 'option_value' => $this->status]
            );

            \Log::info('Successfully updated alarm status to '.$this->status);

        } catch (\Exception $e) {
            // Log error details
            \Log::error('Failed to update alarm status: '.$e->getMessage());
        }
    }
}
