<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PumpOwner extends Model
{
    protected $fillable = [
        'user_id', 'name', 'mobile', 'pump_name', 'village', 'address',
        'rate_per_hour', 'nid', 'notes',
    ];

    protected $casts = [
        'rate_per_hour' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
