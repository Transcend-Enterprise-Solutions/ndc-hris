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
        'ut',
        'total_hours_rendered',
        'remarks',
        'attachment',
        'up_remarks',
        'updated_by',
        'up_morning_in',
        'up_morning_out',
        'up_afternoon_in',
        'up_afternoon_out',
        'up_late',
        'up_ut',
        'up_ot'
    ];

    protected $casts = [
        'late' => 'string',
        'overtime' => 'string',
        'ut' => 'string',
        'total_hours_rendered' => 'string',
    ];

    protected $dates = [
        'date',
    ];

    // Define relationships
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

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sickLeaveDetails()
    {
        return $this->hasMany(SickLeaveDetails::class);
    }
}
