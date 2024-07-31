<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveCreditsCalculation extends Model
{
    use HasFactory;

    protected $table = 'leave_credits_calculation';

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'late_time',
        'total_credits_earned',
        'leave_credits_earned',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
