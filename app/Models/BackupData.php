<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupData extends Model
{
    use HasFactory;

    protected $table = 'backup_data';
    protected $fillable = ['path'];
}
