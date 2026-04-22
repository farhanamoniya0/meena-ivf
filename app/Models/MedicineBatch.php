<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id','batch_number','expiry_date','quantity','purchase_price','sale_price',
    ];

    protected $casts = ['expiry_date' => 'date'];

    public function medicine() { return $this->belongsTo(Medicine::class); }

    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    public function isExpiringSoon(): bool
    {
        return $this->expiry_date->diffInDays(now()) <= 30 && !$this->isExpired();
    }
}
