<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pathfinder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'ddd',
        'phone',
        'full_phone',
        'birthday',
        'age',
        'responsible_name',
        'responsible_phone',
        'email',
        'address',
        'unit_id',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
