<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralPayroll extends Model
{
    use HasFactory;

    protected $table = 'general_payroll';

    protected $fillable = [
        'user_id',
        'employee_number',
        'position',
        'sg_step',
        'rate_per_month',
        'personal_economic_relief_allowance',
        'gross_amount',
        'additional_gsis_premium',
        'lbp_salary_loan',
        'nycea_deductions',
        'sc_membership',
        'total_loans',
        'salary_loan',
        'policy_loan',
        'eal',
        'emergency_loan',
        'mpl',
        'housing_loan',
        'ouli_prem',
        'gfal',
        'cpl',
        'pagibig_mpl',
        'other_deduction_philheath_diff',
        'life_retirement_insurance_premiums',
        'pagibig_contribution',
        'w_holding_tax',
        'philhealth',
        'total_deduction',
        'net_amount_received',
        'amount_due_first_half',
        'amount_due_second_half',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
