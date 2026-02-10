<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    public function pathfinders(): HasMany
    {
        return $this->hasMany(Pathfinder::class);
    }
}
