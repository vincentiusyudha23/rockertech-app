<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MediaUploader;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    public function media_upload(Request $request)
    {
        $this->validate($request, [
            'file' => ['nullable', 'mimes:jpg,jpeg,png,gif,webp,pdf', 'max:25000']
        ]);

        if($request->hasFile('file')){
            $image = $request->file;

            $image_extension = $image->extension();
            $image_name_with_ext = $image->getClientOriginalName();

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));

            $image_db = $image_name.time().'.'.$image_extension;
            $folder_path = global_assets_path('assets/img/employes');
            $image->move($folder_path, $image_db);

            if($image){
                $mediaData = MediaUploader::create([
                    'title' => $image_name_with_ext,
                    'path' => $image_db,
                    'size' => null,
                    'user_id' => Auth::user()->id
                ]);

                if($mediaData){
                    return response()->json([
                        'id' => $mediaData->id
                    ], 200);
                }
            }
        }
    }
}
