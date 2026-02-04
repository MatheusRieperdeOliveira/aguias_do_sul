<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pathfinder extends Model
{
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
    ];
}
