<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationLeaveDetails extends Model
{
    use HasFactory;

    protected $table = 'vacation_leave_details';

    protected $fillable = [
        'application_id',
        'total_earned',
        'less_this_application',
        'balance',
        'recommendation',
        'status',
    ];

    public function leaveApplication()
    {
        return $this->belongsTo(LeaveApplication::class);
    }
}
