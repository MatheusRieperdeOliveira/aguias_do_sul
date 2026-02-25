<?php

namespace App\Services;

use App\Models\Unit;
use Illuminate\Support\Collection;

class UnitService
{
    public function storeUnit(array $unit)
    {
        return Unit::updateOrCreate(
            [
                'name' => $unit['name'],
                'status' => 'active',
            ]
        );
    }

    public function getUnits(): ?Collection
    {
        return Unit::all();
    }
}
