<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    protected $fillable = [
        'name','specialty','phone','email','consultation_fee','qualifications','photo','bio','status',
    ];

    protected $casts = ['consultation_fee' => 'decimal:2'];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
