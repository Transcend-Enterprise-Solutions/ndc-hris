<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTRSchedule extends Model
{
    use HasFactory;


    protected $table = 'dtrschedules';


    protected $fillable = [
        'emp_code',
        'wfh_days',
        'default_start_time',
        'default_end_time',
        'start_date',
        'end_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'emp_code', 'emp_code');
    }

}
