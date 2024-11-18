<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class CosSkPayslip extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'cos_sk_payslip';

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
    
    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('users.name', 'like', $term)
                ->orWhere('users.emp_code', 'like', $term);
        });
    }
}
