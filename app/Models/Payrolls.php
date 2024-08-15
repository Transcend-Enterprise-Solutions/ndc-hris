<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payrolls extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

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
        'total_deduction',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('payrolls.name', 'like', $term)
                ->orWhere('payrolls.employee_number', 'like', $term)
                ->orWhere('payrolls.position', 'like', $term)
                ->orWhere('payrolls.office_division', 'like', $term)
                ->orWhere('payrolls.sg_step', 'like', $term);
        });
    }

    public function scopeSearch2($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('payrolls.name', 'like', $term)
                ->orWhere('payrolls.employee_number', 'like', $term)
                ->orWhere('payrolls.position', 'like', $term)
                ->orWhere('payrolls.office_division', 'like', $term)
                ->orWhere('payrolls.sg_step', 'like', $term)
                ->orWhere('signatories.signatory', 'like', $term)
                ->orWhere('signatories.signatory_type', 'like', $term);
        });
    }

    public function scopeSearch3($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('payrolls.name', 'like', $term)
                ->orWhere('payrolls.employee_number', 'like', $term)
                ->orWhere('payrolls.position', 'like', $term)
                ->orWhere('payrolls.office_division', 'like', $term)
                ->orWhere('payrolls.sg_step', 'like', $term)
                ->orWhere('signatories.signatory', 'like', $term)
                ->orWhere('signatories.signatory_type', 'like', $term);
        });
    }
}
