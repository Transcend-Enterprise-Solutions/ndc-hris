<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wfh extends Model
{
    use HasFactory;
    protected $table = 'wfh';
    protected $fillable = [
        'wfhDay',
        'status',
        'user_id',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'wfh_reason'
    ];

    protected $casts = [
        'wfhDay' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

