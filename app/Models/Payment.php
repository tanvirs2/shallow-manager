<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'farmer_id', 'water_entry_id', 'amount',
        'payment_date', 'method', 'reference', 'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function waterEntry()
    {
        return $this->belongsTo(WaterEntry::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'cash'   => 'নগদ',
            'bkash'  => 'বিকাশ',
            'nagad'  => 'নগদ (Nagad)',
            'rocket' => 'রকেট',
            'bank'   => 'ব্যাংক',
            default  => 'অন্যান্য',
        };
    }
}
