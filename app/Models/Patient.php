<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_code','first_name','last_name','name','dob','age','gender','marital_status',
        'phone','phone_alt','address','post_code','thana','district','division',
        'nid_number','blood_group','height_cm','weight_kg','religion','occupation',
        'referred_by','source_type','photo','nid_photo','consultant_id',
        'registration_type','status','notes','advance_balance',
    ];

    protected $casts = ['dob' => 'date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patient) {
            $year  = date('Y');
            $count = static::whereYear('created_at', $year)->count() + 1;
            $patient->patient_code = 'MIV-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }

    public function consultant()   { return $this->belongsTo(Consultant::class); }
    public function couple()       { return $this->hasOne(Couple::class); }
    public function packages()     { return $this->hasMany(PatientPackage::class); }
    public function payments()     { return $this->hasMany(Payment::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function tasks()        { return $this->hasMany(Task::class); }
    public function labReports()   { return $this->hasMany(LabReport::class); }

    public function activePackage()
    {
        return $this->hasOne(PatientPackage::class)->where('status', 'active')->latest();
    }
}
