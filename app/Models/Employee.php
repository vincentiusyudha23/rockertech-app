<?php

namespace App\Models;

use App\Models\User;
use App\Models\Precense;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees';
    protected $fillable = ['user_id', 'name', 'position', 'email', 'nik', 'birthday', 'address', 'mobile', 'image', 'enc_password'];
    protected $casts = [ 'birthday' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function precense(): HasMany
    {
        return $this->hasMany(Precense::class, 'employe_id', 'id');
    }
}
