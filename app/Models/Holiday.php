<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'holiday_date',
        'description',
        'type',
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];

    // Optionally, you can add relationships or custom methods here
}
