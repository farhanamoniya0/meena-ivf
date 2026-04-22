<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyClosing extends Model
{
    protected $fillable = [
        'closing_date','total_cash','total_bank','total_card',
        'total_bkash','total_nagad','total_rocket','total_amount',
        'total_transactions','closed_by','notes','status','closed_at',
    ];

    protected $casts = ['closing_date' => 'date', 'closed_at' => 'datetime'];

    public function closedBy() { return $this->belongsTo(User::class, 'closed_by'); }
}
