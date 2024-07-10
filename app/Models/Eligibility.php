<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eligibility extends Model
{
    use HasFactory;

    protected $table = 'eligibility';

    protected $fillable = [
        'user_id',
        'rating',
        'date',
        'place_of_exam',
        'license',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
