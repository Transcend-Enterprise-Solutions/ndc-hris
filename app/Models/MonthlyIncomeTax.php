<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyIncomeTax extends Model
{
    use HasFactory;

    protected $table = 'monthly_income_taxes';

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'salary',
        'tax',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
