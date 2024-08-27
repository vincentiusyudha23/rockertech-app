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

if(!function_exists('decryptPassword')){
    function decryptPassword($password = ''){
        return Crypt::decryptString($password);
    }
}

if(!function_exists('global_assets_path')){
    function global_assets_path($path)
    {
        if(env('CPANEL')){
            $publicHtmlPath = '/home/vincenti/public_html'; 
            return str_replace(['core/public/', 'core\\public\\'], '', $publicHtmlPath . '/' .$path);
        }else{
            return str_replace(['core/public/', 'core\\public\\'], '', public_path($path));
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
                    <div class="rounded-2 py-1 px-2 text-white text-center text-sm fw-bold bg-success">
                        IN OFFICE
                    </div>
                ';
                break;

            case 2:
                return '
                    <div class="rounded-2 py-1 px-2 text-white text-center text-sm fw-bold bg-info">
                        OUT OFFICE
                    </div>
                ';
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
                    <div class="rounded-2 py-1 text-white text-center text-sm fw-bold bg-gradient-success">
                        ON TIME
                    </div>
                ';
                break;

            case 2:
                return '
                    <div class="rounded-2 py-1 text-white text-center text-sm fw-bold bg-gradient-warning">
                        LATE
                    </div>
                ';
                break;

            case 3:
                return '
                    <div class="rounded-2 py-1 text-white text-center text-sm fw-bold bg-gradient-danger">
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