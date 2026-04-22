<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineAssignment extends Model
{
    protected $fillable = [
        'patient_id','medicine_id','medicine_batch_id','quantity','assigned_by','notes',
    ];

    public function patient()  { return $this->belongsTo(Patient::class); }
    public function medicine() { return $this->belongsTo(Medicine::class); }
    public function batch()    { return $this->belongsTo(MedicineBatch::class, 'medicine_batch_id'); }
    public function assignedBy(){ return $this->belongsTo(User::class, 'assigned_by'); }
}
