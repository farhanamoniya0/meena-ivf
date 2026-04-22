<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name','generic_name','brand','category','unit','reorder_level','description','status',
    ];

    public function batches()     { return $this->hasMany(MedicineBatch::class); }
    public function assignments() { return $this->hasMany(MedicineAssignment::class); }
    public function requisitions(){ return $this->hasMany(Requisition::class); }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->batches()->sum('quantity');
    }

    public function isLowStock(): bool
    {
        return $this->total_stock <= $this->reorder_level;
    }
}
