<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id','consultant_id','department_id','appointment_date',
        'appointment_time','type','status','notes','created_by',
    ];

    protected $casts = ['appointment_date' => 'date'];

    public function patient()    { return $this->belongsTo(Patient::class); }
    public function consultant() { return $this->belongsTo(Consultant::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function createdBy()  { return $this->belongsTo(User::class, 'created_by'); }
}
