<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['service_code', 'name', 'category', 'charge', 'description', 'status'];

    protected $casts = ['charge' => 'decimal:2'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
