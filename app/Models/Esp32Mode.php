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
            ['action' => 2],
            ['action' => 2, 'status' => 1]
       );
    }

    public function scopeSetPrecense($query)
    {
        return $query->updateOrCreate(
            ['action' => 1],
            ['action' => 1, 'status' => 1]
        );
    }

    public function scopeGetStatusRegis($query): bool
    {
        return $query->where('action', 2)->where('status', 1)->exists();
    }

    public function scopeGetStatusPrecense($query): bool
    {
        return $query->where('action', 1)->where('status', 1)->exists();
    }

    public function scopeSetOffRegis($query)
    {
        return $query->where('action', 2)->update(['status' => 0]);
    }

    public function scopeSetOffPrecense($query)
    {
        return $query->where('action', 1)->update(['status' => 0]);
    }
}
