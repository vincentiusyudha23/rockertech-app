<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermitSubmission extends Model
{
    use HasFactory;

    protected $table = 'permit_submissions';
    protected $fillable = ['employee_id', 'type', 'from_date', 'to_date', 'reason', 'file_id', 'status'];
    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function employe()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
