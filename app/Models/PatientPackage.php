<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPackage extends Model
{
    protected $fillable = [
        'patient_id','ivf_package_id','assigned_by','total_amount',
        'discount','discount_approved_by','start_date','status','notes',
    ];

    protected $casts = ['start_date' => 'date'];

    public function patient()    { return $this->belongsTo(Patient::class); }
    public function ivfPackage() { return $this->belongsTo(IvfPackage::class); }
    public function assignedBy() { return $this->belongsTo(User::class, 'assigned_by'); }
    public function payments()   { return $this->hasMany(Payment::class); }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->where('status', 'approved')->sum('amount');
    }

    public function getRemainingAttribute(): float
    {
        $net = $this->total_amount - $this->discount;
        return max(0, $net - $this->paid_amount);
    }

    public function getNetAmountAttribute(): float
    {
        return $this->total_amount - $this->discount;
    }
}
