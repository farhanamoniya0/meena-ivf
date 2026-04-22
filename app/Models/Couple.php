<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    protected $fillable = [
        'patient_id',
        'husband_name','husband_dob','husband_age','husband_phone',
        'husband_nid','husband_photo','husband_occupation','husband_blood_group',
        'marriage_date','medical_history',
        'partner_first_name','partner_last_name','partner_gender','partner_marital_status',
        'partner_phone','partner_occupation','partner_blood_group',
        'partner_height_cm','partner_weight_kg',
        'partner_address','partner_post_code','partner_thana','partner_district','partner_division',
    ];

    protected $casts = ['husband_dob' => 'date', 'marriage_date' => 'date'];

    public function patient() { return $this->belongsTo(Patient::class); }
}
