<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'bill_no', 'patient_id', 'consultant_id', 'bill_date',
        'subtotal', 'discount', 'net_total', 'paid_amount',
        'payment_method', 'transaction_id', 'payment_date',
        'notes', 'status', 'created_by', 'payment_meta',
    ];

    protected $casts = [
        'bill_date'    => 'date',
        'payment_date' => 'date',
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'net_total'    => 'decimal:2',
        'paid_amount'  => 'decimal:2',
        'payment_meta' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($bill) {
            if (! $bill->bill_no) {
                $year  = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $bill->bill_no = 'BILL-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recalculate(): void
    {
        $this->load('items');
        $subtotal = $this->items->sum('amount');
        $netTotal = max(0, $subtotal - $this->discount);
        $status   = 'draft';
        if ($this->paid_amount >= $netTotal && $netTotal > 0) {
            $status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $status = 'partial';
        }
        $this->update([
            'subtotal'  => $subtotal,
            'net_total' => $netTotal,
            'status'    => $status,
        ]);
    }

    public function getBalanceAttribute(): float
    {
        return max(0, $this->net_total - $this->paid_amount);
    }

    public static function amountInWords(float $amount): string
    {
        $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
                 'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
                 'Seventeen','Eighteen','Nineteen'];
        $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];

        $n = (int) round($amount);
        if ($n === 0) return 'Zero Taka Only';

        $convert = function (int $num) use (&$convert, $ones, $tens): string {
            if ($num === 0)   return '';
            if ($num < 20)    return $ones[$num] . ' ';
            if ($num < 100)   return $tens[(int)($num/10)] . ' ' . ($num%10 ? $ones[$num%10].' ' : '');
            if ($num < 1000)  return $ones[(int)($num/100)] . ' Hundred ' . $convert($num%100);
            if ($num < 100000) return $convert((int)($num/1000)) . 'Thousand ' . $convert($num%1000);
            if ($num < 10000000) return $convert((int)($num/100000)) . 'Lakh ' . $convert($num%100000);
            return $convert((int)($num/10000000)) . 'Crore ' . $convert($num%10000000);
        };

        return trim($convert($n)) . ' Taka Only';
    }
}
