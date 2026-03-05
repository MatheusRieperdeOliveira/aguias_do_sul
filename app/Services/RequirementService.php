<?php

namespace App\Services;

use App\Models\Requirement;
use Illuminate\Support\Collection;

class RequirementService
{
    public function geAlltRequirements(): Collection
    {
        return Requirement::all();
    }

    public function getRequirements(string $type): Collection
    {
        return Requirement::query()->where('type', $type)->get();
    }

}
