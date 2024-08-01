<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveCredits extends Model
{
    use HasFactory;

    protected $table = 'leave_credits';

    protected $fillable = [
        'user_id',
        'total_credits',
        'claimable_credits',
        'total_claimed_credits',
        'credits_transferred',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
