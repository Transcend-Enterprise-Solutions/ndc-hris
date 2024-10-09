<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class PlantillaPayslip extends Model implements AuditableContract
{
    use HasFactory, Auditable;

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
