<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class CosSkPayrolls extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'cos_sk_payrolls';

    protected $fillable = [
        'user_id',
        'sg_step',
        'rate_per_month', 
        'additional_premiums',
        'adjustment',
        'withholding_tax',
        'nycempc',
        'other_deductions',
        'total_deduction',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('cos_sk_payrolls.name', 'like', $term)
                ->orWhere('cos_sk_payrolls.employee_number', 'like', $term)
                ->orWhere('cos_sk_payrolls.position', 'like', $term)
                ->orWhere('cos_sk_payrolls.office_division', 'like', $term)
                ->orWhere('cos_sk_payrolls.sg_step', 'like', $term);
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
