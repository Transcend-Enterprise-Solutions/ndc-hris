<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeDivisionUnits extends Model
{
    use HasFactory;

    protected $table = 'office_division_units';

    protected $fillable = [
        'office_division_id',
        'unit',
    ];

    public function officeDivisions(){
        return $this->belongsTo(OfficeDivisions::class);
    }

    public function positions(){
        return $this->hasMany(Positions::class, 'unit_id');
    }
}
