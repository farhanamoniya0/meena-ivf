<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'employee_id', 'name', 'designation', 'department',
        'phone', 'email', 'nid', 'join_date', 'salary',
        'address', 'photo', 'notes', 'status',
    ];

    protected $casts = [
        'join_date' => 'date',
        'salary'    => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($staff) {
            if (! $staff->employee_id) {
                $year  = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $staff->employee_id = 'EMP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
