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
        'attachment'
    ];
    protected $casts = [
        'late' => 'string',  // Changed from float to string
        'overtime' => 'string',  // Changed from float to string
        'total_hours_rendered' => 'string',  // Changed from float to string
    ];
    protected $dates = [
        'date',
    ];

    // Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveApplication()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function vacationLeaveDetails()
    {
        return $this->hasMany(VacationLeaveDetails::class);
    }
    public function sickLeaveDetails()
    {
        return $this->hasMany(SickLeaveDetails::class);
    }
}
