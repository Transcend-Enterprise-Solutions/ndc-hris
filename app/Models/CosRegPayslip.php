<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CosRegPayslip extends Model
{
    use HasFactory;

    protected $table = 'cos_reg_payslip';

    protected $fillable = [
        'user_id',
        'salary_grade',
        'rate_per_month',
        'days_covered',
        'gross_salary',
        'absences_days',
        'absences_amount',
        'late_undertime_hours',
        'late_undertime_hours_amount',
        'late_undertime_minutes',
        'late_undertime_minutes_amount',
        'gross_salary_less',
        'additional_premiums',
        'adjustment',
        'w_holding_tax',
        'nycempc',
        'other_deductions',
        'total_deduction',
        'net_amount_received',
        'start_date',
        'end_date',
        'prepared_by_name',
        'prepared_by_position',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
