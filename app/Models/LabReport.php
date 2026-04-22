<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReport extends Model
{
    protected $fillable = [
        'sample_code','patient_id','bill_item_id','test_type','status',
        'created_by','collected_by','collected_at',
        'processed_by','processed_at',
        'reported_by','reported_at',
        'delivered_by','delivered_at',
        'report_data','notes',
    ];

    protected $casts = [
        'report_data'  => 'array',
        'collected_at' => 'datetime',
        'processed_at' => 'datetime',
        'reported_at'  => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($r) {
            $year  = date('Y');
            $count = static::whereYear('created_at', $year)->count() + 1;
            $r->sample_code = 'LAB-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }

    public function patient()   { return $this->belongsTo(Patient::class); }
    public function billItem()  { return $this->belongsTo(BillItem::class); }
    public function creator()   { return $this->belongsTo(User::class, 'created_by'); }
    public function collector() { return $this->belongsTo(User::class, 'collected_by'); }
    public function processor() { return $this->belongsTo(User::class, 'processed_by'); }
    public function reporter()  { return $this->belongsTo(User::class, 'reported_by'); }
    public function deliverer() { return $this->belongsTo(User::class, 'delivered_by'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Pending Collection',
            'collected'  => 'Sample Collected',
            'processing' => 'Processing / Analysis',
            'ready'      => 'Report Ready',
            'delivered'  => 'Delivered to Patient',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => '#6b7280',
            'collected'  => '#0891b2',
            'processing' => '#d97706',
            'ready'      => '#7c3aed',
            'delivered'  => '#16a34a',
            default      => '#6b7280',
        };
    }

    public function getStatusBgAttribute(): string
    {
        return match($this->status) {
            'pending'    => '#f3f4f6',
            'collected'  => '#e0f2fe',
            'processing' => '#fef3c7',
            'ready'      => '#ede9fe',
            'delivered'  => '#dcfce7',
            default      => '#f3f4f6',
        };
    }

    public function getNextStatusAttribute(): ?string
    {
        return match($this->status) {
            'pending'    => 'collected',
            'collected'  => 'processing',
            'processing' => 'ready',
            'ready'      => 'delivered',
            default      => null,
        };
    }

    public function getNextActionLabelAttribute(): ?string
    {
        return match($this->status) {
            'pending'    => 'Mark as Collected',
            'collected'  => 'Start Processing',
            'processing' => 'Submit Report & Mark Ready',
            'ready'      => 'Mark as Delivered',
            default      => null,
        };
    }
}
