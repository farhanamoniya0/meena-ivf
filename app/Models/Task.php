<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title','description','assigned_to','assigned_by',
        'patient_id','department_id','priority','due_date','status',
    ];

    protected $casts = ['due_date' => 'date'];

    public function assignedTo()  { return $this->belongsTo(User::class, 'assigned_to'); }
    public function assignedBy()  { return $this->belongsTo(User::class, 'assigned_by'); }
    public function patient()     { return $this->belongsTo(Patient::class); }
    public function department()  { return $this->belongsTo(Department::class); }
}
