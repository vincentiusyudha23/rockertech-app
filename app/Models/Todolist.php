<?php

namespace App\Models;

use App\Models\Comments;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todolist extends Model
{
    use HasFactory;

    protected $table = 'todolists';
    protected $fillable = ['employee_id','title', 'desc', 'priority', 'status', 'index_task', 'type'];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class, 'todo_id', 'id');
    }
}
