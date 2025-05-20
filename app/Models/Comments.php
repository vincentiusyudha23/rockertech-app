<?php

namespace App\Models;

use App\Models\User;
use App\Models\Todolist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = ['user_id', 'todo_id', 'content'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function todolist(): BelongsTo
    {
        return $this->belongsTo(Todolist::class, 'todo_id', 'id');
    }
}
