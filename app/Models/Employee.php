<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = ['user_id', 'name', 'position', 'email', 'nik', 'birthday', 'address', 'mobile'];

    public function user()
    {
        $this->hasOne(User::class);
    }

    public function address()
    {
        $this->hasOne(UserAddress::class);
    }
}
