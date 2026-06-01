<?php

namespace App\Imports;

use App\Models\Farmer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class FarmersImport implements ToModel, WithHeadingRow
{
    public function model(array $row): ?Farmer
    {
        if (empty($row[array_key_first($row)])) return null;

        $keys = array_keys($row);
        return new Farmer([
            'name'             => $row[$keys[0]] ?? '',
            'mobile'           => $row[$keys[1]] ?? '',
            'village'          => $row[$keys[2]] ?? null,
            'union'            => $row[$keys[3]] ?? null,
            'upazila'          => $row[$keys[4]] ?? null,
            'land_area'        => $row[$keys[5]] ?? 0,
            'land_unit'        => in_array($row[$keys[6]] ?? '', ['acre', 'shotok', 'bigha']) ? $row[$keys[6]] : 'shotok',
            'land_description' => $row[$keys[7]] ?? null,
            'is_active'        => true,
        ]);
    }
}
