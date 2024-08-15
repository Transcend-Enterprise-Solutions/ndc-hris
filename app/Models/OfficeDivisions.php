<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeDivisions extends Model
{
    use HasFactory;

    protected $table = 'office_divisions';

    protected $fillable = [
        'office_division',
    ];
}
