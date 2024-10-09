<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Payrolls extends Model implements AuditableContract
{
    use HasFactory, Auditable;

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
        'pagibig_calamity_loan',
        'pagibig_contribution',
        'pagibig_gs',
        'lwop',
        'gsis_rlip',
        'gsis_gs',
        'gsis_ecip',
        'nycempc_mpl',
        'nycempc_educ_loan',
        'nycempc_pi',
        'nycempc_business_loan',
        'nycempc_total',
        'w_holding_tax',
        'philhealth',
        'philhealth_es',
        'other_deductions',
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

    public function getAuditDescriptionForEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "Created by user {$this->user->name}";
            case 'updated':
                return "Updated by user {$this->user->name}";
            case 'deleted':
                return "Deleted by user {$this->user->name}";
            default:
                return '';
        }
    }
}
