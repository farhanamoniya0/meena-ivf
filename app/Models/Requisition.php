<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = [
        'requested_by','medicine_id','quantity','reason',
        'status','approved_by','approved_at','approval_notes',
    ];

    protected $casts = ['approved_at' => 'datetime'];

    public function requestedBy() { return $this->belongsTo(User::class, 'requested_by'); }
    public function medicine()    { return $this->belongsTo(Medicine::class); }
    public function approvedBy()  { return $this->belongsTo(User::class, 'approved_by'); }
}
