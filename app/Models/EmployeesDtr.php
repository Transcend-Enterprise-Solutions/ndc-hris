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
        'emp_code',
        'date',
        'day_of_week',
        'location',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'late',
        'overtime',
        'total_hours_rendered',
        'remarks',
    ];
    protected $casts = [
        'late' => 'string',  // Changed from float to string
        'overtime' => 'string',  // Changed from float to string
        'total_hours_rendered' => 'string',  // Changed from float to string
    ];

    // Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
