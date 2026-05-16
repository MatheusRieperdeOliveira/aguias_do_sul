<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'score',
        'type',
    ];

    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }
}
