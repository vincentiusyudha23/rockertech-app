<?php

namespace App\Console\Commands;

use App\Models\BackupData;
use App\Models\KeyPerformance;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Controllers\AdminController;

class SavedKeyPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:saved-key-performance';

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
        $controller = new AdminController();
        $employes = $controller->kpiAllEmploye();

        collect($employes)->each(function($employe){
            KeyPerformance::create([
                'employee_id' => $employe['id'],
                'todolist' => $employe['todolist'], 
                'precense' => $employe['precense'], 
                'achiev' => $employe['achiev'], 
                'initiative' => $employe['initiativ'], 
                'final_score' => $employe['final_score']
            ]);
        });

        $file_name = 'KPI_'.now()->translatedFormat('F').'_'.now()->year.'.xlsx';
        $file_path = global_assets_path('assets/backup/'.$file_name);

        $export = (new FastExcel($employes))->export($file_path, function($employe){
            return [
                'Name' => $employe['name'],
                'Position' => labelPosition($employe['position']),
                'KPI Todolist' => $employe['todolist'], 
                'KPI Precense' => $employe['precense'], 
                'KPI Target Achievment' => $employe['achiev'], 
                'KPI Initiative' => $employe['initiativ'], 
                'Final Score KPI' => $employe['final_score']
            ];
        });

        if($export){
            BackupData::create([
                'path' => $file_name
            ]);
        }

        $this->info('Berhasil');
    }
}
