<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'user_addresses';
    protected $fillable = ['employee_id', 'street_address', 'kelurahan', 'kecamatan', 'kota', 'provinsi'];
}
