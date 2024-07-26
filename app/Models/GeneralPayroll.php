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
        'net_amount_received',
        'amount_due_first_half',
        'amount_due_second_half',
        'date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    // public function scopeSearch($query, $term){
    //     $term = "%$term%";
    //     $query->where(function ($query) use ($term) {
    //         $query->where('general_payroll.name', 'like', $term)
    //             ->orWhere('general_payroll.employee_number', 'like', $term)
    //             ->orWhere('general_payroll.position', 'like', $term)
    //             ->orWhere('general_payroll.sg_step', 'like', $term);
    //     });
    // }
}
