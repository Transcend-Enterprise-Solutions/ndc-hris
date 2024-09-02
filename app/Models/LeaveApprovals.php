<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApprovals extends Model
{
    use HasFactory;

    protected $table = 'leave_approvals';

    protected $fillable = [
        'user_id',
        'application_id',
        'first_approver',
        'second_approver',
        'third_approver',
        'stage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveApplication()
    {
        return $this->belongsTo(LeaveApplication::class);
    }
}
