<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Precense;
use App\Models\BackupData;
use Illuminate\Bus\Queueable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BackupPrecenseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        Carbon::setLocale('id');

        $precense = Precense::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->get();

        $file_name = 'Precense_'.now()->translatedFormat('F').'_'.now()->year.'.xlsx';

        $file_path = global_assets_path('assets/backup/'.$file_name);

        $export = (new FastExcel($precense))->export($file_path, function($precense){
            return [
                'Name' => $precense?->employe?->name,
                'Position' => $precense?->employe?->position,
                'Type' => labelTypeString($precense->type),
                'Status' => labelStatusString($precense->status),
                'Time' => $precense?->time,
                'Date' => $precense?->created_at->format('d-m-Y')
            ];
        });

        if($export){
            BackupData::create([
                'path' => $file_name
            ]);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
