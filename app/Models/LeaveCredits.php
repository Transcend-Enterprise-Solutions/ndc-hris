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

        'vl_claimable_credits',
        'sl_claimable_credits',
        'spl_claimable_credits',

        'vl_claimed_credits',
        'sl_claimed_credits',
        'spl_claimed_credits',

        'cto_total_credits',
        'cto_claimable_credits',
        'cto_claimed_credits',

        'fl_claimable_credits',
        'fl_claimed_credits',
        
        'credits_transferred',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
