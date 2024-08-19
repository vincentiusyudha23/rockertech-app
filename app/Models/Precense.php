<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Precense extends Model
{
    use HasFactory;

    protected $table = 'precenses';
    protected $fillable = ['employe_id','type','status', 'time'];
    // protected $casts = [ 'time' => 'time'];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
