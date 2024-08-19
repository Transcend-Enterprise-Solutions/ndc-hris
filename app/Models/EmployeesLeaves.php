<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesLeaves extends Model
{
    use HasFactory;

    protected $table = 'employees_leaves';

    protected $fillable = [
        'user_id',
        'paternity',
        'study',
        'maternity',
        'solo_parent',
        'vawc',
        'rehabilitation',
        'leave_for_women',
        'emergency_leave',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
