<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesDtr extends Model
{
    use HasFactory;

    protected $table = 'employees_dtr';

    protected $fillable = [
        'user_id',
        'date',
        'day_of_week',
        'location',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'late',
        'overtime',
        'total_hours_endered',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
