<?php

namespace App\Imports;

use App\Models\Farmer;
use App\Models\WaterEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WaterEntriesImport implements ToModel, WithHeadingRow
{
    public function model(array $row): ?WaterEntry
    {
        $keys = array_keys($row);
        if (empty($row[$keys[0]])) return null;

        $farmer = Farmer::where('mobile', $row[$keys[0]])->first();
        if (!$farmer) return null;

        return new WaterEntry([
            'farmer_id'     => $farmer->id,
            'supply_date'   => $row[$keys[1]] ?? now()->toDateString(),
            'hours'         => $row[$keys[2]] ?? 0,
            'rate_per_hour' => $row[$keys[3]] ?? 0,
            'season'        => $row[$keys[4]] ?? null,
            'notes'         => $row[$keys[5]] ?? null,
        ]);
    }
}
