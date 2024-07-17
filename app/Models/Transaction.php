<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';   
    protected $fillable = [
        'emp_code',
        'punch_time',
        'punch_state',
        'punch_state_display',
        'verify_type',
        'verify_type_display',
        'area_alias',
        'upload_time',
    ];
}
