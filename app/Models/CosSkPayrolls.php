<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CosSkPayrolls extends Model
{
    use HasFactory;

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
}
