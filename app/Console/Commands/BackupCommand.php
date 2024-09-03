<?php

namespace App\Console\Commands;

use App\Models\Precense;
use App\Models\BackupData;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:precense';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
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
             $this->info('Berhasil Dijalankan');
        }catch(\Exception $e){
            \Log::error('Failed to update alarm status: '.$e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
