<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'receipt_no','patient_package_id','patient_id','amount',
        'payment_method','transaction_id','bank_name','reference',
        'received_by','remarks','status','approved_by','approved_at',
    ];

    protected $casts = ['approved_at' => 'datetime'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            $payment->receipt_no = 'RCP-' . strtoupper(uniqid());
        });
    }

    public function patientPackage() { return $this->belongsTo(PatientPackage::class); }
    public function patient()        { return $this->belongsTo(Patient::class); }
    public function receivedBy()     { return $this->belongsTo(User::class, 'received_by'); }
    public function approvedBy()     { return $this->belongsTo(User::class, 'approved_by'); }
}
