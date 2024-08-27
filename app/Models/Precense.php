<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Precense extends Model
{
    use HasFactory;

    protected $table = 'precenses';
    protected $fillable = ['employe_id','type','status', 'time', 'image'];
    // protected $casts = [ 'time' => 'time'];

    public function scopeTodayPrecense($query)
    {
        return $query->whereDate('created_at', Carbon::now());
    }
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
