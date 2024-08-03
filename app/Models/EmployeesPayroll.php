<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesPayroll extends Model
{
    use HasFactory;

    protected $table = 'employees_payroll';

    protected $fillable = [
        'user_id',
        'name',
        'employee_number',
        'office_division',
        'position',
        'salary_grade',
        'daily_salary_rate',
        'no_of_days_covered',
        'regular_holidays',
        'regular_holidays_amount',
        'special_holidays',
        'special_holidays_amount',
        'leave_days_withpay',
        'leave_payment',
        'gross_salary',
        'leave_days_withoutpay',
        'leave_days_withoutpay_amount',
        'absences_days',
        'absences_amount',
        'late_undertime_hours',
        'late_undertime_hours_amount',
        'late_undertime_mins',
        'late_undertime_mins_amount',
        'gross_salary_less',
        'withholding_tax',
        'nycempc',
        'total_deductions',
        'net_amount_due',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('name', 'like', $term)
                ->orWhere('employee_number', 'like', $term)
                ->orWhere('position', 'like', $term)
                ->orWhere('salary_grade', 'like', $term);
        });
    }
}
