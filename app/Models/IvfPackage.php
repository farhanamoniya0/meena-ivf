<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IvfPackage extends Model
{
    protected $fillable = [
        'name','description','total_cost','included_services','duration_days','status',
    ];

    public function patientPackages()
    {
        return $this->hasMany(PatientPackage::class);
    }
}
