<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaUploader extends Model
{
    use HasFactory;
    protected $table = 'media_uploaders';
    protected $fillable = ['title', 'path', 'size', 'user_type', 'user_id'];
}
