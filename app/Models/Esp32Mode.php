<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Esp32Mode extends Model
{
    use HasFactory;

    protected $table = 'esp32_modes';

    protected $fillable = ['action', 'status'];

    public function scopeSetRegis($query)
    {
       return $query->updateOrCreate(
            ['action' => 'registrasi', 'status' => 1],
            ['action' => 'registrasi', 'status' => 1]
       );
    }

    public function scopeGetStatusRegis($query): bool
    {
        return $query->where('action', 'registrasi')->where('status', 1)->exists();
    }

    public function scopeSetOffRegis($query)
    {
        return $query->where('action', 'registrasi')->update(['status' => 0]);
    }
}
