<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaPayslip extends Model
{
    use HasFactory;

    protected $table = 'plantilla_payslip';

    protected $fillable = [
        'user_id',
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
        'other_deductions',
        'total_deduction',
        'net_amount_received',
        'first_half_amount',
        'second_half_amount',
        'start_date',
        'end_date',
        'prepared_by_name',
        'prepared_by_position',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
