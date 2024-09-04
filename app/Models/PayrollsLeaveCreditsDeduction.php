<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollsLeaveCreditsDeduction extends Model
{
    use HasFactory;

    protected $table = 'payrolls_leave_credits_deduction';

    protected $fillable = [
        'user_id',
        'month',
        'credits_deducted',
        'salary_deduction_credits',
        'salary_deduction_amount',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
