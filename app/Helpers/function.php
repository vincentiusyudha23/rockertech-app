<?php

use App\Models\MediaUploader;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('assets')) {
    function assets($param) {
        // Logika helper Anda
        return asset('assets/'.$param);
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
            'alt' => $image->title,
            'img_url' => asset('storage/media/'.$image->path)
        ];

        return $data;
    }
}

if(!function_exists('decryptPassword')){
    function decryptPassword($password = ''){
        return Crypt::decryptString($password);
    }
}