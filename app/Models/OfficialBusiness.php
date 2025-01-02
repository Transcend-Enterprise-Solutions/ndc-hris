<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialBusiness extends Model
{
    use HasFactory;

    protected $table = 'official_businesses';

    protected $fillable = [
        'user_id',
        'reference_number',
        'company',
        'address',
        'lat',        
        'lng',        
        'date',  
        'time_start',  
        'time_end',  
        'purpose',  
        'status',  
        'approver',  
        'date_approved',  
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
