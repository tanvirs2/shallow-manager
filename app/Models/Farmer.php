<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = [
        'name', 'mobile', 'village', 'union', 'upazila',
        'land_area', 'land_unit', 'land_description',
        'nid', 'is_active', 'notes',
    ];

    protected $casts = [
        'land_area' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function waterEntries()
    {
        return $this->hasMany(WaterEntry::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalBilledAttribute(): float
    {
        return (float) $this->waterEntries()->sum('total_amount');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getTotalDueAttribute(): float
    {
        return $this->total_billed - $this->total_paid;
    }

    public function getPaymentStatusAttribute(): string
    {
        $due = $this->total_due;
        if ($due <= 0) return 'paid';
        if ($this->total_paid > 0) return 'partial';
        return 'due';
    }
}
