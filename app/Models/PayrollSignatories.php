<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollSignatories extends Model
{
    use HasFactory;

    protected $table = 'payroll_signatories';

    protected $fillable = [
        'user_id',
        'signatory',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('payroll_signatories.signatory', 'like', $term);
        });
    }
}
