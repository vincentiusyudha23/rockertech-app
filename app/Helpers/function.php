<?php

use App\Models\StaticOption;
use App\Models\MediaUploader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

if (!function_exists('assets')) {
    function assets($param) {
        // Logika helper Anda
        return asset('assets/'.$param);
    }
}

if (!function_exists('storage_asset')) {
    function storage_asset($param) {
        // Logika helper Anda
        return asset('storage/media/'.$param);
    }
}

if(!function_exists('formatBytes')){
    function formatBytes($size,
        $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['',
            'KB',
            'MB',
            'GB',
            'TB'];

        return round(1024 ** ($base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}

if(!function_exists('get_data_image')){
    function get_data_image($id){
        $image = MediaUploader::find($id);
        
        $data = [
            'alt' => $image?->title ?? 'image',
            'img_url' => isset($image?->path) ? assets('img/employes/'.$image?->path) : asset('no-image.jpeg')
        ];

        return $data;
    }
}

if(!function_exists('get_data_file')){
    function get_data_file($id){
        $file = MediaUploader::find($id);
        
        $data = [
            'title' => $file?->path ?? 'no-file',
            'file_url' => isset($file?->path) ? assets('file_permit/employes/'.$file?->path) : null
        ];

        return $data;
    }
}

if(!function_exists('decryptPassword')){
    function decryptPassword($password = ''){
        return Crypt::decryptString($password);
    }
}

if(!function_exists('global_assets_path')){
    function global_assets_path($path)
    {
        if(env('CPANEL') === true){
            $publicHtmlPath = '/home/vincenti/public_html'; 
            return str_replace(['core/public/', 'core\\public\\'], '', $publicHtmlPath . '/' .$path);
        }else{
            return str_replace(['core/public/', 'core\\public\\'], '', public_path($path));
        }
    }
}

if(!function_exists('route_prefix')){
    function route_prefix(){
        if(auth()->user()->hasRole('admin')){
            return 'admin.';
        }else{
            return 'employe.';
        }
    }
}


if(!function_exists('update_static_option')){
    function update_static_option($key,$value) : bool
    {
        $static_option = null;
        if ($static_option === null) {
            try {
                $static_option = StaticOption::query();
            } catch (\Exception $e) {
            }
        }
        try {
            $static_option->updateOrCreate(['option_name' => $key], ['option_name'  => $key,'option_value' => $value,]);
        } catch (\Exception $e) {
            return false;
        }

        Cache::forget($key);

        return true;
    }
}

if(!function_exists('get_static_option')){
    function get_static_option($option_name, $default = null)
    {
        $value = Cache::remember($option_name, 30 * 24 * 60 * 60, function () use ( $option_name) {
            return StaticOption::where('option_name', $option_name)->first();
        });

        return $value->option_value ?? $default;
    }
}

if(!function_exists('labelType')){
    function labelType($id)
    {
        switch ($id) {
            case 1:
                return '
                    <div class="badge badge-sm bg-success">
                        IN OFFICE
                    </div>
                ';
                break;

            case 2:
                return '
                    <div class="badge badge-sm bg-info">
                        OUT OFFICE
                    </div>
                ';
                break;

            case 3:
                return '
                    <div class="badge badge-sm bg-warning">
                        WFH
                    </div>
                ';
                break;
            
            default:
                return '';
                break;
        }
    }
}

if(!function_exists('labelTypeString')){
    function labelTypeString($id)
    {
        switch ($id) {
            case 1:
                return 'IN OFFICE';
                break;
            case 2:
                return 'OUT OFFICE';
                break;
            case 3:
                return 'WORK FROM HOME';
                break;
            
            default:
                return '';
                break;
        }
    }
}

if(!function_exists('labelStatus')){
    function labelStatus($id = '')
    {
        switch ($id) {
            case 1:
                return '
                    <div class="badge badge-sm bg-gradient-success">
                        ON TIME
                    </div>
                ';
                break;

            case 2:
                return '
                    <div class="badge badge-sm bg-gradient-warning">
                        LATE
                    </div>
                ';
                break;

            case 3:
                return '
                    <div class="badge badge-sm bg-gradient-danger">
                        ABSEN
                    </div>
                ';
                break;
            
            default:
                return '';
                break;
        }
    }
}

if(!function_exists('labelStatusString')){
    function labelStatusString($id = '')
    {
        switch ($id) {
            case 1:
                return 'ON TIME';
                break;
            case 2:
                return 'LATE';
                break;
            case 3:
                return 'ABSEN';
                break;
            
            default:
                return '';
                break;
        }
    }
}

if(!function_exists('labelPosition')){
    function labelPosition($val)
    {
        switch (true) {
            case $val == 1:
                return 'Content Planner';
                break;
            case $val == 2:
                return 'Designer';
                break;
            case $val == 3:
                return 'Business Admin';
                break;
            case $val == 4:
                return 'Sales';
                break;
            case $val == 5:
                return 'Assistant Manager';
                break;
            
            default:
                return $val ?? '';
                break;
        }
    }
}

if(!function_exists('sendToEmail')){
    function sendToEmail($data = [])
    {
        if (!isset($data['to']) || !isset($data['subject']) || !isset($data['view'])) {
            return false;
        }

        dispatch(function () use ($data) {
            try {
                $viewData = $data['viewData'] ?? [];
                $viewData['direction'] = ($data['direction'] ?? 'rtl');
                \Mail::send('emails.'.$data['view'], $viewData, function($message) use ($data) {
                    if(isset($data['viewData']['from_name']) && !is_null($data['viewData']['from_name'])){
                        $message->from(($data['viewData']['from_address'] ?? env('MAIL_FROM_ADDRESS','email@email.com')) , ($data['viewData']['from_name'] ?? env('MAIL_FROM_NAME','RockerTech')));
                    }
                    $message->to($data['to'])->subject($data['subject']);
                    foreach ($data['attachments'] ?? [] as $attachment) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name'],
                        ]);
                    }
                });

                \Log::channel('info')->info('Email sending',[
                    'to' => $data['to'],
                    'subject' => $data['subject'],
                ]);

            } catch (\Exception $e) {
                if(app()->environment('local')){
                    dd($e->getMessage());
                }

                \Log::error('Error while sending email: ' . $e->getMessage());
            }
        });

        return true;
    }
}