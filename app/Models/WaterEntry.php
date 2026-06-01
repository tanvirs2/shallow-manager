<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterEntry extends Model
{
    protected $fillable = [
        'farmer_id', 'supply_date', 'hours', 'rate_per_hour',
        'total_amount', 'season', 'notes',
    ];

    protected $casts = [
        'supply_date' => 'date',
        'hours' => 'decimal:2',
        'rate_per_hour' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (WaterEntry $entry) {
            $entry->total_amount = round($entry->hours * $entry->rate_per_hour, 2);
        });
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getDueAmountAttribute(): float
    {
        return (float) $this->total_amount - $this->paid_amount;
    }

    public function getPaymentStatusAttribute(): string
    {
        $due = $this->due_amount;
        if ($due <= 0) return 'paid';
        if ($this->paid_amount > 0) return 'partial';
        return 'due';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'    => '<span class="badge bg-success">পরিশোধ</span>',
            'partial' => '<span class="badge bg-warning text-dark">আংশিক</span>',
            default   => '<span class="badge bg-danger">বাকি</span>',
        };
    }
}
