<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id', 'service_id', 'description',
        'quantity', 'unit_rate', 'discount', 'amount',
    ];

    protected $casts = [
        'unit_rate' => 'decimal:2',
        'amount'    => 'decimal:2',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
