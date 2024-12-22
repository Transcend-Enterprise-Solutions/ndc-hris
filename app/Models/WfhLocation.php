<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WfhLocation extends Model
{
    use HasFactory;

    protected $table = 'wfh_locations';

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
