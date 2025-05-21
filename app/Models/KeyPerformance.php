<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeyPerformance extends Model
{
    use HasFactory;

    protected $table = 'key_performances';
    protected $fillable = ['employee_id', 'todolist', 'precense', 'achiev', 'initiative', 'final_score'];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
