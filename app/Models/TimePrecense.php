<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimePrecense extends Model
{
    use HasFactory;

    protected $fillable = ['min_in_office', 'max_in_office', 'min_out_office', 'type'];
}
